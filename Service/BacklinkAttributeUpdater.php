<?php

namespace MageSuite\CmsProductBacklink\Service;

class BacklinkAttributeUpdater
{
    /**
     * @var \MageSuite\CmsProductBacklink\Model\ProductsRepository
     */
    protected $backlinkProductsRepository;

    /**
     * @var \MageSuite\CmsProductBacklink\DataProviders\ProductsAndPagesProvider
     */
    protected $productsAndPagesProvider;

    /**
     * @var \MageSuite\CmsProductBacklink\Helper\Data
     */
    protected $dataHelper;

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $productRepository;

    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    protected $serializer;

    public function __construct(
        \MageSuite\CmsProductBacklink\Model\ProductsRepository $backlinkProductsRepository,
        \MageSuite\CmsProductBacklink\DataProviders\ProductsAndPagesProvider $productsAndPagesProvider,
        \MageSuite\CmsProductBacklink\Helper\Data $dataHelper,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Framework\Serialize\Serializer\Json $serializer
    ) {
        $this->backlinkProductsRepository = $backlinkProductsRepository;
        $this->productsAndPagesProvider = $productsAndPagesProvider;
        $this->dataHelper = $dataHelper;
        $this->productRepository = $productRepository;
        $this->serializer = $serializer;
    }

    public function execute($storeId, $pageId = null)
    {
        if($pageId){
            $this->removePageFromAttribute($storeId, $pageId);
        }else{
            $this->removeAllPagesFromAttribute($storeId);
        }

        $pagesIdsAssociatedWithProducts = $this->getPagesIdsAssociatedWithProducts($storeId, $pageId);

        if(empty($pagesIdsAssociatedWithProducts)){
            return;
        }

        foreach($pagesIdsAssociatedWithProducts as $productId => $pagesIds){
            $this->updateAttribute($productId, $pagesIds, $storeId, $pageId);
        }
    }

    public function removePageFromAttribute($storeId, $pageId)
    {
        $products = $this->backlinkProductsRepository->getProductsWithAttribute($storeId);

        foreach($products as $product){
            $cmsPageIds = $this->serializer->unserialize(
                $product->getCmsPagesIds()
            );

            if(!in_array($pageId, $cmsPageIds)){
                continue;
            }

            if(count($cmsPageIds) > 1){
                $cmsPageIds = $this->dataHelper->removeSpecificPageIdFromIds($cmsPageIds, $pageId);
                $cmsPageIds = $this->serializer->serialize($cmsPageIds);
            }else{
                $cmsPageIds = $this->serializer->serialize([]);
            }

            $product->setCmsPagesIds($cmsPageIds);
            $product->save();
        }
    }

    public function removeAllPagesFromAttribute($storeId)
    {
        $products = $this->backlinkProductsRepository->getProductsWithAttribute($storeId);

        foreach($products as $product){
            $cmsPageIds = $this->serializer->serialize([]);

            $product->setCmsPagesIds($cmsPageIds);
            $product->save();
        }
    }

    protected function getPagesIdsAssociatedWithProducts($storeId, $pageId)
    {
        $productsIdsAssociatedWithPages = $this->productsAndPagesProvider->getProductsIdsAssociatedWithPages($storeId, $pageId);

        return $this->dataHelper->mapPagesToProducts($productsIdsAssociatedWithPages);
    }

    public function updateAttribute($productId, $pagesIds, $storeId, $pageId)
    {
        $product = $this->productRepository->getById($productId, false, $storeId);

        if($pageId and $product->getCmsPagesIds()){
            $productCmsPageIds = $this->serializer->unserialize($product->getCmsPagesIds());
            $pagesIds = array_merge($pagesIds, $productCmsPageIds);
        }

        $product->setCmsPagesIds($this->serializer->serialize($pagesIds));

        $product->save();
    }
}

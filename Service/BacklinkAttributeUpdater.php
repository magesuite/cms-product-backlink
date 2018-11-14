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

        return;
    }

    public function removePageFromAttribute($storeId, $pageId)
    {
        $products = $this->backlinkProductsRepository->getProductsWithAttribute($storeId);

        foreach($products as $product){
            $cmsPageIds = $this->serializer->unserialize(
                $product->setStoreId($storeId)->getCmsPagesIds()
            );

            if(!in_array($pageId, $cmsPageIds)){
                continue;
            }

            if(count($cmsPageIds) > 1){
                $cmsPageIds = $this->dataHelper->removeSpecificPageIdFromIds($cmsPageIds, $pageId);
            }else{
                $cmsPageIds = [];
            }

            $this->saveAttributeValueInProduct($product, $cmsPageIds);
        }
    }

    public function removeAllPagesFromAttribute($storeId)
    {
        $products = $this->backlinkProductsRepository->getProductsWithAttribute($storeId);

        foreach($products as $product){
            $product->setStoreId($storeId);
            $this->saveAttributeValueInProduct($product, []);
        }
    }

    protected function getPagesIdsAssociatedWithProducts($storeId, $pageId)
    {
        $productsIdsAssociatedWithPages = $this->productsAndPagesProvider->getProductsIdsAssociatedWithPages($storeId, $pageId);

        if(empty($productsIdsAssociatedWithPages)){
            return null;
        }

        return $this->dataHelper->mapPagesToProducts($productsIdsAssociatedWithPages);
    }

    public function updateAttribute($productId, $pagesIds, $storeId, $pageId)
    {
        $product = $this->productRepository->getById($productId, false, $storeId, true);

        if($pageId and $product->setStoreId($storeId)->getCmsPagesIds()){
            $productCmsPageIds = $this->serializer->unserialize($product->setStoreId($storeId)->getCmsPagesIds());
            $pagesIds = array_merge($pagesIds, $productCmsPageIds);
        }

        $this->saveAttributeValueInProduct($product, $pagesIds);
    }

    protected function saveAttributeValueInProduct($product, $pagesIds)
    {
        if(empty($pagesIds)){
            $product->setCmsPagesIds(null);
            $product->getResource()->saveAttribute($product, 'cms_pages_ids');

            return;
        }

        $pagesIds = array_unique($pagesIds);
        $pagesIds = array_values($pagesIds);
        $pagesIds = $this->serializer->serialize($pagesIds);

        $product->setCmsPagesIds($pagesIds);
        $product->getResource()->saveAttribute($product, 'cms_pages_ids');
    }
}

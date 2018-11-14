<?php

namespace MageSuite\CmsProductBacklink\Model;

class ProductsRepository
{
    const CMS_PRODUCT_BACKLINK_ATTRIBUTE_CODE = 'cms_pages_ids';

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $productCollectionFactory;

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    protected $productRepository;

    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository
    ) {
        $this->productCollectionFactory = $productCollectionFactory;
        $this->productRepository = $productRepository;
    }

    public function getProductsWithAttribute($storeId)
    {
        $collection = $this->productCollectionFactory->create();

        $collection
            ->addStoreFilter($storeId)
            ->addAttributeToSelect(self::CMS_PRODUCT_BACKLINK_ATTRIBUTE_CODE, 'left')
            ->addFieldToFilter(self::CMS_PRODUCT_BACKLINK_ATTRIBUTE_CODE, ['notnull' => true])
            ->addUrlRewrite();

        return $collection->getItems();
    }
}

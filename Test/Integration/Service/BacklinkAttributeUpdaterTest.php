<?php

namespace MageSuite\CmsProductBacklink\Test\Integration\DataProviders;

class BacklinkAttributeUpdaterTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    private $objectManager;

    /**
     * @var \MageSuite\CmsProductBacklink\Service\BacklinkAttributeUpdater
     */
    private $backlinkAttributeUpdater;

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */

    private $productRepository;

    public function setUp(): void
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->backlinkAttributeUpdater = $this->objectManager->get(\MageSuite\CmsProductBacklink\Service\BacklinkAttributeUpdater::class);
        $this->productRepository = $this->objectManager->get(\Magento\Catalog\Api\ProductRepositoryInterface::class);
    }

    public static function productsFixture()
    {
        include __DIR__ . '/../_files/products.php';
    }

    public static function productsFixtureRollback()
    {
        include __DIR__ . '/../_files/products_rollback.php';
    }

    /**
     * @magentoAppArea frontend
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture productsFixture
     */
    public function testItRemovesPagesFromProducts()
    {
        $storeId = 1;
        $pageId = null;
        $sku = 'product_with_one_page';

        $this->backlinkAttributeUpdater->removeAllPagesFromAttribute($storeId);

        $product = $this->productRepository->get($sku);
        $this->assertFalse($product->getExistsStoreValueFlag('cms_pages_ids'));
    }

    /**
     * @magentoAppArea frontend
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture productsFixture
     */
    public function testItRemovesSpecificPageFromProducts()
    {
        $storeId = 1;
        $pageId = 100;
        $skuWithOnePage = 'product_with_one_page';
        $skuWithTwoPages = 'product_with_two_pages';

        $this->backlinkAttributeUpdater->removePageFromAttribute($storeId, $pageId);

        $product = $this->productRepository->get($skuWithOnePage);
        $this->assertFalse($product->getExistsStoreValueFlag('cms_pages_ids'));

        $product = $this->productRepository->get($skuWithTwoPages);
        $this->assertEquals('[101]', $product->getCmsPagesIds());
    }

    /**
     * @magentoAppArea frontend
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture productsFixture
     */
    public function testItUpdatesPageInProducts()
    {
        $storeId = 1;
        $pageId = null;
        $newPageIds = [200,201];
        $sku = 'product_without_pages';
        $productId = 600;

        $this->backlinkAttributeUpdater->updateAttribute($productId, $newPageIds, $storeId, $pageId);

        $product = $this->productRepository->get($sku);
        $this->assertEquals('[200,201]', $product->getCmsPagesIds());
    }

    /**
     * @magentoAppArea frontend
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture productsFixture
     */
    public function testItUpdatesSpecificPageInProducts()
    {
        $storeId = 1;
        $pageId = 100;
        $newPageIds = [200,201];
        $skuWithOnePage = 'product_with_one_page';
        $productId = 601;

        $this->backlinkAttributeUpdater->updateAttribute($productId, $newPageIds, $storeId, $pageId);

        $product = $this->productRepository->get($skuWithOnePage);
        $this->assertEquals('[200,201,100]', $product->getCmsPagesIds());
    }
}

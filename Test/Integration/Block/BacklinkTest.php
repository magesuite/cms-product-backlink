<?php

namespace MageSuite\CmsProductBacklink\Test\Integration\Block;

class BacklinkTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    private $objectManager;

    /**
     * @var \MageSuite\CmsProductBacklink\Block\Backlink
     */
    private $backlinkBlock;

    /**
     * @var \Magento\Framework\Registry
     */
    private $coreRegistry;

    /**
     * @var \Magento\Catalog\Api\ProductRepositoryInterface
     */
    private $productRepository;

    public function setUp(): void
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->backlinkBlock = $this->objectManager->get(\MageSuite\CmsProductBacklink\Block\Backlink::class);
        $this->coreRegistry = $this->objectManager->get(\Magento\Framework\Registry::class);
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
     * @magentoConfigFixture current_store cms_product_backlink/general/is_enabled 1
     */
    public function testItReturnsCmsPagesIds()
    {
        $product = $this->productRepository->get('product_with_two_pages');
        $this->coreRegistry->register('product', $product);

        $cmsPagesIds = $this->backlinkBlock->getCmsPages();

        $this->assertEquals('100,101', $cmsPagesIds);
    }

    /**
     * @magentoAppArea frontend
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture productsFixture
     * @magentoConfigFixture current_store cms_product_backlink/general/is_enabled 0
     */
    public function testItReturnsNullIfBacklinkIsDisabled()
    {
        $product = $this->productRepository->get('product_with_two_pages');
        $this->coreRegistry->register('product', $product);

        $cmsPagesIds = $this->backlinkBlock->getCmsPages();

        $this->assertNull($cmsPagesIds);
    }
}

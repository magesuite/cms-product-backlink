<?php

namespace MageSuite\CmsProductBacklink\Test\Integration\Model;

class ProductsRepositoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    private $objectManager;

    /**
     * @var \MageSuite\CmsProductBacklink\Model\ProductsRepository
     */
    private $productsRepository;

    public function setUp()
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->productsRepository = $this->objectManager->get(\MageSuite\CmsProductBacklink\Model\ProductsRepository::class);
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
    public function testItReturnProductsWithAttribute()
    {
        $storeId = 1;

        $products = $this->productsRepository->getProductsWithAttribute($storeId);

        $this->assertCount(2, $products);

        $expectedPages = [
            'product_with_one_page' => '[100]',
            'product_with_two_pages' => '[100,101]'
        ];

        $pages = [];

        foreach($products as $product){
            $pages[$product->getSku()] = $product->getCmsPagesIds();
        }

        $this->assertEquals($expectedPages, $pages);
    }
}
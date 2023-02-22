<?php
declare(strict_types=1);

namespace MageSuite\MagePalGoogleTagManagerAdcell\Test\Integration\Model\DataLayer;

class ProductsAndPagesProviderTest extends \PHPUnit\Framework\TestCase
{
    protected ?\MageSuite\CmsProductBacklink\DataProviders\ProductsAndPagesProvider $productsAndPagesProvider;
    protected ?\Magento\Catalog\Api\ProductRepositoryInterface $productRepository;

    protected function setUp(): void
    {
        $objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
        $this->productsAndPagesProvider = $objectManager->get(\MageSuite\CmsProductBacklink\DataProviders\ProductsAndPagesProvider::class);
        $this->productRepository = $objectManager->get(\Magento\Catalog\Api\ProductRepositoryInterface::class);
    }

    /**
     * @magentoDbIsolation disabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture Magento/Catalog/_files/categories.php
     */
    public function testItReturnsProductIdsCorrectly(): void
    {
        $components = [
            [
                'type' => 'product-grid',
                'name' => 'Products grid',
                'id' => 'componentDummy',
                'section' => 'content',
                'data' => [
                    'category_id' => 4
                ]
            ]
        ];
        $simple = $this->productRepository->get('simple');
        $simple2 = $this->productRepository->get('12345');
        $productIds = $this->productsAndPagesProvider->getProductIdsFromComponents($components);

        $this->assertEquals([$simple2->getId(), $simple->getId()], $productIds);
    }
}

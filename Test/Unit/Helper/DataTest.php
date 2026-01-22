<?php

declare(strict_types=1);

namespace MageSuite\CmsProductBacklink\Test\Unit\Helper;

class DataTest extends \PHPUnit\Framework\TestCase
{
    protected ?\MageSuite\CmsProductBacklink\Helper\Data $dataHelper;

    protected function setUp(): void
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

        $this->dataHelper = $objectManager->getObject(\MageSuite\CmsProductBacklink\Helper\Data::class);
    }

    /**
     * @dataProvider idsDataProvider
     */
    public function testItRemovesSpecificPageIdFromIds(array $cmsPageIds, int $pageId, array $expected): void
    {
        $ids = $this->dataHelper->removeSpecificPageIdFromIds($cmsPageIds, $pageId);

        $this->assertEquals($expected, $ids);
    }

    public static function idsDataProvider(): array
    {
        return [
            [[1, 2, 3], 2, [1, 3]],
            [[1, 2, 3], 4, [1, 2, 3]],
            [[1], 1, []],
            [[], 2, []]
        ];
    }

    /**
     * @dataProvider productsAndPagesDataProvider
     */
    public function testItMapsPagesToProductsCorrectly(array $productsIdsAssociatedWithPages, array $expected): void
    {
        $result = $this->dataHelper->mapPagesToProducts($productsIdsAssociatedWithPages);

        $this->assertEquals($expected, $result);
    }

    public static function productsAndPagesDataProvider(): array
    {
        return [
            [
                [1 => [100, 101]],
                [100 => [1], 101 => [1]]
            ],
            [
                [1 => [100], 2 => [101]],
                [100 => [1], 101 => [2]]
            ],
            [
                [1 => [100], 2 => [100]],
                [100 => [1, 2]]
            ]
        ];
    }

    /**
     * @dataProvider identitiesDataProvider
     */
    public function testItReturnsCorrectIdsFromIdenties(array $identities, array $expected): void
    {
        $result = $this->dataHelper->getProductIdsFromIdentities($identities);

        $this->assertEquals($expected, $result);
    }

    public static function identitiesDataProvider(): array
    {
        return [
            [
                ['cat_p_1', 'cat', 'cat_p', 'test'],
                [1]
            ],
            [
                ['cat', 'random', 'something'],
                []
            ],
            [
                ['cat_p_1', 'cat_p_2', 'cat_p_2014', 'test'],
                [1, 2, 2014]
            ]
        ];
    }
}


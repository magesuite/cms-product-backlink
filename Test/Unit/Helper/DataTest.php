<?php

namespace MageSuite\CmsProductBacklink\Test\Unit\Helper;

class DataTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \MageSuite\CmsProductBacklink\Helper\Data
     */
    private $dataHelper;

    protected function setUp()
    {
        $objectManager = new \Magento\Framework\TestFramework\Unit\Helper\ObjectManager($this);

        $this->dataHelper = $objectManager->getObject(\MageSuite\CmsProductBacklink\Helper\Data::class);
    }

    /**
     * @param array $cmsPageIds
     * @param int $pageId
     * @param array $expected
     * @dataProvider idsDataProvider
     */
    public function testItRemovesSpecificPageIdFromIds($cmsPageIds, $pageId, $expected)
    {
        $ids = $this->dataHelper->removeSpecificPageIdFromIds($cmsPageIds, $pageId);

        $this->assertEquals($expected, $ids);
    }

    public function idsDataProvider()
    {
        return [
            [[1,2,3], 2, [1,3]],
            [[1,2,3], 4, [1,2,3]],
            [[1], 1, []],
            [[], 2, []]
        ];
    }

    /**
     * @param array $productsIdsAssociatedWithPages
     * @param array $expected
     * @dataProvider productsAndPagesDataProvider
     */
    public function testItMapsPagesToProductsCorrectly($productsIdsAssociatedWithPages, $expected)
    {
        $result = $this->dataHelper->mapPagesToProducts($productsIdsAssociatedWithPages);

        $this->assertEquals($expected, $result);
    }

    public function productsAndPagesDataProvider()
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
                [100 => [1,2]]
            ]
        ];
    }

    /**
     * @param array $identities
     * @param array $expected
     * @dataProvider identitiesDataProvider
     */
    public function testItReturnsCorrectIdsFromIdenties($identities, $expected)
    {
        $result = $this->dataHelper->getProductIdsFromIdentities($identities);

        $this->assertEquals($expected, $result);
    }

    public function identitiesDataProvider()
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
                [1,2,2014]
            ]
        ];
    }
}


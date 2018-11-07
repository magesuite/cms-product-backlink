<?php

namespace MageSuite\CmsProductBacklink\Test\Integration\Model;

class PagesRepositoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    private $objectManager;

    /**
     * @var \MageSuite\CmsProductBacklink\Model\PagesRepository
     */
    private $pagesRepository;

    public function setUp()
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->pagesRepository = $this->objectManager->get(\MageSuite\CmsProductBacklink\Model\PagesRepository::class);
    }

    public static function pagesFixture()
    {
        include __DIR__ . '/../_files/pages.php';
    }

    public static function pagesFixtureRollback()
    {
        include __DIR__ . '/../_files/pages_rollback.php';
    }

    /**
     * @magentoAppArea frontend
     * @magentoDbIsolation enabled
     * @magentoAppIsolation enabled
     * @magentoDataFixture pagesFixture
     */
    public function testItReturnsPages()
    {
        $this->itReturnsCmsPagesForDefaultStore();
        $this->itReturnsSpecificCmsPage();
    }


    private function itReturnsCmsPagesForDefaultStore()
    {
        $storeId = 1;

        $pages = $this->pagesRepository->getPagesByStoreId($storeId);

        $actualPages = [];

        foreach($pages as $page){
            $actualPages[$page->getId()] = $page->getIdentifier();
        }

        $this->assertContains('page_in_all_stores', $actualPages);
        $this->assertContains('page_in_default_store', $actualPages);

        $this->assertNotContains('page_without_store', $actualPages);
        $this->assertNotContains('page_in_second_store', $actualPages);
    }

    private function itReturnsSpecificCmsPage()
    {
        $pageId = 102;
        $storeId = 1;

        $pages = [$this->pagesRepository->getPageById($pageId, $storeId)];

        $this->assertEquals('page_in_default_store', $pages[0]->getIdentifier());
    }
}
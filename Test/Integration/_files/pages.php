<?php

$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
/** @var $page \Magento\Cms\Model\Page */

$store = $objectManager->create('Magento\Store\Model\Store');

if (!$store->load('second', 'code')->getId()) {
    $websiteId = $objectManager->get('Magento\Store\Model\StoreManagerInterface')->getWebsite()->getId();
    $groupId = $objectManager->get('Magento\Store\Model\StoreManagerInterface')->getWebsite()->getDefaultGroupId();

    $store->setCode('second')
        ->setWebsiteId($websiteId)
        ->setGroupId($groupId)
        ->setName('Second Store View')
        ->setSortOrder(10)
        ->setIsActive(1)
        ->save();
}

$secondStoreId = $store->load('second', 'code')->getId();

$page = $objectManager->create(\Magento\Cms\Model\Page::class);
$page->setTitle('Page without store')
    ->setId(100)
    ->setIdentifier('page_without_store')
    ->setStores([])
    ->setIsActive(1)
    ->setContent('<h1>Cms Page Design Blank Title1</h1>')
    ->setPageLayout('1column')
    ->save();

$page = $objectManager->create(\Magento\Cms\Model\Page::class);
$page->setTitle('Page in all stores')
    ->setId(101)
    ->setIdentifier('page_in_all_stores')
    ->setStores([0])
    ->setIsActive(1)
    ->setContent('<h1>Cms Page Design Blank Title2</h1>')
    ->setPageLayout('1column')
    ->save();

$page = $objectManager->create(\Magento\Cms\Model\Page::class);
$page->setTitle('Page in default store')
    ->setId(102)
    ->setIdentifier('page_in_default_store')
    ->setStores([1])
    ->setIsActive(1)
    ->setContent('<h1>Cms Page Design Blank Title3</h1>')
    ->setPageLayout('1column')
    ->save();

$page = $objectManager->create(\Magento\Cms\Model\Page::class);
$page->setTitle('Page in second store')
    ->setId(103)
    ->setIdentifier('page_in_second_store')
    ->setStores([$secondStoreId])
    ->setIsActive(1)
    ->setContent('<h1>Cms Page Design Blank Title3</h1>')
    ->setPageLayout('1column')
    ->save();

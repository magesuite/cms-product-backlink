<?php

/** @var \Magento\Framework\Registry $registry */
$objectManager = \Magento\TestFramework\Helper\Bootstrap::getObjectManager();
$registry = $objectManager->get('Magento\Framework\Registry');

$registry->unregister('isSecureArea');
$registry->register('isSecureArea', true);

/** @var $page \Magento\Cms\Model\Page */
foreach([100,101,102,103] as $pageId)  {
    $page = $objectManager->create('Magento\Cms\Model\Page');
    $page->load($pageId);

    if (!$page->getId()) {
        continue;
    }

    $page->delete();
}
/** @var $store \Magento\Store\Model\Store */
$store = $objectManager->create('Magento\Store\Model\Store');
$store->load('second');

if ($store->getId()) {
    $store->delete();
}

$registry->unregister('isSecureArea');
$registry->register('isSecureArea', false);
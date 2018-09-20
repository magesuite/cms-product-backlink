<?php
$registry = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->get('Magento\Framework\Registry');

$registry->unregister('isSecureArea');
$registry->register('isSecureArea', true);

foreach([600,601,602] as $productId) {
    $product = \Magento\TestFramework\Helper\Bootstrap::getObjectManager()->create('Magento\Catalog\Model\Product');

    $product->load($productId);
    
    if ($product->getId()) {
        $product->delete();
    }
}

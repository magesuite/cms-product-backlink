<?php

namespace MageSuite\CmsProductBacklink\Observer;

class RefreshBacklinkOnSaveEvent implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \MageSuite\CmsProductBacklink\Helper\Configuration
     */
    protected $configuration;

    /**
     * @var \MageSuite\CmsProductBacklink\Service\BacklinkAttributeUpdater
     */
    protected $backlinkAttributeUpdater;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    public function __construct(
        \MageSuite\CmsProductBacklink\Helper\Configuration $configuration,
        \MageSuite\CmsProductBacklink\Service\BacklinkAttributeUpdater $backlinkAttributeUpdater,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->configuration = $configuration;
        $this->backlinkAttributeUpdater = $backlinkAttributeUpdater;
        $this->storeManager = $storeManager;

    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if(!$this->configuration->isEnabled()){
            return $this;
        }

        if(!$this->configuration->isUpdateOnSaveEventEnabled()){
            return $this;
        }

        $page = $observer->getEvent()->getObject();

        $storeIds = $this->getStoreIds($page->getStoreId());

        foreach($storeIds as $storeId){
            $this->backlinkAttributeUpdater->execute($storeId, $page->getId());
        }

        return $this;
    }

    private function getStoreIds($storeIds)
    {
        if(in_array(0, $storeIds)){
            $stores = $this->storeManager->getStores();

            $storeIds = array_keys($stores);
            sort($storeIds);
        }

        return $storeIds;
    }
}
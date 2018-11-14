<?php
namespace MageSuite\CmsProductBacklink\Cron;

class BacklinkRefresh
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \MageSuite\CmsProductBacklink\Helper\Configuration
     */
    protected $configuration;

    /**
     * @var \MageSuite\CmsProductBacklink\Service\BacklinkAttributeUpdater
     */
    protected $backlinkAttributeUpdater;

    public function __construct(
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \MageSuite\CmsProductBacklink\Helper\Configuration $configuration,
        \MageSuite\CmsProductBacklink\Service\BacklinkAttributeUpdater $backlinkAttributeUpdater
    )
    {
        $this->storeManager = $storeManager;
        $this->configuration = $configuration;
        $this->backlinkAttributeUpdater = $backlinkAttributeUpdater;
    }

    public function execute()
    {
        $isEnabled = $this->configuration->isEnabled();

        if(!$isEnabled){
            return false;
        }

        $stores = $this->storeManager->getStores();

        $storeIds = array_keys($stores);
        sort($storeIds);

        foreach($storeIds as $storeId){
            $this->storeManager->setCurrentStore($storeId);
            $this->backlinkAttributeUpdater->execute($storeId);
        }

        return true;
    }
}
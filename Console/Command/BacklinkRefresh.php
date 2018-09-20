<?php
namespace MageSuite\CmsProductBacklink\Console\Command;

class BacklinkRefresh extends \Symfony\Component\Console\Command\Command
{
    /**
     * @var \Magento\Framework\App\State
     */
    protected $state;

    /**
     * @var \Magento\Framework\Config\ScopeInterface $scope
     */
    protected $scope;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \MageSuite\CmsProductBacklink\Service\BacklinkAttributeUpdaterFactory
     */
    protected $backlinkAttributeUpdaterFactory;

    public function __construct(
        \Magento\Framework\App\State $state,
        \Magento\Framework\Config\ScopeInterface $scope,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \MageSuite\CmsProductBacklink\Service\BacklinkAttributeUpdaterFactory $backlinkAttributeUpdaterFactory
    )
    {
        parent::__construct();

        $this->state = $state;
        $this->scope = $scope;
        $this->storeManager = $storeManager;
        $this->backlinkAttributeUpdaterFactory = $backlinkAttributeUpdaterFactory;
    }

    protected function configure()
    {
        $this->setName('cms:backlink:refresh');

        $this->addArgument(
            'store_id',
            \Symfony\Component\Console\Input\InputArgument::OPTIONAL,
            'Store Id'
        );
    }

    protected function execute(
        \Symfony\Component\Console\Input\InputInterface $input,
        \Symfony\Component\Console\Output\OutputInterface $output
    )
    {
        if ($this->scope->getCurrentScope() !== 'frontend') {
            $this->state->setAreaCode('frontend');
        }

        $specificStoreId = $input->getArgument('store_id');

        $stores = $specificStoreId !== null
            ? [$specificStoreId => $specificStoreId]
            : $this->storeManager->getStores();

        $storeIds = array_keys($stores);
        sort($storeIds);

        $backlinkAttributeUpdater = $this->backlinkAttributeUpdaterFactory->create();

        foreach($storeIds as $storeId){
            $backlinkAttributeUpdater->execute($storeId);
        }
    }
}
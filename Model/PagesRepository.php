<?php

namespace MageSuite\CmsProductBacklink\Model;

class PagesRepository
{

    /**
     * @var \Magento\Cms\Model\ResourceModel\Page\CollectionFactory
     */
    protected $pageCollectionFactory;

    /**
     * @var \Magento\Cms\Api\PageRepositoryInterface
     */
    protected $pageRepository;

    /**
     * @var \MageSuite\CmsProductBacklink\Helper\Configuration
     */
    protected $configuration;

    public function __construct(
        \Magento\Cms\Model\ResourceModel\Page\CollectionFactory $pageCollectionFactory,
        \Magento\Cms\Api\PageRepositoryInterface $pageRepository,
        \MageSuite\CmsProductBacklink\Helper\Configuration $configuration
    ) {
        $this->pageCollectionFactory = $pageCollectionFactory;
        $this->pageRepository = $pageRepository;
        $this->configuration = $configuration;
    }

    public function getPagesByStoreId($storeId)
    {
        $collection = $this->pageCollectionFactory->create();

        $collection
            ->addStoreFilter($storeId)
            ->addFieldToFilter('is_active', 1)
            ->addFieldToFilter('identifier', ['nin' => $this->configuration->getExcludedPages()]);

        return $collection->getItems();
    }

    public function getPageById($pageId)
    {
        return $this->pageRepository->getById($pageId);
    }
}

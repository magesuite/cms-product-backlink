<?php

namespace MageSuite\CmsProductBacklink\Helper;

class Configuration extends \Magento\Framework\App\Helper\AbstractHelper
{
    const CMS_PRODUCT_BACKLINK_PATH = 'cms_product_backlink/general';

    private $config;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfigInterface
    ) {
        parent::__construct($context);

        $this->scopeConfig = $scopeConfigInterface;
    }

    public function isEnabled()
    {
        $config = $this->getConfig();

        return (boolean) $config['is_enabled'];
    }

    public function isUpdateOnSaveEventEnabled()
    {
        $config = $this->getConfig();

        return (boolean) $config['update_backlink_on_page_save'];
    }

    public function getExcludedPages()
    {
        $config = $this->getConfig();

        if(empty($config['excluded_pages'])){
            return [];
        }

        return explode(',', $config['excluded_pages']);
    }

    private function getConfig()
    {
        if(!$this->config){
            $this->config = $this->scopeConfig->getValue(self::CMS_PRODUCT_BACKLINK_PATH, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        }

        return $this->config;
    }
}

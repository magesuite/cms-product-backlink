<?php

declare(strict_types=1);

namespace MageSuite\CmsProductBacklink\Helper;

class Configuration
{
    protected const CMS_PRODUCT_BACKLINK_PATH = 'cms_product_backlink/general';

    protected ?array $config = null;

    public function __construct(
        protected \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {}

    public function isEnabled(): bool
    {
        $config = $this->getConfig();

        return (bool) $config['is_enabled'];
    }

    public function isUpdateOnSaveEventEnabled(): bool
    {
        $config = $this->getConfig();

        return (bool) $config['update_backlink_on_page_save'];
    }

    public function getExcludedPages(): array
    {
        $config = $this->getConfig();

        if (empty($config['excluded_pages'])) {
            return [];
        }

        return explode(',', $config['excluded_pages']);
    }

    private function getConfig(): array
    {
        if (!$this->config) {
            $this->config = $this->scopeConfig->getValue(self::CMS_PRODUCT_BACKLINK_PATH, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        }

        return $this->config;
    }
}

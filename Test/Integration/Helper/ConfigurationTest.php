<?php

namespace MageSuite\CmsProductBacklink\Test\Integration\Helper;

class ConfigurationTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var \Magento\TestFramework\ObjectManager
     */
    private $objectManager;

    /**
     * @var \MageSuite\CmsProductBacklink\Helper\Configuration
     */
    private $configuration;

    public function setUp()
    {
        $this->objectManager = \Magento\TestFramework\ObjectManager::getInstance();

        $this->configuration = $this->objectManager->get(\MageSuite\CmsProductBacklink\Helper\Configuration::class);
    }

    public function testItReturnCorrectExcludedPages()
    {
        $expected = ['home','no-route','enable-cookies'];

        $excludedPages = $this->configuration->getExcludedPages();

        $this->assertEquals($expected, $excludedPages);
    }
}
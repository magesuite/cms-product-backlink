<?php

namespace MageSuite\CmsProductBacklink\Block;

class Backlink extends \Magento\Framework\View\Element\Template
{
    protected $_template = 'MageSuite_CmsProductBacklink::backlink.phtml';

    /**
     * @var \MageSuite\CmsProductBacklink\Helper\Configuration
     */
    protected $configuration;

    /**
     * @var \MageSuite\ContentConstructorFrontend\Service\CmsPageRenderer
     */
    protected $cmsPageRenderer;

    /**
     * @var \Magento\Framework\Registry
     */
    protected $registry;

    /**
     * @var \Magento\Framework\Serialize\SerializerInterface
     */
    protected $serializer;

    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        \MageSuite\CmsProductBacklink\Helper\Configuration $configuration,
        \MageSuite\ContentConstructorFrontend\Service\CmsPageRenderer $cmsPageRenderer,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Serialize\SerializerInterface $serializer,
        array $data = []
    ) {
        parent::__construct($context, $data);

        $this->configuration = $configuration;
        $this->cmsPageRenderer = $cmsPageRenderer;
        $this->registry = $registry;
        $this->serializer = $serializer;
    }

    public function getCmsPages()
    {
        if(!$this->configuration->isEnabled()){
            return null;
        }

        $product = $this->getProduct();

        if(!$product){
            return null;
        }

        return $this->prepareCmsPagesIds($product->getCmsPagesIds());
    }

    private function prepareCmsPagesIds($cmsPagesIds)
    {
        if(!$cmsPagesIds){
            return null;
        }

        $cmsPagesIds = $this->serializer->unserialize($cmsPagesIds);

        return implode(',', $cmsPagesIds);
    }

    public function getComponentHtml($configuration)
    {
        $componentBlock = $this->cmsPageRenderer->getComponentBlock($configuration);

        if(!$componentBlock){
            return null;
        }

        return $componentBlock->toHtml();
    }

    private function getProduct()
    {
        $product = $this->registry->registry('product');

        return $product ? $product : false;
    }
}
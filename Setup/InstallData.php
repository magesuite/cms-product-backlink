<?php

namespace MageSuite\CmsProductBacklink\Setup;

class InstallData implements \Magento\Framework\Setup\InstallDataInterface
{
    /**
     * @var \Magento\Eav\Setup\EavSetup
     */
    protected $eavSetup;

    /**
     * @var \Magento\Eav\Setup\EavSetupFactory
     */
    protected $eavSetupFactory;

    public function __construct(
        \Magento\Eav\Setup\EavSetupFactory $eavSetupFactory
    )
    {
        $this->eavSetupFactory = $eavSetupFactory;
    }

    public function install(
        \Magento\Framework\Setup\ModuleDataSetupInterface $setup,
        \Magento\Framework\Setup\ModuleContextInterface $context
    )
    {
        $setup->startSetup();

        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'cms_pages_ids',
            [
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_STORE,
                'type' => 'varchar',
                'unique' => false,
                'label' => 'Cms Pages Ids',
                'input' => 'text',
                'source' => '',
                'group' => 'General',
                'required' => false,
                'sort_order' => 10,
                'user_defined' => 1,
                'searchable' => false,
                'filterable' => false,
                'filterable_in_search' => false,
                'visible_on_front' => false,
                'visible' => false,
                'used_in_product_listing' => true,
                'note' => 'Ids of Cms Pages, where product in displayed'
            ]
        );
    }
}
<?php
/**
 * Bluethink_ProductFaq.
 *
 * @category  Bluethink
 * @package   Vendor_CustomModule
 * @author    Bluethink
 * @copyright Copyright (c) Bluethink_ProductFaq Private Limited (https://Bluethink.com)
 * @license   https://store.Bluethink.com/license.html
 */
namespace Bluethink\ProductFaq\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Catalog\Setup\CategorySetupFactory;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;

class AttributeSetAndItsGroup implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;
    /**
     * @var AttributeSetFactory
     */
    private $attributeSetFactory;

    /**
     * @var CategorySetupFactory
     */
    private $categorySetupFactory;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param AttributeSetFactory $attributeSetFactory
     * @param CategorySetupFactory $categorySetupFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        AttributeSetFactory $attributeSetFactory,
        CategorySetupFactory $categorySetupFactory
    ) {
        $this->moduleDataSetup      = $moduleDataSetup;
        $this->attributeSetFactory  = $attributeSetFactory;
        $this->categorySetupFactory = $categorySetupFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        /** @var EavSetup $eavSetup */
        try {
            $categorySetup = $this->categorySetupFactory->create(['setup' => $this->moduleDataSetup]);
            $attributeSet = $this->attributeSetFactory->create();
            $entityTypeId = $categorySetup->getEntityTypeId(\Magento\Catalog\Model\Product::ENTITY);
            $attributeSetId = $categorySetup->getDefaultAttributeSetId($entityTypeId);

            $attributeGroupName1 = 'Faq';        
            $newAttributeSetId = $categorySetup->getAttributeSetId($entityTypeId, 'default');
		    $categorySetup->addAttributeGroup(
                $entityTypeId, 
                $newAttributeSetId, 
                $attributeGroupName1, 
                200 // sort order
            );
            $attributeGroupId1 = $categorySetup->getAttributeGroupId(
                $entityTypeId,
                $newAttributeSetId,
                $attributeGroupName1
            );
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    /**
     * {@inheritdoc}
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        return [];
    }
}

<?php
/**
 * WellCommerce Open-Source E-Commerce Platform
 *
 * This file is part of the WellCommerce package.
 *
 * (c) Adam Piotrowski <adam@wellcommerce.org>
 *
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */

namespace WellCommerce\Bundle\StandardEditionBundle\DataFixtures\ORM;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use WellCommerce\Bundle\CatalogBundle\Entity\Attribute;
use WellCommerce\Bundle\CatalogBundle\Entity\AttributeGroup;
use WellCommerce\Bundle\CatalogBundle\Entity\AttributeGroupTranslation;
use WellCommerce\Bundle\CatalogBundle\Entity\AttributeTranslation;
use WellCommerce\Bundle\CatalogBundle\Entity\AttributeValue;
use WellCommerce\Bundle\CatalogBundle\Entity\AttributeValueTranslation;
use WellCommerce\Bundle\StandardEditionBundle\DataFixtures\AbstractDataFixture;

/**
 * Class LoadAttributeData
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class LoadAttributeData extends AbstractDataFixture
{
    public static $samples = [
        'Smart TVs',
        'Streaming devices',
        'Accessories',
        'DVD & Blue-ray players',
        'Audio players',
        'Projectors',
        'Home theater',
    ];
    
    public static $sizes = [
        'S',
        'M',
        'L',
        'XL',
        'XXL',
        'XXXL',
    ];
    
    public static $colours = [
        'Black',
        'Green',
        'White',
        'Orange',
        'White',
        'Gray',
        'Yellow',
    ];
    
    public function load(ObjectManager $manager)
    {
        if (!$this->isEnabled()) {
            return;
        }
        
        $groups       = new ArrayCollection();
        $attributes   = new ArrayCollection();
        $sizeValues   = new ArrayCollection();
        $colourValues = new ArrayCollection();
        
        foreach (self::$samples as $name) {
            $attributeGroup = new AttributeGroup();
            foreach ($this->getLocales() as $locale) {
                /** @var AttributeGroupTranslation $translation */
                $translation = $attributeGroup->translate($locale->getCode());
                $translation->setName($name);
            }
            $attributeGroup->mergeNewTranslations();
            $manager->persist($attributeGroup);
            $this->setReference('attribute_group_' . $name, $attributeGroup);
            $groups->add($attributeGroup);
        }
        
        $sizes   = ['S', 'M', 'L', 'XL', 'XXL', 'XXXL'];
        $colours = ['Black', 'Green', 'White', 'Orange', 'White', 'Gray', 'Yellow'];
        
        foreach ($sizes as $size) {
            $value = new AttributeValue();
            foreach ($this->getLocales() as $locale) {
                /** @var AttributeValueTranslation $translation */
                $translation = $value->translate($locale->getCode());
                $translation->setName($size);
            }
            $value->mergeNewTranslations();
            $manager->persist($value);
            $sizeValues->add($value);
            $this->setReference('attribute_value_' . $size, $value);
        }
        
        foreach ($colours as $colour) {
            $value = new AttributeValue();
            foreach ($this->getLocales() as $locale) {
                /** @var AttributeValueTranslation $translation */
                $translation = $value->translate($locale->getCode());
                $translation->setName($colour);
            }
            $value->mergeNewTranslations();
            $manager->persist($value);
            $colourValues->add($value);
            $this->setReference('attribute_value_' . $colour, $value);
        }
        
        
        $attribute = new Attribute();
        foreach ($this->getLocales() as $locale) {
            /** @var AttributeTranslation $translation */
            $translation = $attribute->translate($locale->getCode());
            $translation->setName('Size');
        }
        $attribute->mergeNewTranslations();
        $attribute->setGroups($groups);
        $attribute->setValues($sizeValues);
        $manager->persist($attribute);
        $attributes->add($attribute);
        $this->setReference('attribute_size', $attribute);
        
        $attribute = new Attribute();
        foreach ($this->getLocales() as $locale) {
            /** @var AttributeTranslation $translation */
            $translation = $attribute->translate($locale->getCode());
            $translation->setName('Colour');
        }
        $attribute->mergeNewTranslations();
        $attribute->setGroups($groups);
        $attribute->setValues($colourValues);
        $manager->persist($attribute);
        $attributes->add($attribute);
        $this->setReference('attribute_colour', $attribute);
        
        $manager->flush();
    }
}

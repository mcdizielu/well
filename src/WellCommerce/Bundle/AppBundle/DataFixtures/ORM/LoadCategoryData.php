<?php
/*
 * WellCommerce Open-Source E-Commerce Platform
 *
 * This file is part of the WellCommerce package.
 *
 * (c) Adam Piotrowski <adam@wellcommerce.org>
 *
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */

namespace WellCommerce\Bundle\AppBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use WellCommerce\Bundle\AppBundle\Entity\Shop;
use WellCommerce\Bundle\CatalogBundle\Entity\Category;
use WellCommerce\Bundle\CatalogBundle\Entity\CategoryTranslation;
use WellCommerce\Bundle\CoreBundle\Helper\Helper;
use WellCommerce\Bundle\DoctrineBundle\DataFixtures\AbstractDataFixture;

/**
 * Class LoadCategoryData
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class LoadCategoryData extends AbstractDataFixture
{
    public static $samples
        = [
            'Smart TVs',
            'Streaming devices',
            'Accessories',
            'DVD & Blue-ray players',
            'Audio players',
            'Projectors',
            'Home theater',
        ];
    
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        if (!$this->isEnabled()) {
            return;
        }
        
        $this->addCategories($manager);
        
        $this->createLayoutBoxes($manager, [
            'category_menu'     => [
                'type' => 'CategoryMenu',
                'name' => 'Categories',
            ],
            'category_info'     => [
                'type' => 'CategoryInfo',
                'name' => 'Category',
            ],
            'category_products' => [
                'type'     => 'CategoryProducts',
                'name'     => 'Category products',
                'settings' => [
                    'per_page' => 10,
                ],
            ],
        ]);
        
        $manager->flush();
    }
    
    private function addCategories(ObjectManager $manager)
    {
        /** @var Shop $shop */
        $shop      = $this->getReference('shop');
        $hierarchy = 0;
        
        foreach (self::$samples as $name) {
            $category = new Category();
            $category->setHierarchy($hierarchy++);
            $category->addShop($shop);
            foreach ($this->getLocales() as $locale) {
                /** @var CategoryTranslation $translation */
                $translation = $category->translate($locale->getCode());
                $translation->setName($name);
                $translation->setSlug(Helper::urlize($name));
            }
            $category->mergeNewTranslations();
            $manager->persist($category);
            $this->setReference('category_' . $name, $category);
        }
    }
}

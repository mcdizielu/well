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

namespace WellCommerce\Bundle\CatalogBundle\Manager;

use WellCommerce\Bundle\AppBundle\Entity\Locale;
use WellCommerce\Bundle\AppBundle\Entity\Shop;
use WellCommerce\Bundle\CatalogBundle\Entity\Category;
use WellCommerce\Bundle\CoreBundle\Helper\Helper;
use WellCommerce\Bundle\CoreBundle\Manager\AbstractManager;

/**
 * Class CategoryManager
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class CategoryManager extends AbstractManager
{
    /**
     * Sorts categories passed in request
     *
     * @param array $items
     */
    public function sortCategories(array $items)
    {
        $repository = $this->getRepository();
        $em         = $this->getDoctrineHelper()->getEntityManager();
        
        foreach ($items as $item) {
            $parent = $repository->find($item['parent']);
            $child  = $repository->find($item['id']);
            if (null !== $child) {
                $child->setParent($parent);
                $child->setHierarchy($item['weight']);
                $em->persist($child);
            }
        }
        
        $em->flush();
    }
    
    public function quickAddCategory(string $name, int $parent, Shop $shop): Category
    {
        $parentCategory = $this->getRepository()->find($parent);
        
        /** @var Category $category */
        $category = $this->initResource();
        $category->setParent($parentCategory);
        $category->addShop($shop);
        
        foreach ($this->getLocales() as $locale) {
            $this->translateCategory($locale, $category, $name);
        }
        $em = $this->getDoctrineHelper()->getEntityManager();
        $em->persist($category);
        $em->flush();
        
        return $category;
    }
    
    protected function translateCategory(Locale $locale, Category $category, $name)
    {
        /**
         * @var $translation \WellCommerce\Bundle\CatalogBundle\Entity\CategoryTranslation
         */
        $translation = $category->translate($locale->getCode());
        $slug        = $this->getLocaleSlug($locale, $name);
        $translation->setName($name);
        $translation->setSlug($slug);
        $category->mergeNewTranslations();
    }
    
    protected function getLocaleSlug(Locale $locale, $name)
    {
        $slug          = Helper::urlize($name);
        $currentLocale = $this->getRequestHelper()->getCurrentLocale();
        if ($locale->getCode() != $currentLocale) {
            $slug = sprintf('%s/%s', $locale->getCode(), $slug);
        }
        
        return $slug;
    }
}

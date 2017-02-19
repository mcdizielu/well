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
use WellCommerce\Bundle\CmsBundle\Entity\Page;
use WellCommerce\Bundle\CmsBundle\Entity\PageTranslation;
use WellCommerce\Bundle\CoreBundle\Helper\Helper;
use WellCommerce\Bundle\DoctrineBundle\DataFixtures\AbstractDataFixture;

/**
 * Class LoadPageData
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class LoadPageData extends AbstractDataFixture
{
    protected $shop;
    
    protected $defaultText;
    
    /**
     * @var ObjectManager
     */
    protected $manager;
    
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        if (!$this->isEnabled()) {
            return;
        }
        
        $this->manager     = $manager;
        $this->shop        = $this->getReference('shop');
        $this->defaultText = $this->getFakerGenerator()->text(600);
        
        $aboutUs = $this->createPage('About us', 0, null);
        $this->setReference('page_about_us', $aboutUs);
        $this->createPage('News feed', 10, $aboutUs);
        $this->createPage('Stores', 20, $aboutUs);
        $this->createPage('Brands', 30, $aboutUs);
        $this->createPage('Our brand', 40, $aboutUs);
        $this->createPage('About company', 50, $aboutUs);
        $this->createPage('Wholesale', 60, $aboutUs);
        
        $help = $this->createPage('Help', 10, null);
        $this->setReference('page_help', $aboutUs);
        $this->createPage('Conditions', 10, $help);
        $this->createPage('Returns, warranty', 20, $help);
        $this->createPage('Shipping', 30, $help);
        $this->createPage('Availability & delivery times', 40, $help);
        $this->createPage('Payment', 50, $help);
        $this->createPage('Site map', 60, $help);
        
        $this->createLayoutBoxes($manager, [
            'page' => [
                'type' => 'Page',
                'name' => 'Page',
            ],
        ]);
        
        $manager->flush();
    }
    
    /**
     * Creates a cms page
     *
     * @param string $name
     * @param int    $hierarchy
     * @param Page   $parent
     *
     * @return Page
     */
    protected function createPage($name, $hierarchy, Page $parent = null): Page
    {
        $page = new Page();
        $page->setParent($parent);
        $page->setHierarchy($hierarchy);
        $page->setPublish(1);
        $page->setRedirectType(0);
        $page->setSection('');
        $page->addShop($this->shop);
        foreach ($this->getLocales() as $locale) {
            /** @var PageTranslation $translation */
            $translation = $page->translate($locale->getCode());
            $translation->setName($name);
            $translation->setSlug(Helper::urlize($name));
            $translation->setContent($this->defaultText);
        }
        
        $page->mergeNewTranslations();
        
        $this->manager->persist($page);
        
        return $page;
    }
}

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
use WellCommerce\Bundle\CatalogBundle\Entity\Producer;
use WellCommerce\Bundle\CatalogBundle\Entity\ProducerTranslation;
use WellCommerce\Bundle\CoreBundle\Helper\Helper;
use WellCommerce\Bundle\DoctrineBundle\DataFixtures\AbstractDataFixture;

/**
 * Class LoadProducerData
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class LoadProducerData extends AbstractDataFixture
{
    public static $samples = ['LG', 'Samsung', 'Sony', 'Panasonic', 'Toshiba'];
    
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        if (!$this->isEnabled()) {
            return;
        }
        
        $this->addProducers($manager);
        
        $this->createLayoutBoxes($manager, [
            'producer_menu'     => [
                'type' => 'ProducerMenu',
                'name' => 'Producers',
            ],
            'producer_products' => [
                'type' => 'ProducerProducts',
                'name' => 'Producer products',
            ],
        ]);
        
        $manager->flush();
    }
    
    private function addProducers(ObjectManager $manager)
    {
        $shop = $this->getReference('shop');
        
        foreach (self::$samples as $name) {
            $producer = new Producer();
            $producer->addShop($shop);
            foreach ($this->getLocales() as $locale) {
                /** @var ProducerTranslation $translation */
                $translation = $producer->translate($locale->getCode());
                $translation->setName($name);
                $translation->setSlug(Helper::urlize($name));
            }
            
            $producer->mergeNewTranslations();
            $manager->persist($producer);
            $this->setReference('producer_' . $name, $producer);
        }
    }
}

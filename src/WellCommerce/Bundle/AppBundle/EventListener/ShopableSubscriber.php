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

namespace WellCommerce\Bundle\AppBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use WellCommerce\Bundle\AppBundle\Entity\Shop;

/**
 * Class ShopableSubscriber
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ShopableSubscriber implements EventSubscriber
{
    /**
     * @var \Doctrine\ORM\Mapping\ClassMetadataInfo
     */
    protected $classMetadata;
    
    public function getSubscribedEvents()
    {
        return [Events::loadClassMetadata];
    }
    
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $this->classMetadata = $eventArgs->getClassMetadata();
        $reflectionClass     = $this->classMetadata->getReflectionClass();
        
        if (null === $reflectionClass) {
            return;
        }
        
        if ($reflectionClass->hasMethod('setShops') && $reflectionClass->hasMethod('getShops')) {
            $this->mapShopAssociation();
        }
        
        if ($reflectionClass->hasMethod('setShop') && $reflectionClass->hasMethod('getShop')) {
            $this->mapShopField();
        }
    }
    
    protected function mapShopAssociation()
    {
        if (!$this->classMetadata->hasAssociation('shops')) {
            $this->classMetadata->mapManyToMany([
                'fieldName'          => 'shops',
                'fetch'              => ClassMetadataInfo::FETCH_LAZY,
                'joinColumns'        => [
                    [
                        'name'                 => 'foreign_id',
                        'referencedColumnName' => 'id',
                        'onDelete'             => 'CASCADE',
                        'nullable'             => false,
                    ],
                ],
                'inverseJoinColumns' => [
                    [
                        'name'                 => 'shop_id',
                        'referencedColumnName' => 'id',
                        'onDelete'             => 'CASCADE',
                        'nullable'             => false,
                    ],
                ],
                'targetEntity'       => Shop::class,
            ]);
        }
    }
    
    protected function mapShopField()
    {
        if (!$this->classMetadata->hasAssociation('shop')) {
            $this->classMetadata->mapManyToOne([
                'fieldName'    => 'shop',
                'fetch'        => ClassMetadataInfo::FETCH_LAZY,
                'joinColumns'  => [
                    [
                        'name'                 => 'shop_id',
                        'referencedColumnName' => 'id',
                        'onDelete'             => 'CASCADE',
                    ],
                ],
                'targetEntity' => Shop::class,
            ]);
        }
    }
}

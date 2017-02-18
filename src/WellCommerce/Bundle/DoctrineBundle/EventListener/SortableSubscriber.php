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

namespace WellCommerce\Bundle\DoctrineBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;

/**
 * Class SortableSubscriber
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class SortableSubscriber implements EventSubscriber
{
    /**
     * Returns subscribed events
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [Events::loadClassMetadata];
    }
    
    /**
     * Event triggered during metadata loading
     *
     * @param LoadClassMetadataEventArgs $eventArgs
     */
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        /** @var \Doctrine\ORM\Mapping\ClassMetadataInfo $classMetadata */
        $classMetadata   = $eventArgs->getClassMetadata();
        $reflectionClass = $classMetadata->getReflectionClass();
        
        if ($reflectionClass->hasMethod('setHierarchy') && !$classMetadata->hasField('hierarchy')) {
            $classMetadata->mapField([
                'fieldName' => 'hierarchy',
                'type'      => 'integer',
                'nullable'  => false,
                'options'   => [
                    'default' => 0,
                ],
            ]);
        }
    }
}

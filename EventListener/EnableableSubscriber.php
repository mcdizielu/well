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
 * Class EnableableSubscriber
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class EnableableSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return [Events::loadClassMetadata];
    }
    
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        /** @var \Doctrine\ORM\Mapping\ClassMetadataInfo $classMetadata */
        $classMetadata   = $eventArgs->getClassMetadata();
        $reflectionClass = $classMetadata->getReflectionClass();
        
        if ($reflectionClass->hasMethod('setEnabled') && !$classMetadata->hasField('enabled')) {
            $classMetadata->mapField([
                'fieldName' => 'enabled',
                'type'      => 'boolean',
                'nullable'  => false,
                'options'   => [
                    'default' => true,
                ],
            ]);
        }
    }
}

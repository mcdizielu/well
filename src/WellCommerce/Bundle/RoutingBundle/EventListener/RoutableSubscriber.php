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

namespace WellCommerce\Bundle\RoutingBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use WellCommerce\Bundle\RoutingBundle\Entity\RoutableSubjectInterface;
use WellCommerce\Bundle\RoutingBundle\Entity\RoutingDiscriminatorsAwareInterface;

/**
 * Class RoutableSubscriber
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class RoutableSubscriber implements EventSubscriber
{
    /**
     * @var array
     */
    private $routingDiscriminatorMap;
    
    public function __construct(array $routingDiscriminatorMap = [])
    {
        $this->routingDiscriminatorMap = $routingDiscriminatorMap;
    }
    
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $metadata = $eventArgs->getClassMetaData();
        /** @var $reflectionClass \ReflectionClass */
        $reflectionClass = $metadata->getReflectionClass();
        if ($reflectionClass->implementsInterface(RoutingDiscriminatorsAwareInterface::class)) {
            $metadata->setDiscriminatorMap($this->routingDiscriminatorMap);
        }
    }
    
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();
        if ($entity instanceof RoutableSubjectInterface) {
            $route = $this->addRoute($entity);
            $entity->setRoute($route);
        }
    }
    
    protected function addRoute(RoutableSubjectInterface $entity)
    {
        $route = $entity->getRouteEntity();
        $route->setPath($entity->getSlug());
        $route->setLocale($entity->getLocale());
        $route->setIdentifier($entity->getTranslatable());
        
        return $route;
    }
    
    public function getSubscribedEvents()
    {
        return [
            Events::prePersist,
            Events::loadClassMetadata,
        ];
    }
}

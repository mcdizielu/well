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

namespace WellCommerce\Bundle\OrderBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Id\UuidGenerator;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use WellCommerce\Bundle\OrderBundle\Entity\Payment;

/**
 * Class PaymentDoctrineEventSubscriber
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class PaymentDoctrineEventSubscriber implements EventSubscriber
{
    use ContainerAwareTrait;
    
    public function getSubscribedEvents()
    {
        return [
            'prePersist',
        ];
    }
    
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if ($entity instanceof Payment) {
            $generator = new UuidGenerator();
            $token     = $generator->generate($args->getObjectManager(), null);
            $entity->setToken($token);
        }
    }
}

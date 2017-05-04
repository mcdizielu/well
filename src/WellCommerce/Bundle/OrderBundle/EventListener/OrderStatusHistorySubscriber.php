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

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use WellCommerce\Bundle\CoreBundle\Doctrine\Event\EntityEvent;
use WellCommerce\Bundle\CoreBundle\Helper\Mailer\MailerHelper;
use WellCommerce\Bundle\CoreBundle\Helper\Translator\TranslatorHelperInterface;
use WellCommerce\Bundle\OrderBundle\Entity\OrderStatusHistory;

/**
 * Class OrderStatusHistorySubscriber
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class OrderStatusHistorySubscriber implements EventSubscriberInterface
{
    /**
     * @var MailerHelper
     */
    private $mailerHelper;
    
    /**
     * @var TranslatorHelperInterface
     */
    private $translatorHelper;
    
    public function __construct(MailerHelper $mailerHelper, TranslatorHelperInterface $translatorHelper)
    {
        $this->mailerHelper     = $mailerHelper;
        $this->translatorHelper = $translatorHelper;
    }
    
    public static function getSubscribedEvents()
    {
        return [
            'order_status_history.pre_create' => ['onOrderStatusHistoryPreCreate', 0],
        ];
    }
    
    public function onOrderStatusHistoryPreCreate(EntityEvent $event)
    {
        $history = $event->getEntity();
        if ($history instanceof OrderStatusHistory) {
            $order = $history->getOrder();
            if ($history->isNotify()) {
                $this->mailerHelper->sendEmail([
                    'recipient'     => $order->getContactDetails()->getEmail(),
                    'subject'       => $this->translatorHelper->trans('order_status_history.email.status_changed'),
                    'template'      => 'WellCommerceOrderBundle:Email:order_status_change.html.twig',
                    'parameters'    => [
                        'history' => $history,
                        'order'   => $order,
                    ],
                    'configuration' => $order->getShop()->getMailerConfiguration(),
                ]);
            }
            
            $order->setCurrentStatus($history->getOrderStatus());
        }
    }
}

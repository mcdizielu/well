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

namespace WellCommerce\Bundle\ReviewBundle\EventListener;

use WellCommerce\Bundle\CatalogBundle\Entity\Product;
use WellCommerce\Bundle\CoreBundle\EventListener\AbstractEventSubscriber;
use WellCommerce\Component\Form\Event\FormEvent;

/**
 * Class ReviewSubscriber
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ReviewSubscriber extends AbstractEventSubscriber
{
    public static function getSubscribedEvents()
    {
        return [
            'admin.product.pre_form_init' => ['onProductFormAdminInit'],
        ];
    }

    public function onProductFormAdminInit(FormEvent $event)
    {
        $resource = $event->getResource();
        if ($resource instanceof Product) {
            $form    = $event->getForm();
            $builder = $event->getFormBuilder();

            $mainData = $form->getChildren()->get('main_data');

            $mainData->addChild($builder->getElement('checkbox', [
                'name'    => 'enableReviews',
                'label'   => 'review.label.enable_reviews',
                'default' => true,
            ]));
        }
    }
}

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

namespace WellCommerce\Bundle\OAuthBundle\EventListener;

use WellCommerce\Bundle\AppBundle\Entity\Shop;
use WellCommerce\Bundle\CoreBundle\EventListener\AbstractEventSubscriber;
use WellCommerce\Component\Form\Event\FormEvent;

/**
 * Class OAuthSubscriber
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class OAuthSubscriber extends AbstractEventSubscriber
{
    public static function getSubscribedEvents()
    {
        return [
            'admin.shop.pre_form_init' => ['onShopFormAdminInit'],
        ];
    }
    
    public function onShopFormAdminInit(FormEvent $event)
    {
        $resource = $event->getResource();
        if ($resource instanceof Shop) {
            $form    = $event->getForm();
            $builder = $event->getFormBuilder();
            
            $authData = $form->addChild($builder->getElement('nested_fieldset', [
                'name'  => 'auth_data',
                'label' => 'oauth.fieldset.settings',
            ]));

            $facebookData = $authData->addChild($builder->getElement('nested_fieldset', [
                'name'  => 'facebook_data',
                'label' => 'oauth.facebook.fieldset.settings',
            ]));

            $facebookData->addChild($builder->getElement('checkbox', [
                'name'  => 'facebookConnectEnabled',
                'label' => 'oauth.facebook.label.enabled',
            ]));

            $facebookData->addChild($builder->getElement('text_field', [
                'name'  => 'facebookConnectAppId',
                'label' => 'oauth.facebook.label.app_id',
            ]));

            $facebookData->addChild($builder->getElement('text_field', [
                'name'  => 'facebookConnectAppSecret',
                'label' => 'oauth.facebook.label.app_secret',
            ]));

            $googleData = $authData->addChild($builder->getElement('nested_fieldset', [
                'name'  => 'google_data',
                'label' => 'oauth.google.fieldset.settings',
            ]));

            $googleData->addChild($builder->getElement('checkbox', [
                'name'  => 'googleConnectEnabled',
                'label' => 'oauth.google.label.enabled',
            ]));

            $googleData->addChild($builder->getElement('text_field', [
                'name'  => 'googleConnectAppId',
                'label' => 'oauth.google.label.app_id',
            ]));

            $googleData->addChild($builder->getElement('text_field', [
                'name'  => 'googleConnectAppSecret',
                'label' => 'oauth.google.label.app_secret',
            ]));
        }
    }
}

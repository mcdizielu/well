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

namespace WellCommerce\Bundle\InvoiceBundle\EventListener;

use WellCommerce\Bundle\AppBundle\Entity\Shop;
use WellCommerce\Bundle\CoreBundle\EventListener\AbstractEventSubscriber;
use WellCommerce\Bundle\InvoiceBundle\Processor\InvoiceProcessorInterface;
use WellCommerce\Component\Form\Event\FormEvent;

/**
 * Class InvoiceSubscriber
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class InvoiceSubscriber extends AbstractEventSubscriber
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
            
            $invoiceData = $form->addChild($builder->getElement('nested_fieldset', [
                'name'  => 'invoice_data',
                'label' => 'invoice.fieldset.settings',
            ]));
            
            $invoiceData->addChild($builder->getElement('text_field', [
                'name'    => 'invoiceMaturity',
                'label'   => 'invoice.label.maturity',
                'default' => 7,
            ]));
            
            $options       = [];
            $processors    = $this->get('invoice.processor.collection');
            $processorKeys = $processors->keys();
            
            /** @var InvoiceProcessorInterface $processor */
            foreach ($processors->all() as $processor) {
                $processorName           = $processor->getAlias();
                $options[$processorName] = $processorName;
            }
            
            $defaultProcessor = reset($processorKeys);
            
            $invoiceData->addChild($builder->getElement('select', [
                'name'    => 'invoiceProcessor',
                'label'   => 'invoice.label.processor',
                'default' => $defaultProcessor,
                'options' => $options,
            ]));
        }
    }
}

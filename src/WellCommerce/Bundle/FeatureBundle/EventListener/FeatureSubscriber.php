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
namespace WellCommerce\Bundle\FeatureBundle\EventListener;

use WellCommerce\Bundle\CatalogBundle\Entity\Product;
use WellCommerce\Bundle\CoreBundle\EventListener\AbstractEventSubscriber;
use WellCommerce\Component\Form\Event\FormEvent;

/**
 * Class FeatureSubscriber
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class FeatureSubscriber extends AbstractEventSubscriber
{
    public static function getSubscribedEvents()
    {
        return [
            'admin.product.pre_form_init' => ['onProductFormAdminInit'],
        ];
    }
    
    public function onProductFormAdminInit(FormEvent $event)
    {
        $form     = $event->getForm();
        $builder  = $event->getFormBuilder();
        $resource = $event->getResource();
        
        if ($resource instanceof Product) {
            $featuresData = $form->addChild($builder->getElement('nested_fieldset', [
                'name'  => 'features_data',
                'label' => 'product.fieldset.features',
            ]));
            
            $featureSet = $featuresData->addChild($builder->getElement('hidden', [
                'name'        => 'featureSet',
                'label'       => 'product.label.feature_set',
                'transformer' => $builder->getRepositoryTransformer('entity', $this->get('feature_set.repository')),
            ]));
            
            $featuresData->addChild($builder->getElement('feature_editor', [
                'name'              => 'features',
                'label'             => 'product.label.features',
                'product_id'        => $this->getRequestHelper()->getAttributesBagParam('id'),
                'feature_set_field' => $featureSet,
                'transformer'       => $builder->getRepositoryTransformer('feature_collection', $this->get('product_feature.repository')),
            ]));
        }
        
        $form->registerAdditionalJavascript('bundles/wellcommercefeature/js/feature_editor.js');
    }
}

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

namespace WellCommerce\Bundle\CoreBundle\Layout\Configurator;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use WellCommerce\Bundle\CoreBundle\DependencyInjection\AbstractContainerAware;
use WellCommerce\Component\Form\Conditions\Equals;
use WellCommerce\Component\Form\Elements\Fieldset\FieldsetInterface;
use WellCommerce\Component\Form\Elements\FormInterface;
use WellCommerce\Component\Form\Elements\Optioned\Select;
use WellCommerce\Component\Form\FormBuilderInterface;
use WellCommerce\Component\Layout\Configurator\LayoutBoxConfiguratorInterface;
use WellCommerce\Component\Layout\Controller\BoxControllerInterface;

/**
 * Class AbstractLayoutBoxConfigurator
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
abstract class AbstractLayoutBoxConfigurator extends AbstractContainerAware implements LayoutBoxConfiguratorInterface
{
    /**
     * @var BoxControllerInterface
     */
    protected $controller;
    
    public function addFormFields(FormBuilderInterface $builder, FormInterface $form, $defaults)
    {
        $fieldset = $this->getFieldset($builder, $form);
        
        $fieldset->addChild($builder->getElement('tip', [
            'tip' => $this->trans('layout_box.configuration'),
        ]));
        
        return $fieldset;
    }
    
    protected function getFieldset(FormBuilderInterface $builder, FormInterface $form): FieldsetInterface
    {
        $type          = $this->getType();
        $boxTypeSelect = $this->getBoxTypeSelect($form);
        $boxTypeSelect->addOptionToSelect($type, $type);
        
        $fieldset = $form->addChild($builder->getElement('nested_fieldset', [
            'name'         => $type,
            'label'        => $this->trans('layout_box.label.settings'),
            'dependencies' => [
                $builder->getDependency('show', [
                    'field'     => $boxTypeSelect,
                    'condition' => new Equals($type),
                    'form'      => $form,
                ]),
            ],
        ]));
        
        return $fieldset;
    }
    
    protected function getPropertyAccessor(): PropertyAccessor
    {
        return PropertyAccess::createPropertyAccessor();
    }
    
    protected function getBoxTypeSelect(FormInterface $form): Select
    {
        return $form->getChildren()->get('required_data')->getChildren()->get('boxType');
    }
    
    public function getController(): BoxControllerInterface
    {
        return $this->controller;
    }
}

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

namespace WellCommerce\Bundle\CatalogBundle\Configurator;

use WellCommerce\Bundle\CatalogBundle\Controller\Box\CategoryMenuBoxController;
use WellCommerce\Bundle\CatalogBundle\DataSet\Admin\CategoryDataSet;
use WellCommerce\Bundle\CoreBundle\Layout\Configurator\AbstractLayoutBoxConfigurator;
use WellCommerce\Component\Form\Elements\FormInterface;
use WellCommerce\Component\Form\FormBuilderInterface;

/**
 * Class CategoryMenuBoxConfigurator
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class CategoryMenuBoxConfigurator extends AbstractLayoutBoxConfigurator
{
    /**
     * @var CategoryDataSet
     */
    private $dataSet;
    
    public function __construct(CategoryMenuBoxController $controller, CategoryDataSet $dataSet)
    {
        $this->controller = $controller;
        $this->dataSet    = $dataSet;
    }
    
    public function getType(): string
    {
        return 'CategoryMenu';
    }
    
    public function addFormFields(FormBuilderInterface $builder, FormInterface $form, $defaults)
    {
        $fieldset = $this->getFieldset($builder, $form);
        $accessor = $this->getPropertyAccessor();
        
        $fieldset->addChild($builder->getElement('tip', [
            'tip' => 'Choose categories which should be not visible in box.',
        ]));
        
        $exclude = $fieldset->addChild($builder->getElement('tree', [
            'name'       => 'exclude',
            'label'      => 'category.label.exclude',
            'choosable'  => false,
            'selectable' => true,
            'sortable'   => false,
            'clickable'  => false,
            'items'      => $this->dataSet->getResult('flat_tree'),
        ]));
        
        $exclude->setValue($accessor->getValue($defaults, '[exclude]'));
    }
}

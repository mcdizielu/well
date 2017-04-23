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

namespace WellCommerce\Bundle\ShowcaseBundle\Service\Layout\Configurator;

use WellCommerce\Bundle\CatalogBundle\DataSet\Admin\CategoryDataSet;
use WellCommerce\Bundle\CatalogBundle\DataSet\Admin\ProductStatusDataSet;
use WellCommerce\Bundle\CoreBundle\Layout\Configurator\AbstractLayoutBoxConfigurator;
use WellCommerce\Bundle\ShowcaseBundle\Controller\Box\ShowcaseBoxController;
use WellCommerce\Component\DataSet\Conditions\Condition\Eq;
use WellCommerce\Component\DataSet\Conditions\ConditionsCollection;
use WellCommerce\Component\Form\Elements\FormInterface;
use WellCommerce\Component\Form\FormBuilderInterface;

/**
 * Class ShowcaseBoxConfigurator
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class ShowcaseBoxConfigurator extends AbstractLayoutBoxConfigurator
{
    /**
     * @var ProductStatusDataSet
     */
    private $dataSet;
    
    /**
     * @var CategoryDataSet
     */
    private $categoryDataSet;
    
    public function __construct(ShowcaseBoxController $controller, ProductStatusDataSet $dataSet, CategoryDataSet $categoryDataSet)
    {
        $this->controller      = $controller;
        $this->dataSet         = $dataSet;
        $this->categoryDataSet = $categoryDataSet;
    }
    
    public function getType(): string
    {
        return 'Showcase';
    }
    
    public function addFormFields(FormBuilderInterface $builder, FormInterface $form, $defaults)
    {
        $fieldset = $this->getFieldset($builder, $form);
        
        $fieldset->addChild($builder->getElement('tip', [
            'tip' => 'layout_box.showcase.tip',
        ]));
        
        $fieldset->addChild($builder->getElement('select', [
            'name'    => 'status',
            'label'   => 'layout_box.showcase.status',
            'options' => $this->dataSet->getResult('select'),
        ]))->setValue($this->getValue($defaults, '[status]'));
        
        $fieldset->addChild($builder->getElement('text_field', [
            'name'  => 'limit',
            'label' => 'layout_box.showcase.limit',
        ]))->setValue($this->getValue($defaults, '[limit]', 10));
        
        $fieldset->addChild($builder->getElement('tree', [
            'name'       => 'categories',
            'label'      => 'common.label.categories',
            'choosable'  => false,
            'selectable' => true,
            'sortable'   => false,
            'clickable'  => false,
            'items'      => $this->getRootCategories(),
            'restrict'   => 0,
        ]))->setValue($this->getValue($defaults, '[categories]', []));
    }
    
    private function getRootCategories(): array
    {
        $conditions = new ConditionsCollection();
        $conditions->add(new Eq('parent', null));
        
        return $this->categoryDataSet->getResult('flat_tree', [
            'conditions' => $conditions,
        ]);
    }
}

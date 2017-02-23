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

use WellCommerce\Bundle\CatalogBundle\Controller\Box\ProductStatusBoxController;
use WellCommerce\Bundle\CatalogBundle\DataSet\Admin\ProductStatusDataSet;
use WellCommerce\Bundle\CoreBundle\Layout\Configurator\AbstractLayoutBoxConfigurator;
use WellCommerce\Component\Form\Elements\FormInterface;
use WellCommerce\Component\Form\FormBuilderInterface;

/**
 * Class ProductStatusBoxConfigurator
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class ProductStatusBoxConfigurator extends AbstractLayoutBoxConfigurator
{
    protected $dataSet;
    
    public function __construct(ProductStatusBoxController $controller, ProductStatusDataSet $dataSet)
    {
        $this->controller = $controller;
        $this->dataSet    = $dataSet;
    }
    
    public function getType(): string
    {
        return 'ProductStatus';
    }
    
    public function addFormFields(FormBuilderInterface $builder, FormInterface $form, $defaults)
    {
        $fieldset = $this->getFieldset($builder, $form);
        
        $fieldset->addChild($builder->getElement('tip', [
            'tip' => 'layout_box.product_status.tip',
        ]));
        
        $statuses   = $this->dataSet->getResult('select');
        $statusKeys = array_keys($statuses);
        
        $fieldset->addChild($builder->getElement('select', [
            'name'    => 'status',
            'label'   => 'product.label.statuses',
            'options' => $statuses,
        ]))->setValue(current($statusKeys));
    }
}

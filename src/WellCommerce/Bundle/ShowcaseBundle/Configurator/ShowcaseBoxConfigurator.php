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

namespace WellCommerce\Bundle\ShowcaseBundle\Configurator;

use WellCommerce\Bundle\CatalogBundle\DataSet\Admin\ProductStatusDataSet;
use WellCommerce\Bundle\CoreBundle\Layout\Configurator\AbstractLayoutBoxConfigurator;
use WellCommerce\Bundle\ShowcaseBundle\Controller\Box\ShowcaseBoxController;
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
    
    public function __construct(ShowcaseBoxController $controller, ProductStatusDataSet $dataSet)
    {
        $this->controller = $controller;
        $this->dataSet    = $dataSet;
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
        
        $status = $fieldset->addChild($builder->getElement('select', [
            'name'    => 'status',
            'label'   => 'showcase.label.status',
            'options' => $this->dataSet->getResult('select'),
        ]));
        
        $status->setValue($this->getPropertyAccessor()->getValue($defaults, '[status]'));
    }
}

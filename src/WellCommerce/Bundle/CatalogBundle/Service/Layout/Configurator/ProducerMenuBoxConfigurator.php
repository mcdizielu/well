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

namespace WellCommerce\Bundle\CatalogBundle\Service\Layout\Configurator;

use WellCommerce\Bundle\CatalogBundle\Controller\Box\ProducerMenuBoxController;
use WellCommerce\Bundle\CatalogBundle\DataSet\Admin\ProducerDataSet;
use WellCommerce\Bundle\CoreBundle\Layout\Configurator\AbstractLayoutBoxConfigurator;
use WellCommerce\Component\Form\Elements\FormInterface;
use WellCommerce\Component\Form\FormBuilderInterface;

/**
 * Class ProducerMenuBoxConfigurator
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class ProducerMenuBoxConfigurator extends AbstractLayoutBoxConfigurator
{
    /**
     * @var ProducerDataSet
     */
    private $dataSet;

    public function __construct(ProducerMenuBoxController $controller, ProducerDataSet $dataSet)
    {
        $this->controller = $controller;
        $this->dataSet    = $dataSet;
    }
    
    public function getType(): string
    {
        return 'ProducerMenu';
    }
    
    public function addFormFields(FormBuilderInterface $builder, FormInterface $form, $defaults)
    {
        $fieldset = $this->getFieldset($builder, $form);
        $accessor = $this->getPropertyAccessor();

        $fieldset->addChild($builder->getElement('tip', [
            'tip' => 'producer_menu.tip.layout_box_configuration',
        ]));

        $exclude = $fieldset->addChild($builder->getElement('tree', [
            'name'       => 'producers',
            'label'      => 'producer_menu.label.producers',
            'choosable'  => false,
            'selectable' => true,
            'sortable'   => false,
            'clickable'  => false,
            'items'      => $this->dataSet->getResult('flat_tree'),
        ]));

        $exclude->setValue($accessor->getValue($defaults, '[producers]'));
    }
}

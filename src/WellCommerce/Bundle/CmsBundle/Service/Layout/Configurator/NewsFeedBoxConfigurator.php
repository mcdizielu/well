<?php
/**
 * Created by PhpStorm.
 * User: diversantvlz
 * Date: 19.04.2017
 * Time: 18:37
 */

namespace WellCommerce\Bundle\CmsBundle\Service\Layout\Configurator;

use WellCommerce\Bundle\CmsBundle\Controller\Box\NewsFeedBoxController;
use WellCommerce\Bundle\CoreBundle\Layout\Configurator\AbstractLayoutBoxConfigurator;
use WellCommerce\Component\Form\Elements\FormInterface;
use WellCommerce\Component\Form\FormBuilderInterface;

class NewsFeedBoxConfigurator extends AbstractLayoutBoxConfigurator
{

    public function __construct(NewsFeedBoxController $controller)
    {
        $this->controller = $controller;
    }

    public function getType(): string
    {
        return 'NewsFeed';
    }

    public function addFormFields(FormBuilderInterface $builder, FormInterface $form, $defaults)
    {
        $fieldset = $this->getFieldset($builder, $form);

        $fieldset->addChild($builder->getElement('text_field', [
            'name'  => 'per_page',
            'label' => 'news_feed.layout_box.per_page',
        ]));
    }
}

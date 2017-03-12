<?php

namespace WellCommerce\Bundle\SearchBundle\Form\Front;

use WellCommerce\Bundle\CoreBundle\Form\AbstractFormBuilder;
use WellCommerce\Component\Form\Elements\FormInterface;

/**
 * Class AdvancedSearchFormBuilder
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class AdvancedSearchFormBuilder extends AbstractFormBuilder
{
    public function getAlias(): string
    {
        return 'front.advanced_search';
    }
    
    public function buildForm(FormInterface $form)
    {
        $form->addFilter($this->getFilter('no_code'));
        $form->addFilter($this->getFilter('trim'));
        $form->addFilter($this->getFilter('secure'));
    }
}

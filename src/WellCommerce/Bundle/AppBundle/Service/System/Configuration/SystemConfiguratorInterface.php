<?php

namespace WellCommerce\Bundle\AppBundle\Service\System\Configuration;

use WellCommerce\Component\Form\Elements\FormInterface;
use WellCommerce\Component\Form\FormBuilderInterface;

/**
 * Interface SystemConfiguratorInterface
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
interface SystemConfiguratorInterface
{
    public function saveParameters(array $data);
    
    public function getDefaults(): array;
    
    public function addFormFields(FormBuilderInterface $builder, FormInterface $form);
}

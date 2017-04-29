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
    public function getAlias(): string;

    public function getParameter(string $name);

    public function getParameters(): array;

    public function saveParameters(array $parameters);
    
    public function getDefaults(): array;
    
    public function addFormFields(FormBuilderInterface $builder, FormInterface $form);
}

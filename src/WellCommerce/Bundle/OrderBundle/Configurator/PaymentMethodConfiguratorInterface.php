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

namespace WellCommerce\Bundle\OrderBundle\Configurator;

use WellCommerce\Bundle\OrderBundle\Entity\PaymentMethod;
use WellCommerce\Component\Form\Dependencies\DependencyInterface;
use WellCommerce\Component\Form\Elements\ElementInterface;
use WellCommerce\Component\Form\FormBuilderInterface;

/**
 * Interface PaymentMethodConfiguratorInterface
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
interface PaymentMethodConfiguratorInterface
{
    public function getName(): string;
    
    public function configure(PaymentMethod $paymentMethod);
    
    public function getConfiguration(): array;
    
    public function getConfigurationKey(string $name): string;
    
    public function getConfigurationValue(string $name);
    
    public function addConfigurationFields(FormBuilderInterface $builder, ElementInterface $fieldset, DependencyInterface $dependency);
    
    public function getSupportedConfigurationKeys(): array;
    
    public function getInitializeTemplateName(): string;
}

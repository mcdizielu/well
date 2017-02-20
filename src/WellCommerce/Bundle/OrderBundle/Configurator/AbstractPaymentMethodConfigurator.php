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

use Symfony\Component\OptionsResolver\OptionsResolver;
use WellCommerce\Bundle\CoreBundle\DependencyInjection\AbstractContainerAware;
use WellCommerce\Bundle\OrderBundle\Entity\PaymentMethod;

/**
 * Class AbstractPaymentMethodConfigurator
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
abstract class AbstractPaymentMethodConfigurator extends AbstractContainerAware implements PaymentMethodConfiguratorInterface
{
    /**
     * @var string
     */
    protected $name;
    
    /**
     * @var array
     */
    protected $configuration;
    
    /**
     * @var string
     */
    protected $initializeTemplate;
    
    public function __construct(string $name, string $initializeTemplate)
    {
        $this->name               = $name;
        $this->initializeTemplate = $initializeTemplate;
    }
    
    public function getInitializeTemplateName(): string
    {
        return $this->initializeTemplate;
    }
    
    public function getName(): string
    {
        return $this->name;
    }
    
    public function configure(PaymentMethod $paymentMethod)
    {
        $configuration = $paymentMethod->getConfiguration();
        $resolver      = new OptionsResolver();
        $this->configureOptions($resolver);
        $this->configuration = $resolver->resolve($configuration);
    }
    
    public function getConfiguration(): array
    {
        if (null === $this->configuration) {
            throw new \LogicException('Processor was not configured prior to accessing configuration. Please use configure() method');
        }
        
        return $this->configuration;
    }
    
    public function getConfigurationKey(string $name): string
    {
        return sprintf('%s_%s', $this->getName(), $name);
    }
    
    public function getConfigurationValue(string $name)
    {
        $key = $this->getConfigurationKey($name);
        
        return $this->configuration[$key] ?? null;
    }
    
    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired($this->getSupportedConfigurationKeys());
    }
    
    public function getSupportedConfigurationKeys(): array
    {
        return [];
    }
}

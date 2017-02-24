<?php

namespace WellCommerce\Bundle\OrderBundle\Processor;

use WellCommerce\Bundle\OrderBundle\Configurator\PaymentMethodConfiguratorInterface;
use WellCommerce\Bundle\OrderBundle\Gateway\PaymentGatewayInterface;

/**
 * Class GenericPaymentProcessor
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class GenericPaymentProcessor implements PaymentProcessorInterface
{
    /**
     * @var PaymentGatewayInterface
     */
    protected $gateway;
    
    /**
     * @var PaymentMethodConfiguratorInterface
     */
    protected $configurator;
    
    /**
     * GenericPaymentProcessor constructor.
     *
     * @param PaymentGatewayInterface            $gateway
     * @param PaymentMethodConfiguratorInterface $configurator
     */
    public function __construct(PaymentGatewayInterface $gateway, PaymentMethodConfiguratorInterface $configurator)
    {
        $this->gateway      = $gateway;
        $this->configurator = $configurator;
    }
    
    public function getGateway(): PaymentGatewayInterface
    {
        return $this->gateway;
    }
    
    public function getConfigurator(): PaymentMethodConfiguratorInterface
    {
        return $this->configurator;
    }
}

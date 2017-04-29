<?php

namespace WellCommerce\Bundle\AppBundle\Service\System\Configuration;

use Symfony\Component\HttpKernel\KernelInterface;
use WellCommerce\Bundle\AppBundle\Entity\SystemConfiguration;
use WellCommerce\Bundle\AppBundle\Manager\SystemConfigurationManager;
use WellCommerce\Component\Form\Elements\FormInterface;
use WellCommerce\Component\Form\FormBuilderInterface;

/**
 * Class AbstractSystemConfigurator
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
abstract class AbstractSystemConfigurator implements SystemConfiguratorInterface
{
    /**
     * @var SystemConfigurationManager
     */
    protected $manager;
    
    /**
     * @var KernelInterface
     */
    protected $kernel;

    /**
     * @var array
     */
    protected $parameters;

    public function __construct(KernelInterface $kernel, SystemConfigurationManager $manager)
    {
        $this->kernel  = $kernel;
        $this->manager = $manager;
    }

    public function addFormFields(FormBuilderInterface $builder, FormInterface $form)
    {
    }

    public function getDefaults(): array
    {
        return [];
    }

    public function getParameter(string $name)
    {
        return $this->getParameters()[$name] ?? $this->getDefaults()[$name];
    }

    public function getParameters(): array
    {
        if (null === $this->parameters) {
            $configuration    = $this->manager->getConfiguration($this->getAlias());
            $this->parameters = ($configuration instanceof SystemConfiguration) ? $configuration->getParameters() : $this->getDefaults();
        }

        return $this->parameters;
    }

    public function saveParameters(array $parameters)
    {
        $parameters = array_filter($parameters);
        $this->manager->saveParameters($this->getAlias(), $parameters);
    }
}

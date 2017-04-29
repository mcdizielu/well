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

namespace WellCommerce\Bundle\AppBundle\Controller\Admin;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\Response;
use WellCommerce\Bundle\AppBundle\Manager\SystemConfigurationManager;
use WellCommerce\Bundle\AppBundle\Service\System\Configuration\SystemConfiguratorInterface;
use WellCommerce\Bundle\CoreBundle\Controller\AbstractController;

/**
 * Class SystemConfigurationController
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class SystemConfigurationController extends AbstractController
{
    /**
     * @var SystemConfigurationManager
     */
    protected $manager;
    
    public function indexAction(): Response
    {
        $form = $this->formBuilder->createForm(null, ['ajax_enabled' => false]);
        
        if ($form->handleRequest()->isSubmitted()) {
            $data = $form->getValue();
            $this->getConfigurators()->map(function (SystemConfiguratorInterface $configurator) use ($data) {
                $parameters = $data[$configurator->getAlias()] ?? [];
                $configurator->saveParameters($parameters);
            });
            
            $this->getFlashHelper()->addSuccess('system_configuration.flash.saved');
            
            return $this->redirectToAction('index');
        }
        
        return $this->displayTemplate('index', ['form' => $form]);
    }

    private function getConfigurators(): Collection
    {
        return $this->get('system.configurator.collection');
    }
}

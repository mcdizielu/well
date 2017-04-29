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

namespace WellCommerce\Bundle\AppBundle\Form\Admin;

use Doctrine\Common\Collections\Collection;
use WellCommerce\Bundle\AppBundle\Service\System\Configuration\SystemConfiguratorInterface;
use WellCommerce\Bundle\CoreBundle\Form\AbstractFormBuilder;
use WellCommerce\Component\Form\Elements\FormInterface;

/**
 * Class SystemConfigurationFormBuilder
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class SystemConfigurationFormBuilder extends AbstractFormBuilder
{
    public function getAlias(): string
    {
        return 'admin.system_configuration';
    }
    
    public function buildForm(FormInterface $form)
    {
        $this->getConfigurators()->map(function (SystemConfiguratorInterface $configurator) use ($form) {
            $configurator->addFormFields($this, $form);
        });
        
        $form->addFilter($this->getFilter('no_code'));
        $form->addFilter($this->getFilter('trim'));
        $form->addFilter($this->getFilter('secure'));
    }
    
    private function getConfigurators(): Collection
    {
        return $this->get('system.configurator.collection');
    }
}

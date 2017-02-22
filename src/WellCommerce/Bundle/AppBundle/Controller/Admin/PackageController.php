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

use Symfony\Component\HttpFoundation\Request;
use WellCommerce\Bundle\AppBundle\Entity\Package;
use WellCommerce\Bundle\AppBundle\Manager\PackageManager;
use WellCommerce\Bundle\CoreBundle\Controller\Admin\AbstractAdminController;
use WellCommerce\Bundle\CoreBundle\Helper\Environment\EnvironmentHelperInterface;
use WellCommerce\Bundle\CoreBundle\Helper\Package\PackageHelperInterface;

/**
 * Class PackageController
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class PackageController extends AbstractAdminController
{
    /**
     * @var PackageManager
     */
    protected $manager;
    
    public function syncAction()
    {
        $this->manager->syncPackages(PackageHelperInterface::DEFAULT_PACKAGE_BUNDLE_TYPE);
        $this->manager->syncPackages(PackageHelperInterface::DEFAULT_PACKAGE_THEME_TYPE);
        $this->manager->getFlashHelper()->addSuccess('package.flash.sync.success');
        
        return $this->getRouterHelper()->redirectToAction('index');
    }
    
    public function packageAction(Package $package = null, $operation)
    {
        if (null === $package) {
            return $this->redirectToAction('index');
        }
        
        $form = $this->formBuilder->createForm($package);
        
        return $this->displayTemplate('package', [
            'operation'   => $operation,
            'packageName' => $package->getFullName(),
            'form'        => $form,
        ]);
    }
    
    public function consoleAction(Request $request)
    {
        $helper    = $this->getHelper();
        $arguments = $this->manager->getConsoleCommandArguments($request);
        $process   = $helper->getProcess($arguments, 720);
        $process->run();
        
        if ($process->getExitCode() !== null) {
            if (0 === (int)$process->getExitCode()) {
                $this->manager->changePackageStatus($request);
            }
            
            return $this->jsonResponse(['code' => $process->getExitCode(), 'error' => $process->getErrorOutput()]);
        }
    }
    
    protected function getHelper(): EnvironmentHelperInterface
    {
        return $this->get('environment.helper');
    }
}

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

use SensioLabs\AnsiConverter\AnsiToHtmlConverter;
use SensioLabs\AnsiConverter\Theme\SolarizedTheme;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use WellCommerce\Bundle\AppBundle\Entity\Package;
use WellCommerce\Bundle\AppBundle\Manager\PackageManager;
use WellCommerce\Bundle\CoreBundle\Console\Output\ConsoleHtmlOutput;
use WellCommerce\Bundle\CoreBundle\Controller\Admin\AbstractAdminController;
use WellCommerce\Bundle\CoreBundle\Helper\Package\PackageHelperInterface;
use WellCommerce\Bundle\CoreBundle\Helper\Process\ProcessHelperInterface;

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
    
    public function indexAction(): Response
    {
        $this->manager->syncPackages(PackageHelperInterface::DEFAULT_PACKAGE_BUNDLE_TYPE);
        $this->manager->syncPackages(PackageHelperInterface::DEFAULT_PACKAGE_THEME_TYPE);
        $this->manager->getFlashHelper()->addSuccess('package.flash.sync.success');
        
        return $this->displayTemplate('index', [
            'datagrid' => $this->dataGrid,
        ]);
    }
    
    public function packageAction(Package $package, $operation)
    {
        $form = $this->formBuilder->createForm($package);
        
        return $this->displayTemplate('package', [
            'operation'   => $operation,
            'packageName' => $package->getFullName(),
            'form'        => $form,
        ]);
    }
    
    public function runAction(Package $package, string $operation)
    {
        $output    = new ConsoleHtmlOutput();
        $arguments = ['app/console', 'wellcommerce:package:' . $operation, '--package=' . $package->getName(),];
        $process   = $this->getProcessHelper()->createProcess($arguments);
        $process->start();
        
        foreach ($process as $type => $data) {
            $output->write($data);
        }
        
        return new Response('');
    }
    
    private function getProcessHelper(): ProcessHelperInterface
    {
        return $this->get('process.helper');
    }
}

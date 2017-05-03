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

use Carbon\Carbon;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Response;
use WellCommerce\Bundle\AppBundle\Entity\Theme;
use WellCommerce\Bundle\CoreBundle\Controller\Admin\AbstractAdminController;
use WellCommerce\Bundle\CoreBundle\Entity\EntityInterface;

/**
 * Class ThemeController
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ThemeController extends AbstractAdminController
{
    public function duplicateAction(int $id): Response
    {
        /** @var Theme $sourceTheme */
        $sourceTheme = $this->getManager()->getRepository()->find($id);
        
        if (!$sourceTheme instanceof EntityInterface) {
            return $this->redirectToAction('index');
        }
        
        $targetTheme = $this->duplicateTheme($sourceTheme);
        
        $this->getFlashHelper()->addSuccess('theme.flash.duplicate_success');
        
        return $this->redirectToAction('edit', ['id' => $targetTheme->getId()]);
    }
    
    private function duplicateTheme(Theme $sourceTheme): Theme
    {
        /** @var Theme $targetTheme */
        $targetTheme = $this->manager->initResource();
        $targetTheme->setName(sprintf('%s (%s)', $sourceTheme->getName(), Carbon::now()->format('Y-m-d H:i:s')));
        $targetTheme->setFolder(sprintf('%s-%s', $sourceTheme->getFolder(), Carbon::now()->format('YmdHis')));
        
        $this->manager->createResource($targetTheme);
        
        $filesystem = new Filesystem();
        $themesDir  = $this->getKernel()->getRootDir() . '/../web/themes/';
        $sourceDir  = $themesDir . $sourceTheme->getFolder();
        $targetDir  = $themesDir . $targetTheme->getFolder();
        $filesystem->mirror($sourceDir, $targetDir);
        
        return $targetTheme;
    }
}

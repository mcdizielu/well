<?php

namespace WellCommerce\Bundle\GeneratorBundle\Generator;

use WellCommerce\Bundle\GeneratorBundle\Model\WellCommerceBundle;

/**
 * Class ControllerGenerator
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ControllerGenerator extends AbstractGenerator
{
    public function generate(WellCommerceBundle $bundle)
    {
        $this->generateClasses($bundle);
    }
    
    private function generateClasses(WellCommerceBundle $bundle)
    {
        $classTemplates = [
            'Admin/Controller.php.twig' => sprintf('Controller/Admin/%sController.php', $bundle->getPrefix()),
            'Front/Controller.php.twig' => sprintf('Controller/Front/%sController.php', $bundle->getPrefix()),
        ];
        
        foreach ($classTemplates as $template => $target) {
            $template = 'WellCommerceCoreBundle:skeleton/bundle:' . $template;
            $content  = $this->renderTemplate($bundle, $template);
            
            $this->filesystem->dumpFile($bundle->getTargetDirectory() . '/' . $target, $content);
        }
    }
}

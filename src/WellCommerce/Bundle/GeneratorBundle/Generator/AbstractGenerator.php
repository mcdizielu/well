<?php

namespace WellCommerce\Bundle\GeneratorBundle\Generator;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;
use WellCommerce\Bundle\GeneratorBundle\Model\WellCommerceBundle;

/**
 * Class AbstractGenerator
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
abstract class AbstractGenerator
{
    /**
     * @var KernelInterface
     */
    protected $kernel;
    
    /**
     * @var \Twig_Environment
     */
    protected $twig;
    
    /**
     * @var Filesystem
     */
    protected $filesystem;
    
    public function __construct(KernelInterface $kernel, \Twig_Environment $twig)
    {
        $this->kernel     = $kernel;
        $this->twig       = $twig;
        $this->filesystem = new Filesystem();
    }
    
    abstract public function generate(WellCommerceBundle $bundle);
    
    protected function renderTemplate(WellCommerceBundle $bundle, string $template): string
    {
        return $this->twig->render('WellCommerceCoreBundle:skeleton/bundle:' . $template, [
            'bundle' => $bundle,
        ]);
    }
}

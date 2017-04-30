<?php

namespace WellCommerce\Bundle\CoreBundle\Helper\Composer;

use Composer\Composer;
use Composer\Factory;
use Composer\IO\NullIO;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class ComposerHelper
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class ComposerHelper implements ComposerHelperInterface
{
    /**
     * @var KernelInterface
     */
    private $kernel;
    
    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }
    
    public function getPackages(): array
    {
        $composer      = $this->getComposer();
        $installedRepo = $composer->getRepositoryManager()->getLocalRepository();
        
        return $installedRepo->getPackages();
    }
    
    public function getComposer(): Composer
    {
        $composerCacheDir = $this->kernel->getRootDir() . '/.composer';
        $composerJson     = $this->kernel->getRootDir() . '/../composer.json';
        
        putenv('COMPOSER_HOME=' . $composerCacheDir);
        
        return Factory::create(new NullIO(), $composerJson);
    }
}

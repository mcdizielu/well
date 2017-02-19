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

namespace WellCommerce\Bundle\ApiBundle\CacheWarmer;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmer;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Yaml\Yaml;
use WellCommerce\Component\Serializer\Metadata\Loader\SerializationMetadataLoaderInterface;

/**
 * Class SerializationCacheWarmer
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class SerializationCacheWarmer extends CacheWarmer
{
    /**
     * @var KernelInterface
     */
    private $kernel;
    
    /**
     * @var array
     */
    private $mapping;
    
    /**
     * @var Filesystem
     */
    private $filesystem;
    
    public function __construct(KernelInterface $kernel, array $mapping)
    {
        $this->kernel     = $kernel;
        $this->mapping    = $mapping;
        $this->filesystem = new Filesystem();
    }
    
    public function warmUp($cacheDir)
    {
        $configuration = $this->getConfiguration();
        
        if (count($configuration)) {
            $file = $cacheDir . '/' . SerializationMetadataLoaderInterface::CACHE_FILENAME;
            $this->writeCacheFile($file, sprintf('<?php return %s;', var_export($configuration, true)));
        }
    }
    
    private function getConfiguration(): array
    {
        $configuration = [];
        
        foreach ($this->mapping as $className => $options) {
            $path = $this->resolvePath($options['mapping']);
            if ($this->filesystem->exists($path)) {
                $content       = file_get_contents($path);
                $configuration = array_replace_recursive($configuration, $this->parseContent($content));
            }
        }
        
        return $configuration;
    }
    
    private function parseContent(string $content): array
    {
        return Yaml::parse($content);
    }
    
    private function resolvePath(string $path): string
    {
        $resourcePathExploded = explode('/', $path);
        $resourcePathRoot     = array_shift($resourcePathExploded);
        
        if (strpos($resourcePathRoot, '@') === 0) {
            $mappingFileBundle = ltrim($resourcePathRoot, '@');
            $bundle            = $this->kernel->getBundle($mappingFileBundle);
            
            if ($bundle instanceof BundleInterface) {
                $resourcePathRoot = $bundle->getPath();
            }
        }
        
        array_unshift($resourcePathExploded, $resourcePathRoot);
        
        return implode('/', $resourcePathExploded);
    }
    
    public function isOptional()
    {
        return false;
    }
}

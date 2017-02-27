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

namespace WellCommerce\Bundle\CoreBundle\Helper\Image;

use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Liip\ImagineBundle\Imagine\Filter\FilterConfiguration;

/**
 * Class ImageHelper
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class ImageHelper implements ImageHelperInterface
{
    /**
     * @var CacheManager
     */
    private $cacheManager;
    
    /**
     * @var FilterConfiguration
     */
    private $configuration;
    
    public function __construct(CacheManager $cacheManager, FilterConfiguration $configuration)
    {
        $this->cacheManager  = $cacheManager;
        $this->configuration = $configuration;
    }
    
    public function getImage(string $path = null, string $filter, array $config = []): string
    {
        if ('' === (string)$path) {
            return $this->getDefaultImage($filter);
        }
        
        return $this->cacheManager->getBrowserPath($path, $filter, $config);
    }
    
    private function getDefaultImage(string $filter): string
    {
        $configuration = $this->configuration->get($filter);
        $size          = $configuration['filters']['thumbnail']['size'] ?? [300, 300];
        list($width, $height) = $size;
        
        return sprintf('http://placehold.it/%sx%s', $width, $height);
    }
}

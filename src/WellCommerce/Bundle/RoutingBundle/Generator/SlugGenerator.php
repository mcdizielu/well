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

namespace WellCommerce\Bundle\RoutingBundle\Generator;

use WellCommerce\Bundle\CoreBundle\Doctrine\Repository\RepositoryInterface;
use WellCommerce\Bundle\CoreBundle\Helper\Helper;
use WellCommerce\Bundle\RoutingBundle\Entity\Route;

/**
 * Class SlugGenerator
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class SlugGenerator implements SlugGeneratorInterface
{
    /**
     * @var RepositoryInterface
     */
    private $repository;
    
    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }
    
    public function generate(string $name, $id, string $locale, $values, int $iteration = 0): string
    {
        $slug           = Helper::urlize($name);
        $existsInValues = in_array($slug, (array)$values);
        
        // if slug is the same as other values, try to add locale part
        if ($existsInValues) {
            $slug = sprintf('%s-%s', $slug, $locale);
        }
        
        $route = $this->repository->findOneBy(['path' => $slug]);
        
        if (null !== $route) {
            // if passed identifier and locale are same as in route, assume we can change the slug directly
            if ($this->hasRouteSameLocaleAndId($route, $locale, $id)) {
                return $slug;
            } else {
                $iteration++;
                $slug = $this->makeSlugIterated($slug, $iteration);
                
                return $this->generate($slug, $id, $locale, $values, $iteration);
            }
        }
        
        return $slug;
    }
    
    private function hasRouteSameLocaleAndId(Route $route, string $locale, $id): bool
    {
        return ((int)$route->getIdentifier()->getId() === (int)$id && $route->getLocale() === $locale);
    }
    
    private function makeSlugIterated(string $slug, int $iteration): string
    {
        return sprintf('%s-%s', $slug, $iteration);
    }
}

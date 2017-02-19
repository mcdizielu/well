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

namespace WellCommerce\Bundle\RoutingBundle\Provider;

use Doctrine\Common\Util\ClassUtils;
use Symfony\Cmf\Component\Routing\RouteProviderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Route as SymfonyRoute;
use Symfony\Component\Routing\RouteCollection;
use WellCommerce\Bundle\CoreBundle\Doctrine\Repository\RepositoryInterface;
use WellCommerce\Bundle\RoutingBundle\Entity\Route;

/**
 * Class RouteProvider
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class RouteProvider implements RouteProviderInterface
{
    const DYNAMIC_PREFIX        = 'dynamic_';
    const PATH_PARAMS_SEPARATOR = ',';
    
    /**
     * @var array
     */
    private $routingGeneratorMap;
    
    /**
     * @var RepositoryInterface
     */
    private $repository;
    
    /**
     * RouteProvider constructor.
     *
     * @param array               $routingGeneratorMap
     * @param RepositoryInterface $repository
     */
    public function __construct(array $routingGeneratorMap = [], RepositoryInterface $repository)
    {
        $this->routingGeneratorMap = $routingGeneratorMap;
        $this->repository          = $repository;
    }
    
    /**
     * Returns route collection for current request
     *
     * @param Request $request
     *
     * @return RouteCollection
     */
    public function getRouteCollectionForRequest(Request $request)
    {
        $collection = new RouteCollection();
        $path       = $this->getNormalizedPath($request);
        $resource   = $this->repository->findOneBy(['path' => $path]);
        
        if ($resource) {
            $route = $this->createRoute($resource);
            $collection->add(
                self::DYNAMIC_PREFIX . $resource->getId(),
                $route
            );
        }
        
        return $collection;
    }
    
    /**
     * Returns route by its identifier
     *
     * @param string $identifier
     *
     * @return SymfonyRoute
     */
    public function getRouteByName($identifier)
    {
        $id       = str_replace(self::DYNAMIC_PREFIX, '', $identifier);
        $resource = $this->repository->find($id);
        
        if ($resource instanceof Route) {
            return $this->createRoute($resource);
        }
        
        return null;
    }
    
    public function getRoutesByNames($names, $parameters = [])
    {
        $collection = $this->repository->getCollection();
        $routes     = [];
        
        $collection->map(function (Route $route) use (&$routes) {
            $routes[] = $this->getRouteByName($route->getId());
        });
        
        return $routes;
    }
    
    /**
     * Returns normalized path used in resource query
     *
     * @param Request $request
     *
     * @return mixed
     */
    private function getNormalizedPath(Request $request)
    {
        $path  = ltrim($request->getPathInfo(), '/');
        $paths = explode(self::PATH_PARAMS_SEPARATOR, $path);
        
        return current($paths);
    }
    
    private function createRoute(Route $resource): SymfonyRoute
    {
        $settings                        = $this->getRouteGenerationSettings($resource);
        $settings['defaults']['id']      = $resource->getIdentifier()->getId();
        $settings['defaults']['_locale'] = $resource->getLocale();
        
        return new SymfonyRoute(
            $this->getPath($resource, $settings['pattern']),
            $settings['defaults'],
            $settings['requirements'],
            $settings['options']
        );
    }
    
    private function getRouteGenerationSettings(Route $resource): array
    {
        $class = ClassUtils::getRealClass(get_class($resource));
        
        if (!isset($this->routingGeneratorMap[$class])) {
            throw new \InvalidArgumentException(
                sprintf('Route resource of type "%s" has invalid/missing configuration.', $class)
            );
        }
        
        return $this->routingGeneratorMap[$class];
    }
    
    private function getPath(Route $resource, string $pattern): string
    {
        if (strlen($pattern)) {
            return $resource->getPath() . self::PATH_PARAMS_SEPARATOR . $pattern;
        }
        
        return $resource->getPath();
    }
}

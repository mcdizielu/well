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

namespace WellCommerce\Bundle\AppBundle\Provider;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;
use WellCommerce\Bundle\AppBundle\Entity\AdminMenu;
use WellCommerce\Bundle\CoreBundle\Doctrine\Repository\RepositoryInterface;

/**
 * Class AdminMenuProvider
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class AdminMenuProvider
{
    const CACHE_FILENAME = 'admin_menu.php';
    
    /**
     * @var KernelInterface
     */
    protected $kernel;
    
    /**
     * @var RepositoryInterface
     */
    protected $repository;
    
    /**
     * AdminMenuProvider constructor.
     *
     * @param KernelInterface     $kernel
     * @param RepositoryInterface $repository
     */
    public function __construct(KernelInterface $kernel, RepositoryInterface $repository)
    {
        $this->kernel     = $kernel;
        $this->repository = $repository;
    }
    
    public function getMenu()
    {
        if (is_file($cache = $this->kernel->getCacheDir() . '/' . self::CACHE_FILENAME)) {
            $menu = require $cache;
            
            return $menu;
        }
        
        $menu = $this->generateMenu();
        $this->writeCache($menu);
        
        return $menu;
    }
    
    protected function generateMenu()
    {
        $criteria = new Criteria();
        $criteria->orderBy(['hierarchy' => 'asc']);
        
        $collection = $this->repository->matching($criteria);
        $elements   = $this->filterElements($collection, null);
        $tree       = $this->generateTree($collection, $elements);
        
        return $tree;
    }
    
    /**
     * Generates a tree for given children elements
     *
     * @param Collection $collection
     * @param Collection $children
     *
     * @return array
     */
    protected function generateTree(Collection $collection, Collection $children)
    {
        $children->map(function (AdminMenu $menuItem) use ($collection, &$tree) {
            $tree[] = [
                'routeName' => $menuItem->getRouteName(),
                'cssClass'  => $menuItem->getCssClass(),
                'name'      => $menuItem->getName(),
                'children'  => $this->generateTree($collection, $this->filterElements($collection, $menuItem)),
            ];
        });
        
        return $tree;
    }
    
    protected function writeCache(array $menu)
    {
        $file    = $this->kernel->getCacheDir() . '/' . self::CACHE_FILENAME;
        $content = sprintf('<?php return %s;', var_export($menu, true));
        $fs      = new Filesystem();
        $fs->dumpFile($file, $content);
    }
    
    /**
     * Filters the collection and returns only children elements for given parent element
     *
     * @param Collection     $collection
     * @param AdminMenu|null $parent
     *
     * @return Collection
     */
    protected function filterElements(Collection $collection, AdminMenu $parent = null)
    {
        $children = $collection->filter(function (AdminMenu $menuItem) use ($parent) {
            return $menuItem->getParent() === $parent;
        });
        
        return $children;
    }
}

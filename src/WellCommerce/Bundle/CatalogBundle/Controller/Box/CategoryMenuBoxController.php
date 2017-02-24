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

namespace WellCommerce\Bundle\CatalogBundle\Controller\Box;

use Symfony\Component\HttpFoundation\Response;
use WellCommerce\Bundle\CatalogBundle\Entity\Category;
use WellCommerce\Bundle\CatalogBundle\Repository\CategoryRepository;
use WellCommerce\Bundle\CoreBundle\Controller\Box\AbstractBoxController;
use WellCommerce\Component\Layout\Collection\LayoutBoxSettingsCollection;

/**
 * Class CategoryMenuBoxController
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class CategoryMenuBoxController extends AbstractBoxController
{
    public function indexAction(LayoutBoxSettingsCollection $boxSettings): Response
    {
        return $this->displayTemplate('index', [
            'active' => $this->getActiveCategories(),
        ]);
    }
    
    private function getActiveCategories(): array
    {
        $active = [];
        if ($this->getCategoryStorage()->hasCurrentCategory()) {
            /** @var CategoryRepository $repository */
            $repository = $this->manager->getRepository();
            $category   = $this->getCategoryStorage()->getCurrentCategory();
            $paths      = $repository->getCategoryPath($category);
            $active     = [];
            
            /** @var Category $path */
            foreach ($paths as $path) {
                $active[] = $path->getId();
            }
        }
        
        return $active;
    }
}

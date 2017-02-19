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

namespace WellCommerce\Bundle\CatalogBundle\Controller\Front;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use WellCommerce\Bundle\CatalogBundle\Entity\Category;
use WellCommerce\Bundle\CatalogBundle\Entity\Product;
use WellCommerce\Bundle\CoreBundle\Controller\Front\AbstractFrontController;
use WellCommerce\Component\Breadcrumb\Model\Breadcrumb;

/**
 * Class ProductController
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ProductController extends AbstractFrontController
{
    public function indexAction(Product $product = null): Response
    {
        if (!$product instanceof Product || $product->getCategories()->isEmpty()) {
            return $this->redirectToRoute('front.home_page.index');
        }
        
        $this->addBreadcrumbs($product);
        $this->getProductStorage()->setCurrentProduct($product);
        
        return $this->displayTemplate('index', [
            'product'  => $product,
            'metadata' => $product->translate()->getMeta(),
        ]);
    }
    
    public function viewAction(Product $product): JsonResponse
    {
        $this->getProductStorage()->setCurrentProduct($product);
        
        $templateData       = $this->get('product.helper')->getProductDefaultTemplateData($product);
        $basketModalContent = $this->renderView('WellCommerceCatalogBundle:Front/Product:view.html.twig', $templateData);
        
        return $this->jsonResponse([
            'basketModalContent' => $basketModalContent,
            'templateData'       => $templateData,
        ]);
    }
    
    private function addBreadcrumbs(Product $product)
    {
        $category = $product->getCategories()->last();
        $paths    = $this->get('category.repository')->getCategoryPath($category);
        
        /** @var Category $path */
        foreach ($paths as $path) {
            $this->getBreadcrumbProvider()->add(new Breadcrumb([
                'label' => $path->translate()->getName(),
                'url'   => $this->getRouterHelper()->generateUrl($path->translate()->getRoute()->getId()),
            ]));
        }
        
        $this->getBreadcrumbProvider()->add(new Breadcrumb([
            'label' => $product->translate()->getName(),
        ]));
    }
}

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

namespace WellCommerce\Bundle\WishlistBundle\Controller\Front;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use WellCommerce\Bundle\CatalogBundle\Entity\Product;
use WellCommerce\Bundle\CoreBundle\Controller\Front\AbstractFrontController;
use WellCommerce\Bundle\WishlistBundle\Entity\Wishlist;

/**
 * Class WishlistController
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class WishlistController extends AbstractFrontController
{
    public function indexAction(): Response
    {
        return $this->displayTemplate('index');
    }
    
    public function addAction(Product $product): RedirectResponse
    {
        $wishlist = $this->findWishlist($product);
        
        if (!$wishlist instanceof Wishlist) {
            /** @var Wishlist $wishlist */
            $wishlist = $this->manager->initResource();
            $wishlist->setClient($this->getAuthenticatedClient());
            $wishlist->setProduct($product);
            $this->manager->createResource($wishlist);
        }
        
        return $this->redirectToAction('index');
    }
    
    public function deleteAction(Product $product): RedirectResponse
    {
        $wishlist = $this->findWishlist($product);
        
        if ($wishlist instanceof Wishlist) {
            $this->manager->removeResource($wishlist);
        }
        
        return $this->redirectToAction('index');
    }
    
    private function findWishlist(Product $product)
    {
        return $this->manager->getRepository()->findOneBy([
            'client'  => $this->getAuthenticatedClient(),
            'product' => $product,
        ]);
    }
}

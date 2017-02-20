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

namespace WellCommerce\Bundle\WishlistBundle\Controller\Box;

use Symfony\Component\HttpFoundation\Response;
use WellCommerce\Bundle\AppBundle\Collection\LayoutBoxSettingsCollection;
use WellCommerce\Bundle\CoreBundle\Controller\Box\AbstractBoxController;
use WellCommerce\Bundle\WishlistBundle\Entity\Wishlist;
use WellCommerce\Bundle\WishlistBundle\Repository\WishlistRepositoryInterface;
use WellCommerce\Component\DataSet\Conditions\Condition\In;
use WellCommerce\Component\DataSet\Conditions\ConditionsCollection;

/**
 * Class WishlistBoxController
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class WishlistBoxController extends AbstractBoxController
{
    public function indexAction(LayoutBoxSettingsCollection $boxSettings): Response
    {
        $dataset = $this->get('product.dataset.front')->getResult('array', [
            'order_by'   => 'name',
            'order_dir'  => 'asc',
            'conditions' => $this->createConditions(),
        ]);
        
        return $this->displayTemplate('index', [
            'dataset' => $dataset,
        ]);
    }
    
    private function createConditions(): ConditionsCollection
    {
        /** @var WishlistRepositoryInterface $repository */
        $repository = $this->getManager()->getRepository();
        $wishlist   = $repository->getClientWishlistCollection($this->getAuthenticatedClient());
        $productIds = [];
        
        $wishlist->map(function (Wishlist $wishlist) use (&$productIds) {
            $productIds[] = $wishlist->getProduct()->getId();
        });
        
        $conditions = new ConditionsCollection();
        $conditions->add(new In('id', $productIds));
        
        return $conditions;
    }
}

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

namespace WellCommerce\Bundle\AlsoPurchasedBundle\Controller\Box;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Symfony\Component\HttpFoundation\Response;
use WellCommerce\Bundle\CatalogBundle\Entity\Product;
use WellCommerce\Bundle\CoreBundle\Controller\Box\AbstractBoxController;
use WellCommerce\Bundle\CoreBundle\Doctrine\Repository\RepositoryInterface;
use WellCommerce\Bundle\OrderBundle\Entity\OrderProduct;
use WellCommerce\Component\DataSet\Conditions\Condition\In;
use WellCommerce\Component\DataSet\Conditions\ConditionsCollection;
use WellCommerce\Component\Layout\Collection\LayoutBoxSettingsCollection;

/**
 * Class LastViewedBoxController
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class AlsoPurchasedBoxController extends AbstractBoxController
{
    public function indexAction(LayoutBoxSettingsCollection $boxSettings): Response
    {
        $dataset        = $this->get('product.dataset.front');
        $currentProduct = $this->getProductStorage()->getCurrentProduct();
        
        $products = $dataset->getResult('array', [
            'limit'      => $boxSettings->getParam('limit', 4),
            'page'       => 1,
            'order_by'   => 'hierarchy',
            'order_dir'  => 'asc',
            'conditions' => $this->createConditionsCollection($currentProduct),
        ]);
        
        return $this->displayTemplate('index', [
            'dataset'     => $products,
            'boxSettings' => $boxSettings,
        ]);
    }
    
    private function createConditionsCollection(Product $product = null): ConditionsCollection
    {
        $conditions  = new ConditionsCollection();
        $identifiers = [0];
        
        if ($product instanceof Product) {
            $otherProducts = $this->getAlsoPurchasedProducts($product);
            $identifiers   = $otherProducts->getKeys();
            
            if (0 === count($identifiers)) {
                $identifiers = [0];
            }
        }
        
        $conditions->add(new In('id', $identifiers));
        
        return $conditions;
    }
    
    private function getAlsoPurchasedProducts(Product $product): Collection
    {
        $criteria = new Criteria();
        $criteria->where($criteria->expr()->eq('product', $product));
        $collection    = $this->getOrderProductRepository()->matching($criteria);
        $otherProducts = new ArrayCollection();
        
        $collection->map(function (OrderProduct $orderProduct) use ($otherProducts, $product) {
            $orderProduct->getOrder()->getProducts()->map(function (OrderProduct $otherProduct) use ($otherProducts, $product) {
                if ($otherProduct->getProduct()->getId() !== $product->getId()) {
                    $otherProducts->set($otherProduct->getProduct()->getId(), $otherProduct);
                }
            });
        });
        
        return $otherProducts;
    }
    
    private function getOrderProductRepository(): RepositoryInterface
    {
        return $this->get('order_product.repository');
    }
}

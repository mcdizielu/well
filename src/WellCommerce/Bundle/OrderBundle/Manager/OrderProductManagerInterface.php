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

namespace WellCommerce\Bundle\OrderBundle\Manager;

use WellCommerce\Bundle\CatalogBundle\Entity\Product;
use WellCommerce\Bundle\CatalogBundle\Entity\Variant;
use WellCommerce\Bundle\CoreBundle\Manager\ManagerInterface;
use WellCommerce\Bundle\OrderBundle\Entity\Order;
use WellCommerce\Bundle\OrderBundle\Entity\OrderProduct;

/**
 * Interface OrderProductManagerInterface
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
interface OrderProductManagerInterface extends ManagerInterface
{
    public function addProductToOrder(Product $product, Variant $variant = null, int $quantity = 1, Order $order);
    
    public function getCartQuantity(Product $product, Variant $variant = null, Order $order): int;
    
    public function findProductInOrder(Product $product, Variant $variant = null, Order $order);
    
    public function createOrderProduct(Product $product, Variant $variant = null, Order $order): OrderProduct;
    
    public function deleteOrderProduct(OrderProduct $orderProduct, Order $order);
    
    public function changeOrderProductQuantity(OrderProduct $orderProduct, Order $order, int $quantity);
}

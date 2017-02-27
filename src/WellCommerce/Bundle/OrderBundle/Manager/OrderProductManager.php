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

use WellCommerce\Bundle\AppBundle\Entity\Price;
use WellCommerce\Bundle\AppBundle\Helper\TaxHelper;
use WellCommerce\Bundle\CatalogBundle\Entity\Product;
use WellCommerce\Bundle\CatalogBundle\Entity\Variant;
use WellCommerce\Bundle\CoreBundle\Manager\AbstractManager;
use WellCommerce\Bundle\OrderBundle\Entity\Order;
use WellCommerce\Bundle\OrderBundle\Entity\OrderProduct;
use WellCommerce\Bundle\OrderBundle\Exception\ChangeOrderProductQuantityException;
use WellCommerce\Bundle\OrderBundle\Exception\DeleteOrderProductException;

/**
 * Class OrderProductManager
 *
 * @author Adam Piotrowski <adam@wellcommerce.org>
 */
final class OrderProductManager extends AbstractManager implements OrderProductManagerInterface
{
    public function addProductToOrder(Product $product, Variant $variant = null, int $quantity = 1, Order $order)
    {
        $orderProduct = $this->findProductInOrder($product, $variant, $order);
        
        if (!$orderProduct instanceof OrderProduct) {
            $orderProduct = $this->createOrderProduct($product, $variant, $order);
            $orderProduct->setQuantity($quantity);
            $order->getProducts()->add($orderProduct);
        } else {
            $orderProduct->increaseQuantity($quantity);
        }
        
        $this->updateResource($order);
    }
    
    public function getCartQuantity(Product $product, Variant $variant = null, Order $order): int
    {
        $quantity     = 0;
        $orderProduct = $this->findProductInOrder($product, $variant, $order);
        if ($orderProduct instanceof OrderProduct) {
            $quantity = $orderProduct->getQuantity();
        }
        
        return $quantity;
    }
    
    public function findProductInOrder(Product $product, Variant $variant = null, Order $order)
    {
        return $this->getRepository()->findOneBy([
            'order'   => $order,
            'product' => $product,
            'variant' => $variant,
        ]);
    }
    
    public function createOrderProduct(Product $product, Variant $variant = null, Order $order): OrderProduct
    {
        /** @var OrderProduct $orderProduct */
        $orderProduct = $this->initResource();
        $orderProduct->setOrder($order);
        $orderProduct->setProduct($product);
        
        if ($variant instanceof Variant) {
            $orderProduct->setVariant($variant);
        }
        
        return $orderProduct;
    }
    
    public function deleteOrderProduct(OrderProduct $orderProduct, Order $order)
    {
        if (false === $order->getProducts()->contains($orderProduct)) {
            throw new DeleteOrderProductException($orderProduct);
        }
        
        $this->removeResource($orderProduct);
        $this->updateResource($order);
    }
    
    public function changeOrderProductQuantity(OrderProduct $orderProduct, Order $order, int $quantity)
    {
        if (false === $order->getProducts()->contains($orderProduct)) {
            throw new ChangeOrderProductQuantityException($orderProduct);
        }
        
        if ($quantity < 1) {
            $this->deleteOrderProduct($orderProduct, $order);
        } else {
            $orderProduct->setQuantity($quantity);
        }
        
        $this->updateResource($order);
    }
    
    public function addUpdateOrderProduct(array $productValues, Order $order): OrderProduct
    {
        $orderProduct = $this->getRepository()->findOneBy(['id' => $productValues['id']]);
        if (!$orderProduct instanceof OrderProduct) {
            $orderProduct = $this->addOrderProduct($productValues, $order);
            $order->getProducts()->add($orderProduct);
        } else {
            $this->updateOrderProduct($orderProduct, $productValues);
        }
        
        return $orderProduct;
    }
    
    private function updateOrderProduct(OrderProduct $orderProduct, array $productValues)
    {
        $sellPrice   = $orderProduct->getSellPrice();
        $grossAmount = $productValues['gross_amount'];
        $taxRate     = $orderProduct->getSellPrice()->getTaxRate();
        $netAmount   = TaxHelper::calculateNetPrice($grossAmount, $taxRate);
        
        $sellPrice->setTaxRate($taxRate);
        $sellPrice->setTaxAmount($grossAmount - $netAmount);
        $sellPrice->setNetAmount($netAmount);
        $sellPrice->setGrossAmount($grossAmount);
        $orderProduct->setWeight($productValues['weight']);
        $orderProduct->setQuantity($productValues['quantity']);
    }
    
    private function addOrderProduct(array $productValues, Order $order): OrderProduct
    {
        $productId = (int)$productValues['product_id'];
        $product   = $this->getEntityManager()->getRepository(Product::class)->find($productId);
        if (!$product instanceof Product) {
            throw new \InvalidArgumentException(sprintf('Cannot add product to order. ID "%s" does not exists.', $productId));
        }
        
        /** @var OrderProduct $orderProduct */
        $orderProduct = $this->initResource();
        $orderProduct->setBuyPrice($product->getBuyPrice());
        $orderProduct->setOrder($order);
        $orderProduct->setProduct($product);
        $orderProduct->setQuantity($productValues['quantity']);
        $orderProduct->setWeight($productValues['weight']);
        
        $sellPrice   = new Price();
        $grossAmount = $productValues['gross_amount'];
        $taxRate     = $product->getSellPriceTax()->getValue();
        $netAmount   = TaxHelper::calculateNetPrice($grossAmount, $taxRate);
        
        $sellPrice->setTaxRate($taxRate);
        $sellPrice->setTaxAmount($grossAmount - $netAmount);
        $sellPrice->setNetAmount($netAmount);
        $sellPrice->setGrossAmount($grossAmount);
        $sellPrice->setCurrency($order->getCurrency());
        
        $orderProduct->setSellPrice($sellPrice);
        
        return $orderProduct;
    }
}

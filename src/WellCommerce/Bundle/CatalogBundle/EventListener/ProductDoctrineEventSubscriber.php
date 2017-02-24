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

namespace WellCommerce\Bundle\CatalogBundle\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use WellCommerce\Bundle\AppBundle\Helper\TaxHelper;
use WellCommerce\Bundle\CatalogBundle\Entity\Product;
use WellCommerce\Bundle\CatalogBundle\Entity\Variant;

/**
 * Class ProductDoctrineEventSubscriber
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ProductDoctrineEventSubscriber implements EventSubscriber
{
    public function getSubscribedEvents()
    {
        return [
            'prePersist',
            'preUpdate',
        ];
    }
    
    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->onProductDataBeforeSave($args);
    }
    
    public function prePersist(LifecycleEventArgs $args)
    {
        $this->onProductDataBeforeSave($args);
    }
    
    public function onProductDataBeforeSave(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if ($entity instanceof Product) {
            $this->refreshProductSellPrices($entity);
            $this->refreshProductBuyPrices($entity);
            $this->syncProductStock($entity);
        }
        
        if ($entity instanceof Variant) {
            $this->refreshProductVariantSellPrice($entity);
            $this->syncVariantStock($entity);
        }
    }
    
    protected function refreshProductSellPrices(Product $product)
    {
        $sellPrice             = $product->getSellPrice();
        $grossAmount           = $sellPrice->getGrossAmount();
        $discountedGrossAmount = $sellPrice->getDiscountedGrossAmount();
        $taxRate               = $product->getSellPriceTax()->getValue();
        $netAmount             = TaxHelper::calculateNetPrice($grossAmount, $taxRate);
        $discountedNetAmount   = TaxHelper::calculateNetPrice($discountedGrossAmount, $taxRate);
        
        $sellPrice->setTaxRate($taxRate);
        $sellPrice->setTaxAmount($grossAmount - $netAmount);
        $sellPrice->setNetAmount($netAmount);
        $sellPrice->setDiscountedTaxAmount($discountedGrossAmount - $discountedNetAmount);
        $sellPrice->setDiscountedNetAmount($discountedNetAmount);
    }
    
    protected function refreshProductVariantSellPrice(Variant $variant)
    {
        $product               = $variant->getProduct();
        $sellPrice             = $product->getSellPrice();
        $grossAmount           = $this->calculateAttributePrice($variant, $sellPrice->getGrossAmount());
        $discountedGrossAmount = $this->calculateAttributePrice($variant, $sellPrice->getDiscountedGrossAmount());
        $taxRate               = $product->getSellPriceTax()->getValue();
        $netAmount             = TaxHelper::calculateNetPrice($grossAmount, $taxRate);
        $discountedNetAmount   = TaxHelper::calculateNetPrice($discountedGrossAmount, $taxRate);
        
        $productAttributeSellPrice = $variant->getSellPrice();
        $productAttributeSellPrice->setTaxRate($taxRate);
        $productAttributeSellPrice->setTaxAmount($grossAmount - $netAmount);
        $productAttributeSellPrice->setGrossAmount($grossAmount);
        $productAttributeSellPrice->setNetAmount($netAmount);
        $productAttributeSellPrice->setDiscountedGrossAmount($discountedGrossAmount);
        $productAttributeSellPrice->setDiscountedTaxAmount($discountedGrossAmount - $discountedNetAmount);
        $productAttributeSellPrice->setDiscountedNetAmount($discountedNetAmount);
        $productAttributeSellPrice->setValidFrom($sellPrice->getValidFrom());
        $productAttributeSellPrice->setValidTo($sellPrice->getValidTo());
        $productAttributeSellPrice->setCurrency($sellPrice->getCurrency());
    }
    
    protected function calculateAttributePrice(Variant $variant, $amount)
    {
        $modifierType  = $variant->getModifierType();
        $modifierValue = $variant->getModifierValue();
        
        switch ($modifierType) {
            case '+':
                $amount = $amount + $modifierValue;
                break;
            case '-':
                $amount = $amount - $modifierValue;
                break;
            case '%':
                $amount = $amount * ($modifierValue / 100);
                break;
        }
        
        return round($amount, 2);
    }
    
    protected function refreshProductBuyPrices(Product $product)
    {
        $buyPrice    = $product->getBuyPrice();
        $grossAmount = $buyPrice->getGrossAmount();
        $taxRate     = $product->getBuyPriceTax()->getValue();
        $netAmount   = TaxHelper::calculateNetPrice($grossAmount, $taxRate);
        
        $buyPrice->setTaxRate($taxRate);
        $buyPrice->setTaxAmount($grossAmount - $netAmount);
        $buyPrice->setNetAmount($netAmount);
    }
    
    protected function syncProductStock(Product $product)
    {
        $trackStock       = $product->getTrackStock();
        $stock            = $this->getProductStock($product);
        $grossPrice       = $product->getSellPrice()->getFinalGrossAmount();
        $isStockAvailable = (true === $trackStock) ? $stock > 0 : 1;
        $isPriceNonZero   = $grossPrice > 0;
        
        if (false === $product->isEnabled() && true === $trackStock) {
            $product->setEnabled($isStockAvailable);
        }
        
        $product->setStock($stock);
        
        if (false === $isPriceNonZero) {
            $product->setEnabled(false);
        }
        
        if (0 === $product->getCategories()->count()) {
            $product->setEnabled(false);
        }
    }
    
    protected function syncVariantStock(Variant $variant)
    {
        $product          = $variant->getProduct();
        $trackStock       = $product->getTrackStock();
        $stock            = $variant->getStock();
        $isStockAvailable = (true === $trackStock) ? $stock > 0 : 1;
        $variant->setEnabled($isStockAvailable);
        
        if (false === $product->isEnabled()) {
            $variant->setEnabled(false);
        }
    }
    
    protected function getProductStock(Product $product): int
    {
        if (0 === $product->getVariants()->count()) {
            return $product->getStock();
        }
        
        $stock = 0;
        $product->getVariants()->map(function (Variant $variant) use (&$stock) {
            $stock = $stock + $variant->getStock();
        });
        
        return $stock;
    }
}

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

namespace WellCommerce\Bundle\CatalogBundle\Helper;

use WellCommerce\Bundle\AppBundle\Helper\CurrencyHelperInterface;
use WellCommerce\Bundle\CatalogBundle\Entity\Product;
use WellCommerce\Bundle\CatalogBundle\Entity\Variant;
use WellCommerce\Bundle\CatalogBundle\Entity\VariantOption;

/**
 * Class VariantHelper
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class VariantHelper implements VariantHelperInterface
{
    /**
     * @var CurrencyHelperInterface
     */
    protected $currencyHelper;
    
    /**
     * VariantHelper constructor.
     *
     * @param CurrencyHelperInterface $currencyHelper
     */
    public function __construct(CurrencyHelperInterface $currencyHelper)
    {
        $this->currencyHelper = $currencyHelper;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getVariants(Product $product): array
    {
        $variants = [];
        
        $product->getVariants()->map(function (Variant $variant) use (&$variants) {
            if ($variant->isEnabled()) {
                $this->extractVariantData($variant, $variants);
            }
        });
        
        return $variants;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getAttributes(Product $product): array
    {
        $attributes = [];
        
        $product->getVariants()->map(function (Variant $variant) use (&$attributes) {
            if ($variant->isEnabled()) {
                $this->extractAttributesData($variant, $attributes);
            }
        });
        
        return $attributes;
    }
    
    protected function extractAttributesData(Variant $variant, &$attributes)
    {
        $variant->getOptions()->map(function (VariantOption $variantOption) use (&$attributes) {
            $attribute                                                           = $variantOption->getAttribute();
            $attributeValue                                                      = $variantOption->getAttributeValue();
            $attributes[$attribute->getId()]['name']                             = $attribute->translate()->getName();
            $attributes[$attribute->getId()]['values'][$attributeValue->getId()] = $attributeValue->translate()->getName();
        });
    }
    
    protected function extractVariantData(Variant $variant, array &$variants)
    {
        $sellPrice    = $variant->getSellPrice();
        $baseCurrency = $sellPrice->getCurrency();
        $key          = $this->getVariantOptionsKey($variant);
        
        $variants[$key] = [
            'id'                 => $variant->getId(),
            'finalPriceGross'    => $this->currencyHelper->convertAndFormat($sellPrice->getFinalGrossAmount(), $baseCurrency),
            'sellPriceGross'     => $this->currencyHelper->convertAndFormat($sellPrice->getGrossAmount(), $baseCurrency),
            'discountPriceGross' => $this->currencyHelper->convertAndFormat($sellPrice->getDiscountedGrossAmount(), $baseCurrency),
            'stock'              => $variant->getStock(),
            'symbol'             => $variant->getSymbol(),
            'options'            => $this->getVariantOptions($variant),
        ];
    }
    
    protected function getVariantOptionsKey(Variant $variant): string
    {
        $options = [];
        
        $variant->getOptions()->map(function (VariantOption $variantOption) use (&$options) {
            $attribute      = $variantOption->getAttribute();
            $attributeValue = $variantOption->getAttributeValue();
            $key            = sprintf('%s:%s', $attribute->getId(), $attributeValue->getId());
            $options[$key]  = $key;
        });
        
        ksort($options);
        
        return implode(',', $options);
    }
    
    public function getVariantOptions(Variant $variant): array
    {
        $options = [];
        
        $variant->getOptions()->map(function (VariantOption $variantOption) use (&$options) {
            $attribute                                   = $variantOption->getAttribute();
            $attributeValue                              = $variantOption->getAttributeValue();
            $options[$attribute->translate()->getName()] = $attributeValue->translate()->getName();
        });
        
        return $options;
    }
}

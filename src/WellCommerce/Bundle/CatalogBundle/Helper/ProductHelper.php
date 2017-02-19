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

use WellCommerce\Bundle\CatalogBundle\Entity\Category;
use WellCommerce\Bundle\CatalogBundle\Entity\Product;
use WellCommerce\Bundle\OrderBundle\Context\ProductContext;
use WellCommerce\Bundle\OrderBundle\Provider\ShippingMethodProviderInterface;
use WellCommerce\Component\DataSet\Conditions\Condition\Eq;
use WellCommerce\Component\DataSet\Conditions\ConditionsCollection;
use WellCommerce\Component\DataSet\DataSetInterface;

/**
 * Class ProductHelper
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ProductHelper implements ProductHelperInterface
{
    /**
     * @var ShippingMethodProviderInterface
     */
    protected $shippingMethodProvider;
    
    /**
     * @var VariantHelperInterface
     */
    protected $variantHelper;
    
    /**
     * @var DataSetInterface
     */
    protected $dataset;
    
    /**
     * ProductHelper constructor.
     *
     * @param DataSetInterface                $dataset
     * @param ShippingMethodProviderInterface $shippingMethodProvider
     * @param VariantHelperInterface          $variantHelper
     */
    public function __construct(
        DataSetInterface $dataset,
        ShippingMethodProviderInterface $shippingMethodProvider,
        VariantHelperInterface $variantHelper
    ) {
        $this->shippingMethodProvider = $shippingMethodProvider;
        $this->variantHelper          = $variantHelper;
        $this->dataset                = $dataset;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getProductDefaultTemplateData(Product $product): array
    {
        $shippingMethodCosts = $this->shippingMethodProvider->getCosts(new ProductContext($product));
        $variants            = $this->variantHelper->getVariants($product);
        $attributes          = $this->variantHelper->getAttributes($product);
        
        return [
            'product'       => $product,
            'shippingCosts' => $shippingMethodCosts,
            'variants'      => json_encode($variants),
            'attributes'    => $attributes,
        ];
    }
    
    public function getProductRecommendationsForCategory(Category $category): array
    {
        $conditions = new ConditionsCollection();
        $conditions->add(new Eq('category', $category->getId()));
        
        return $this->dataset->getResult('datagrid', [
            'limit'      => 3,
            'order_by'   => 'name',
            'order_dir'  => 'asc',
            'conditions' => $conditions,
        ]);
    }
    
}

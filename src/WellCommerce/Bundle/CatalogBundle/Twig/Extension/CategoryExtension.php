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
namespace WellCommerce\Bundle\CatalogBundle\Twig\Extension;

use WellCommerce\Component\DataSet\Conditions\Condition\Eq;
use WellCommerce\Component\DataSet\Conditions\ConditionsCollection;
use WellCommerce\Component\DataSet\DataSetInterface;

/**
 * Class CategoryExtension
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class CategoryExtension extends \Twig_Extension
{
    /**
     * @var DataSetInterface
     */
    protected $dataset;
    
    /**
     * Constructor
     *
     * @param DataSetInterface $dataset
     */
    public function __construct(DataSetInterface $dataset)
    {
        $this->dataset = $dataset;
    }
    
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('categoriesTree', [$this, 'getCategoriesTree'], ['is_safe' => ['html']]),
        ];
    }
    
    public function getCategoriesTree(int $parent = null, $limit = 1000, $orderBy = 'hierarchy', $orderDir = 'asc'): array
    {
        $conditions = new ConditionsCollection();
        $conditions->add(new Eq('parent', $parent));
        
        return $this->dataset->getResult('flat_tree', [
            'limit'      => $limit,
            'order_by'   => $orderBy,
            'order_dir'  => $orderDir,
            'conditions' => $conditions,
        ]);
    }
    
    public function getName()
    {
        return 'category';
    }
}

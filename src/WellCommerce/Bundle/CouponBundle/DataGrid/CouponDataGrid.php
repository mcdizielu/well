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
namespace WellCommerce\Bundle\CouponBundle\DataGrid;

use WellCommerce\Bundle\CoreBundle\DataGrid\AbstractDataGrid;
use WellCommerce\Component\DataGrid\Column\Column;
use WellCommerce\Component\DataGrid\Column\ColumnCollection;
use WellCommerce\Component\DataGrid\Column\Options\Appearance;
use WellCommerce\Component\DataGrid\Column\Options\Filter;
use WellCommerce\Component\DataGrid\Column\Options\Sorting;

/**
 * Class CouponDataGrid
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class CouponDataGrid extends AbstractDataGrid
{
    public function configureColumns(ColumnCollection $collection)
    {
        $collection->add(new Column([
            'id'         => 'id',
            'caption'    => 'common.label.id',
            'sorting'    => new Sorting([
                'default_order' => Sorting::SORT_DIR_DESC,
            ]),
            'appearance' => new Appearance([
                'width'   => 90,
                'visible' => false,
            ]),
            'filter'     => new Filter([
                'type' => Filter::FILTER_BETWEEN,
            ]),
        ]));
        
        $collection->add(new Column([
            'id'      => 'name',
            'caption' => 'common.label.name',
        ]));
        
        $collection->add(new Column([
            'id'      => 'code',
            'caption' => 'common.label.code',
        ]));
        
        $collection->add(new Column([
            'id'      => 'discount',
            'caption' => 'common.label.discount',
        ]));
        
        $collection->add(new Column([
            'id'      => 'minimumOrderValue',
            'caption' => 'common.label.minimum_order_value',
        ]));
        
        $collection->add(new Column([
            'id'      => 'createdAt',
            'caption' => 'common.label.created_at',
        ]));
        
        $collection->add(new Column([
            'id'      => 'validFrom',
            'caption' => 'common.label.valid_from',
        ]));
        
        $collection->add(new Column([
            'id'      => 'validTo',
            'caption' => 'common.label.valid_to',
        ]));
    }
    
    public function getIdentifier(): string
    {
        return 'coupon';
    }
}

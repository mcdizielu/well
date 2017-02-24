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
namespace WellCommerce\Bundle\OrderBundle\DataGrid;

use WellCommerce\Bundle\CoreBundle\DataGrid\AbstractDataGrid;
use WellCommerce\Component\DataGrid\Column\Column;
use WellCommerce\Component\DataGrid\Column\ColumnCollection;
use WellCommerce\Component\DataGrid\Column\Options\Appearance;
use WellCommerce\Component\DataGrid\Column\Options\Filter;

/**
 * Class OrderStatusDataGrid
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class OrderStatusDataGrid extends AbstractDataGrid
{
    /**
     * @var OrderStatusGroupFilter
     */
    protected $orderStatusGroupFilter;
    
    public function __construct(OrderStatusGroupFilter $orderStatusGroupFilter)
    {
        $this->orderStatusGroupFilter = $orderStatusGroupFilter;
    }
    
    public function configureColumns(ColumnCollection $collection)
    {
        $collection->add(new Column([
            'id'         => 'id',
            'caption'    => 'common.label.id',
            'appearance' => new Appearance([
                'width'   => 90,
                'visible' => false,
            ]),
            'filter'     => new Filter([
                'type' => Filter::FILTER_BETWEEN,
            ]),
        ]));
        
        $collection->add(new Column([
            'id'         => 'name',
            'caption'    => 'common.label.name',
            'appearance' => new Appearance([
                'width' => 340,
            ]),
        ]));
        
        $collection->add(new Column([
            'id'         => 'groupName',
            'caption'    => 'common.label.group',
            'filter'     => new Filter([
                'type'    => Filter::FILTER_TREE,
                'filtered_column' => 'groupId',
                'options' => $this->orderStatusGroupFilter->getOptions(),
            ]),
            'appearance' => new Appearance([
                'width' => 140,
                'align' => Appearance::ALIGN_CENTER,
            ]),
        ]));
        
        $collection->add(new Column([
            'id'         => 'createdAt',
            'caption'    => 'common.label.created_at',
            'filter'     => new Filter([
                'type' => Filter::FILTER_BETWEEN,
            ]),
            'appearance' => new Appearance([
                'width' => 40,
                'align' => Appearance::ALIGN_CENTER,
            ]),
        ]));
    }
    
    public function getIdentifier(): string
    {
        return 'order_status';
    }
}

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
namespace WellCommerce\Bundle\CatalogBundle\DataGrid;

use WellCommerce\Bundle\CoreBundle\DataGrid\AbstractDataGrid;
use WellCommerce\Component\DataGrid\Column\Column;
use WellCommerce\Component\DataGrid\Column\ColumnCollection;
use WellCommerce\Component\DataGrid\Column\Options\Appearance;
use WellCommerce\Component\DataGrid\Column\Options\Filter;

/**
 * Class AttributeDataGrid
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class AttributeDataGrid extends AbstractDataGrid
{
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
            'id'      => 'name',
            'caption' => 'common.label.name',
            'appearance' => new Appearance([
                'width' => 140,
                'align' => Appearance::ALIGN_LEFT
            ]),
        ]));

        $collection->add(new Column([
            'id'         => 'groups',
            'caption'    => 'attribute.label.groups',
            'filter'     => new Filter([
                'type'    => Filter::FILTER_INPUT,
            ]),
            'appearance' => new Appearance([
                'width' => 140,
                'align' => Appearance::ALIGN_CENTER
            ]),
        ]));
    }
    
    public function getIdentifier(): string
    {
        return 'attribute';
    }
}

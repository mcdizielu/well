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

namespace WellCommerce\Bundle\InvoiceBundle\DataGrid;

use WellCommerce\Bundle\CoreBundle\DataGrid\AbstractDataGrid;
use WellCommerce\Component\DataGrid\Column\Column;
use WellCommerce\Component\DataGrid\Column\ColumnCollection;
use WellCommerce\Component\DataGrid\Column\Options\Appearance;
use WellCommerce\Component\DataGrid\Column\Options\Filter;
use WellCommerce\Component\DataGrid\Column\Options\Sorting;
use WellCommerce\Component\DataGrid\Configuration\EventHandler\ClickRowEventHandler;
use WellCommerce\Component\DataGrid\Configuration\EventHandler\DeleteGroupEventHandler;
use WellCommerce\Component\DataGrid\Configuration\EventHandler\DeleteRowEventHandler;
use WellCommerce\Component\DataGrid\Configuration\EventHandler\LoadEventHandler;
use WellCommerce\Component\DataGrid\DataGridInterface;
use WellCommerce\Component\DataGrid\Options\OptionsInterface;

/**
 * Class InvoiceDataGrid
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class InvoiceDataGrid extends AbstractDataGrid
{
    public function configureColumns(ColumnCollection $collection)
    {
        $collection->add(new Column([
            'id'         => 'id',
            'caption'    => 'invoice.label.id',
            'appearance' => new Appearance([
                'width'   => 40,
                'visible' => false,
                'align'   => Appearance::ALIGN_CENTER,
            ]),
            'filter'     => new Filter([
                'type' => Filter::FILTER_BETWEEN,
            ]),
        ]));
        
        $collection->add(new Column([
            'id'         => 'number',
            'caption'    => 'invoice.label.number',
            'filter'     => new Filter([
                'type' => Filter::FILTER_INPUT,
            ]),
            'sorting'    => new Sorting([
                'default_order' => Sorting::SORT_DIR_DESC,
            ]),
            'appearance' => new Appearance([
                'width' => 40,
                'align' => Appearance::ALIGN_CENTER,
            ]),
        ]));
    
        $collection->add(new Column([
            'id'         => 'orderNumber',
            'caption'    => 'invoice.label.order_number',
            'filter'     => new Filter([
                'type' => Filter::FILTER_INPUT,
            ]),
            'sorting'    => new Sorting([
                'default_order' => Sorting::SORT_DIR_DESC,
            ]),
            'appearance' => new Appearance([
                'width' => 40,
                'align' => Appearance::ALIGN_CENTER,
            ]),
        ]));
        
        $collection->add(new Column([
            'id'         => 'date',
            'caption'    => 'invoice.label.date',
            'filter'     => new Filter([
                'type' => Filter::FILTER_BETWEEN,
            ]),
            'appearance' => new Appearance([
                'width' => 40,
                'align' => Appearance::ALIGN_CENTER,
            ]),
        ]));
        
        $collection->add(new Column([
            'id'         => 'amountPaid',
            'caption'    => 'invoice.label.amount_paid',
            'filter'     => new Filter([
                'type' => Filter::FILTER_BETWEEN,
            ]),
            'appearance' => new Appearance([
                'width' => 40,
                'align' => Appearance::ALIGN_CENTER,
            ]),
        ]));
        
        $collection->add(new Column([
            'id'         => 'amountDue',
            'caption'    => 'invoice.label.amount_due',
            'filter'     => new Filter([
                'type' => Filter::FILTER_BETWEEN,
            ]),
            'appearance' => new Appearance([
                'width' => 40,
                'align' => Appearance::ALIGN_CENTER,
            ]),
        ]));
        
        $collection->add(new Column([
            'id'         => 'dueDate',
            'caption'    => 'invoice.label.due_date',
            'filter'     => new Filter([
                'type' => Filter::FILTER_BETWEEN,
            ]),
            'appearance' => new Appearance([
                'width' => 40,
                'align' => Appearance::ALIGN_CENTER,
            ]),
        ]));
        
        $collection->add(new Column([
            'id'         => 'createdAt',
            'caption'    => 'invoice.label.created_at',
            'filter'     => new Filter([
                'type' => Filter::FILTER_BETWEEN,
            ]),
            'appearance' => new Appearance([
                'width' => 40,
                'align' => Appearance::ALIGN_CENTER,
            ]),
        ]));
    }
    
    public function configureOptions(OptionsInterface $options)
    {
        $eventHandlers = $options->getEventHandlers();
        
        $eventHandlers->add(new LoadEventHandler([
            'function' => $this->getJavascriptFunctionName('load'),
            'route'    => $this->getActionUrl('grid'),
        ]));
        
        $eventHandlers->add(new ClickRowEventHandler([
            'function' => $this->getJavascriptFunctionName('click'),
            'route'    => $this->getActionUrl('edit'),
        ]));
        
        $eventHandlers->add(new DeleteRowEventHandler([
            'function'   => $this->getJavascriptFunctionName('delete'),
            'row_action' => DataGridInterface::ACTION_DELETE,
            'route'      => $this->getActionUrl('delete'),
        ]));
        
        $eventHandlers->add(new DeleteGroupEventHandler([
            'function'     => $this->getJavascriptFunctionName('delete_group'),
            'group_action' => DataGridInterface::ACTION_DELETE_GROUP,
            'route'        => $this->getActionUrl('delete_group'),
        ]));
    }
    
    public function getIdentifier(): string
    {
        return 'invoice';
    }
}

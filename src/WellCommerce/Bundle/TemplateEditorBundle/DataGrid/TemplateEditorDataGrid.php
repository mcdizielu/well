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

namespace WellCommerce\Bundle\TemplateEditorBundle\DataGrid;

use WellCommerce\Bundle\CoreBundle\DataGrid\AbstractDataGrid;
use WellCommerce\Component\DataGrid\Column\Column;
use WellCommerce\Component\DataGrid\Column\ColumnCollection;
use WellCommerce\Component\DataGrid\Column\Options\Appearance;
use WellCommerce\Component\DataGrid\Column\Options\Filter;
use WellCommerce\Component\DataGrid\Column\Options\Sorting;
use WellCommerce\Component\DataGrid\Configuration\EventHandler\ClickRowEventHandler;
use WellCommerce\Component\DataGrid\Configuration\EventHandler\EditRowEventHandler;
use WellCommerce\Component\DataGrid\Configuration\EventHandler\LoadEventHandler;
use WellCommerce\Component\DataGrid\DataGridInterface;
use WellCommerce\Component\DataGrid\Options\OptionsInterface;

/**
 * Class TemplateEditorDataGrid
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class TemplateEditorDataGrid extends AbstractDataGrid
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
            'id'      => 'folder',
            'caption' => 'theme.label.folder',
        ]));
    }
    
    
    public function configureOptions(OptionsInterface $options)
    {
        $eventHandlers = $options->getEventHandlers();
        
        $eventHandlers->add(new LoadEventHandler([
            'function' => $this->getJavascriptFunctionName('load'),
            'route'    => $this->getActionUrl('grid'),
        ]));
        
        $eventHandlers->add(new EditRowEventHandler([
            'function'   => $this->getJavascriptFunctionName('edit'),
            'row_action' => DataGridInterface::ACTION_EDIT,
            'route'      => $this->getActionUrl('edit'),
        ]));
        
        $eventHandlers->add(new ClickRowEventHandler([
            'function' => $this->getJavascriptFunctionName('click'),
            'route'    => $this->getActionUrl('edit'),
        ]));
    }
    
    public function getIdentifier(): string
    {
        return 'template_editor';
    }
}

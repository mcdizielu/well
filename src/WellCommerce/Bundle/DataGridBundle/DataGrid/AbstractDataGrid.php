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
namespace WellCommerce\Bundle\DataGridBundle\DataGrid;

use WellCommerce\Component\DataGrid\Column\ColumnCollection;
use WellCommerce\Component\DataGrid\Configuration\EventHandler\ClickRowEventHandler;
use WellCommerce\Component\DataGrid\Configuration\EventHandler\DeleteGroupEventHandler;
use WellCommerce\Component\DataGrid\Configuration\EventHandler\DeleteRowEventHandler;
use WellCommerce\Component\DataGrid\Configuration\EventHandler\EditRowEventHandler;
use WellCommerce\Component\DataGrid\Configuration\EventHandler\LoadEventHandler;
use WellCommerce\Component\DataGrid\DataGridInterface;
use WellCommerce\Component\DataGrid\Options\OptionsInterface;

/**
 * Class AbstractDataGrid
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
abstract class AbstractDataGrid implements DataGridInterface
{
    /**
     * @var string
     */
    protected $identifier;
    
    /**
     * @var ColumnCollection
     */
    protected $columns;
    
    /**
     * @var OptionsInterface
     */
    protected $options;
    
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
    
    protected function getActionUrl(string $actionName): string
    {
        return sprintf('admin.%s.%s', $this->getIdentifier(), $actionName);
    }
    
    protected function getJavascriptFunctionName(string $name): string
    {
        $functionName = sprintf('%s%s', $name, ucfirst($this->getIdentifier()));
        $functionName = ucwords(str_replace(['-', '_'], ' ', $functionName));
        $functionName = str_replace(' ', '', $functionName);
        
        return lcfirst($functionName);
    }
    
    public function setColumns(ColumnCollection $columns)
    {
        $this->columns = $columns;
    }
    
    public function getColumns(): ColumnCollection
    {
        return $this->columns;
    }
    
    public function setOptions(OptionsInterface $options)
    {
        $this->options = $options;
    }
    
    public function getOptions(): OptionsInterface
    {
        return $this->options;
    }
}

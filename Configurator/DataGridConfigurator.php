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

namespace WellCommerce\Component\DataGrid\Configurator;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use WellCommerce\Component\DataGrid\Column\ColumnCollection;
use WellCommerce\Component\DataGrid\DataGridInterface;
use WellCommerce\Component\DataGrid\Event\DataGridEvent;
use WellCommerce\Component\DataGrid\Options\Options;

/**
 * Class DataGridConfigurator
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class DataGridConfigurator implements DataGridConfiguratorInterface
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;
    
    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }
    
    public function configure(DataGridInterface $dataGrid)
    {
        $columns = new ColumnCollection();
        $options = new Options();
        
        $dataGrid->configureColumns($columns);
        $dataGrid->configureOptions($options);
        
        $dataGrid->setColumns($columns);
        $dataGrid->setOptions($options);
        
        $eventName = sprintf('%s.%s', $dataGrid->getIdentifier(), DataGridEvent::EVENT_SUFFIX);
        $this->eventDispatcher->dispatch($eventName, new DataGridEvent($dataGrid));
    }
}

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

namespace WellCommerce\Component\DataGrid;

use WellCommerce\Component\DataGrid\Column\ColumnCollection;
use WellCommerce\Component\DataGrid\Options\OptionsInterface;

/**
 * Interface DataGridInterface
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
interface DataGridInterface
{
    const ACTION_DELETE_GROUP = 'GF_Datagrid.ACTION_DELETE_GROUP';
    const ACTION_VIEW         = 'GF_Datagrid.ACTION_VIEW';
    const ACTION_EDIT         = 'GF_Datagrid.ACTION_EDIT';
    const ACTION_DELETE       = 'GF_Datagrid.ACTION_DELETE';
    const REDIRECT            = 'GF_Datagrid.Redirect';
    const OPERATOR_NE         = '!=';
    const OPERATOR_LE         = '<=';
    const OPERATOR_GE         = '>=';
    const OPERATOR_LIKE       = 'LIKE';
    const OPERATOR_IN         = '=';
    const GF_NULL             = 'GF.NULL';
    
    public function getIdentifier(): string;
    
    public function configureColumns(ColumnCollection $columns);
    
    public function configureOptions(OptionsInterface $options);
    
    public function setColumns(ColumnCollection $columns);
    
    public function getColumns(): ColumnCollection;
    
    public function setOptions(OptionsInterface $options);
    
    public function getOptions(): OptionsInterface;
}

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

namespace WellCommerce\Bundle\InvoiceBundle\DataSet\Admin;

use Doctrine\ORM\QueryBuilder;
use WellCommerce\Bundle\CoreBundle\DataSet\AbstractDataSet;
use WellCommerce\Component\DataSet\Configurator\DataSetConfiguratorInterface;

/**
 * Class InvoiceDataSet
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class InvoiceDataSet extends AbstractDataSet
{
    public function getIdentifier(): string
    {
        return 'admin.invoice';
    }
    
    public function configureOptions(DataSetConfiguratorInterface $configurator)
    {
        $configurator->setColumns([
            'id'          => 'invoice.id',
            'number'      => 'invoice.number',
            'orderNumber' => 'orders.number',
            'createdAt'   => 'invoice.createdAt',
            'date'        => 'invoice.date',
            'dueDate'     => 'invoice.dueDate',
            'amountPaid'  => 'invoice.amountPaid',
            'amountDue'   => 'invoice.amountDue',
            'shop'        => 'IDENTITY(invoice.shop)',
        ]);
        
        $configurator->setColumnTransformers([
            'createdAt' => $this->manager->createTransformer('date', ['format' => 'Y-m-d H:i:s']),
            'date'      => $this->manager->createTransformer('date', ['format' => 'Y-m-d H:i:s']),
            'dueDate'   => $this->manager->createTransformer('date', ['format' => 'Y-m-d H:i:s']),
        ]);
    }
    
    protected function createQueryBuilder(): QueryBuilder
    {
        $queryBuilder = $this->repository->getQueryBuilder();
        $queryBuilder->leftJoin('invoice.order', 'orders');
        $queryBuilder->groupBy('invoice.id');
        $queryBuilder->where($queryBuilder->expr()->eq('invoice.shop', $this->getShopStorage()->getCurrentShopIdentifier()));
        
        return $queryBuilder;
    }
}

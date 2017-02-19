<?php

namespace WellCommerce\Bundle\OrderBundle\DataGrid;

use WellCommerce\Bundle\CoreBundle\Doctrine\Repository\RepositoryInterface;
use WellCommerce\Bundle\OrderBundle\Entity\OrderStatus;

/**
 * Class OrderStatusFilter
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class OrderStatusFilter
{
    private $repository;
    
    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }
    
    public function getOptions(): array
    {
        $options  = [];
        $statuses = $this->repository->getCollection();
        $statuses->map(function (OrderStatus $status) use (&$options) {
            $options[] = [
                'id'          => $status->getId(),
                'name'        => $status->translate()->getName(),
                'hasChildren' => false,
                'parent'      => null,
                'weight'      => $status->getId(),
            ];
        });
        
        return $options;
    }
}

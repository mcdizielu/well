<?php

namespace WellCommerce\Bundle\OrderBundle\DataGrid;

use WellCommerce\Bundle\CoreBundle\Doctrine\Repository\RepositoryInterface;
use WellCommerce\Bundle\OrderBundle\Entity\OrderStatusGroup;

/**
 * Class OrderStatusGroupFilter
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class OrderStatusGroupFilter
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
        $statuses->map(function (OrderStatusGroup $status) use (&$options) {
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

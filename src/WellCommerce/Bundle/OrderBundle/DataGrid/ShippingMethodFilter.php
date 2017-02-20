<?php

namespace WellCommerce\Bundle\OrderBundle\DataGrid;

use WellCommerce\Bundle\CoreBundle\Doctrine\Repository\RepositoryInterface;
use WellCommerce\Bundle\OrderBundle\Entity\ShippingMethod;

/**
 * Class ShippingMethodFilter
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class ShippingMethodFilter
{
    private $repository;
    
    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }
    
    public function getOptions(): array
    {
        $options = [];
        $methods = $this->repository->getCollection();
        $methods->map(function (ShippingMethod $method) use (&$options) {
            $options[] = [
                'id'          => $method->getId(),
                'name'        => $method->translate()->getName(),
                'hasChildren' => false,
                'parent'      => null,
                'weight'      => $method->getId(),
            ];
        });
        
        return $options;
    }
}

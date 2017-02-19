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

namespace WellCommerce\Bundle\CatalogBundle\DataSet\Transformer;

use WellCommerce\Bundle\CatalogBundle\Entity\Product;
use WellCommerce\Bundle\CatalogBundle\Entity\ProductDistinction;
use WellCommerce\Bundle\CoreBundle\Doctrine\Repository\RepositoryInterface;
use WellCommerce\Component\DataSet\Transformer\AbstractDataSetTransformer;

/**
 * Class DistinctionsTransformer
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class DistinctionsTransformer extends AbstractDataSetTransformer
{
    /**
     * @var RepositoryInterface
     */
    private $repository;
    
    /**
     * DistinctionsTransformer constructor.
     *
     * @param RepositoryInterface $repository
     */
    public function __construct(RepositoryInterface $repository)
    {
        $this->repository = $repository;
    }
    
    public function transformValue($productId)
    {
        $distinctions = [];
        $product      = $this->repository->find($productId);
        if ($product instanceof Product) {
            $product->getDistinctions()->map(function (ProductDistinction $distinction) use (&$distinctions) {
                if ($distinction->isValid()) {
                    $distinctions[] = [
                        'name'     => $distinction->getStatus()->translate()->getName(),
                        'symbol'   => $distinction->getStatus()->getSymbol(),
                        'cssClass' => $distinction->getStatus()->translate()->getCssClass(),
                    ];
                }
            });
        }
        
        return $distinctions;
    }
}

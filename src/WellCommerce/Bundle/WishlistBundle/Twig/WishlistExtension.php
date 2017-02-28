<?php

namespace WellCommerce\Bundle\WishlistBundle\Twig;

use WellCommerce\Bundle\AppBundle\Entity\Client;
use WellCommerce\Bundle\CatalogBundle\DataSet\Front\ProductDataSet;
use WellCommerce\Bundle\WishlistBundle\Entity\Wishlist;
use WellCommerce\Bundle\WishlistBundle\Repository\WishlistRepositoryInterface;
use WellCommerce\Component\DataSet\Conditions\Condition\In;
use WellCommerce\Component\DataSet\Conditions\ConditionsCollection;

/**
 * Class WishlistExtension
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class WishlistExtension extends \Twig_Extension
{
    /**
     * @var ProductDataSet
     */
    protected $dataSet;
    
    /**
     * @var WishlistRepositoryInterface
     */
    protected $repository;
    
    public function __construct(ProductDataSet $dataSet, WishlistRepositoryInterface $repository)
    {
        $this->dataSet    = $dataSet;
        $this->repository = $repository;
    }
    
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('wishlist', [$this, 'getWishlist'], ['is_safe' => ['html']]),
        ];
    }
    
    public function getWishlist(Client $client = null): array
    {
        return $this->dataSet->getResult('array', [
            'order_by'   => 'name',
            'order_dir'  => 'asc',
            'conditions' => $this->createConditions($client),
        ]);
    }
    
    private function createConditions(Client $client = null): ConditionsCollection
    {
        $conditions = new ConditionsCollection();
        
        if (null !== $client) {
            $wishlist   = $this->repository->getClientWishlistCollection($client);
            $productIds = [];
            
            $wishlist->map(function (Wishlist $wishlist) use (&$productIds) {
                $productIds[] = $wishlist->getProduct()->getId();
            });
            
            $conditions->add(new In('id', $productIds));
            
            return $conditions;
        }
        
        $conditions->add(new In('id', 0));
        
        return $conditions;
    }
    
    public function getName()
    {
        return 'wishlist';
    }
}

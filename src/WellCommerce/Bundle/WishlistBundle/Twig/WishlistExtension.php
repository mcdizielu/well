<?php

namespace WellCommerce\Bundle\WishlistBundle\Twig;

use WellCommerce\Bundle\AppBundle\Entity\Client;
use WellCommerce\Bundle\WishlistBundle\Repository\WishlistRepositoryInterface;

/**
 * Class WishlistExtension
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class WishlistExtension extends \Twig_Extension
{
    /**
     * @var WishlistRepositoryInterface
     */
    protected $repository;
    
    public function __construct(WishlistRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }
    
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('wishlistCount', [$this, 'getWishlistCount'], ['is_safe' => ['html']]),
        ];
    }
    
    public function getWishlistCount(Client $client = null): int
    {
        if (null === $client) {
            return 0;
        }
        
        return $this->repository->getClientWishlistCollection($client)->count();
    }
    
    public function getName()
    {
        return 'wishlist';
    }
}

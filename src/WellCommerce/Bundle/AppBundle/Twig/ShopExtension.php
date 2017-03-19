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
namespace WellCommerce\Bundle\AppBundle\Twig;

use WellCommerce\Bundle\AppBundle\Entity\Shop;
use WellCommerce\Bundle\AppBundle\Storage\ShopStorageInterface;
use WellCommerce\Component\DataSet\DataSetInterface;

/**
 * Class ShopExtension
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ShopExtension extends \Twig_Extension
{
    /**
     * @var ShopStorageInterface
     */
    protected $shopStorage;
    
    /**
     * @var DataSetInterface
     */
    protected $shopDataset;
    
    /**
     * ShopExtension constructor.
     *
     * @param ShopStorageInterface $shopStorage
     * @param DataSetInterface     $shopDataset
     */
    public function __construct(ShopStorageInterface $shopStorage, DataSetInterface $shopDataset)
    {
        $this->shopStorage = $shopStorage;
        $this->shopDataset = $shopDataset;
    }
    
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('currentShop', [$this, 'getCurrentShop'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('shops', [$this, 'getShops'], ['is_safe' => ['html']]),
        ];
    }
    
    public function getCurrentShop(): Shop
    {
        return $this->shopStorage->getCurrentShop();
    }
    
    public function getShops(): array
    {
        return $this->shopDataset->getResult('select');
    }
    
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'shop';
    }
}

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

namespace WellCommerce\Bundle\AppBundle\DataFixtures\ORM;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use WellCommerce\Bundle\AppBundle\Entity\Dimension;
use WellCommerce\Bundle\AppBundle\Entity\DiscountablePrice;
use WellCommerce\Bundle\AppBundle\Entity\Price;
use WellCommerce\Bundle\CatalogBundle\Entity\Category;
use WellCommerce\Bundle\CatalogBundle\Entity\Product;
use WellCommerce\Bundle\CatalogBundle\Entity\ProductDistinction;
use WellCommerce\Bundle\CatalogBundle\Entity\ProductPhoto;
use WellCommerce\Bundle\CatalogBundle\Entity\ProductTranslation;
use WellCommerce\Bundle\CoreBundle\Doctrine\Fixtures\AbstractDataFixture;
use WellCommerce\Bundle\CoreBundle\Helper\Helper;

/**
 * Class LoadProductData
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class LoadProductData extends AbstractDataFixture
{
    public static $samples = [];
    
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        if (!$this->isEnabled()) {
            return;
        }
        
        $limit = $this->container->getParameter('fixtures_product_limit');
        $faker = $this->getFakerGenerator();
        $names = [];
        
        for ($i = 0; $i < $limit; $i++) {
            $sentence     = $faker->unique()->sentence(3);
            $name         = substr($sentence, 0, strlen($sentence) - 1);
            $names[$name] = $name;
        }
        
        $products = new ArrayCollection();
        foreach ($names as $name) {
            $products->add($this->createRandomProduct($name, $manager));
        }
        
        $manager->flush();
        
        $products->map(function (Product $product) {
            $product->getCategories()->map(function (Category $category) {
                $category->setProductsCount($category->getProducts()->count());
                $category->setChildrenCount($category->getChildren()->count());
            });
        });
        
        $this->createLayoutBoxes($manager, [
            'product_info' => [
                'type' => 'ProductInfo',
                'name' => 'Product',
            ],
        ]);
        
        $manager->flush();
    }
    
    protected function createRandomProduct(string $name, ObjectManager $manager)
    {
        $faker            = $this->getFakerGenerator();
        $shortDescription = $faker->text(100);
        $description      = $faker->text(1000);
        $sku              = $faker->creditCardNumber();
        $shop             = $this->getReference('shop');
        $currency         = $this->randomizeSamples('currency', LoadCurrencyData::$samples);
        $producer         = $this->randomizeSamples('producer', LoadProducerData::$samples);
        $availability     = $this->randomizeSamples('availability', LoadAvailabilityData::$samples);
        $categories       = $this->randomizeSamples('category', $s = LoadCategoryData::$samples, rand(2, 4));
        $tax              = $this->randomizeSamples('tax', LoadTaxData::$samples);
        $unit             = $this->randomizeSamples('unit', LoadUnitData::$samples);
        
        $dimension = new Dimension();
        $dimension->setDepth(rand(10, 100));
        $dimension->setHeight(rand(10, 100));
        $dimension->setWidth(rand(10, 100));
        
        $buyPrice = new Price();
        $buyPrice->setGrossAmount(rand(50, 80));
        $buyPrice->setCurrency($currency->getCode());
        
        $sellPrice = new DiscountablePrice();
        $sellPrice->setGrossAmount($price = rand(100, 200));
        $sellPrice->setCurrency($currency->getCode());
        
        $sellPrice->setDiscountedGrossAmount($price * (rand(80, 95) / 100));
        $sellPrice->setValidFrom(new \DateTime());
        $sellPrice->setValidTo((new \DateTime())->modify('+30 days'));
        
        /** @var Product $product */
        $product = $this->get('product.factory')->create();
        $product->setSku($sku);
        $product->setHierarchy(rand(0, 10));
        $product->setEnabled(true);
        $product->setAvailability($availability);
        $product->setBuyPrice($buyPrice);
        $product->setBuyPriceTax($tax);
        $product->setSellPrice($sellPrice);
        $product->setSellPriceTax($tax);
        $product->setCategories($categories);
        $product->addShop($shop);
        
        foreach ($this->getLocales() as $locale) {
            /** @var ProductTranslation $translation */
            $translation = $product->translate($locale->getCode());
            $translation->setName($name);
            $translation->setSlug(Helper::urlize($name));
            $translation->setShortDescription($shortDescription);
            $translation->setDescription($description);
        }
        
        $product->mergeNewTranslations();
        
        $product->setProductPhotos($this->getPhotos($product, $manager));
        $product->setProducer($producer);
        $product->setStock(rand(0, 1000));
        $product->setUnit($unit);
        $product->setDimension($dimension);
        $product->setTrackStock(true);
        $product->setPackageSize(1);
        $product->setWeight(rand(0, 5));
        
        $distinctions = new ArrayCollection();
        
        $distinction = new ProductDistinction();
        $distinction->setProduct($product);
        $distinction->setStatus($this->getReference('product_status_bestseller'));
        $manager->persist($distinction);
        $distinctions->add($distinction);
        
        $distinction = new ProductDistinction();
        $distinction->setProduct($product);
        $distinction->setStatus($this->getReference('product_status_featured'));
        $manager->persist($distinction);
        $distinctions->add($distinction);
        
        $distinction = new ProductDistinction();
        $distinction->setProduct($product);
        $distinction->setStatus($this->getReference('product_status_novelty'));
        $manager->persist($distinction);
        $distinctions->add($distinction);
        
        $distinction = new ProductDistinction();
        $distinction->setProduct($product);
        $distinction->setStatus($this->getReference('product_status_promotion'));
        $manager->persist($distinction);
        $distinctions->add($distinction);
        
        $product->setDistinctions($distinctions);
        
        $manager->persist($product);
        
        return $product;
    }
    
    protected function getPhotos(Product $product, ObjectManager $manager)
    {
        $productPhotos = new ArrayCollection();
        $mediaFiles    = $this->randomizeSamples('photo', LoadMediaData::$samples, 3);
        $isMainPhoto   = true;
        
        foreach ($mediaFiles as $media) {
            $productPhoto = new ProductPhoto();
            $productPhoto->setHierarchy(0);
            $productPhoto->setMainPhoto($isMainPhoto);
            $productPhoto->setPhoto($media);
            $productPhoto->setProduct($product);
            $manager->persist($productPhoto);
            
            if ($isMainPhoto) {
                $product->setPhoto($media);
                $isMainPhoto = false;
            }
            
            $productPhotos->add($productPhoto);
        }
        
        return $productPhotos;
    }
}

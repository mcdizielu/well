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

namespace WellCommerce\Bundle\StandardEditionBundle\DataFixtures\ORM;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use WellCommerce\Bundle\AppBundle\Entity\Dimension;
use WellCommerce\Bundle\AppBundle\Entity\DiscountablePrice;
use WellCommerce\Bundle\AppBundle\Entity\Price;
use WellCommerce\Bundle\CatalogBundle\Entity\Attribute;
use WellCommerce\Bundle\CatalogBundle\Entity\AttributeValue;
use WellCommerce\Bundle\CatalogBundle\Entity\Category;
use WellCommerce\Bundle\CatalogBundle\Entity\Product;
use WellCommerce\Bundle\CatalogBundle\Entity\ProductDistinction;
use WellCommerce\Bundle\CatalogBundle\Entity\ProductPhoto;
use WellCommerce\Bundle\CatalogBundle\Entity\ProductTranslation;
use WellCommerce\Bundle\CatalogBundle\Entity\Variant;
use WellCommerce\Bundle\CatalogBundle\Entity\VariantOption;
use WellCommerce\Bundle\CoreBundle\Helper\Helper;
use WellCommerce\Bundle\StandardEditionBundle\DataFixtures\AbstractDataFixture;

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
        
        $price     = $faker->randomFloat(2, 1, 500);
        $sellPrice = new DiscountablePrice();
        $sellPrice->setGrossAmount($price);
        $sellPrice->setCurrency($currency->getCode());
        $sellPrice->setDiscountedGrossAmount($price * rand(0.8, 0.95));
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
            $translation->getMeta()->setTitle($name);
            $translation->getMeta()->setKeywords($shortDescription);
            $translation->getMeta()->setDescription($description);
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
        
        $hasVariants = rand(0, 1);
        if ($hasVariants) {
            $variants = new ArrayCollection();
            
            $input = [
                'size'   => LoadAttributeData::$sizes,
                'colour' => LoadAttributeData::$colours,
            ];
            
            $cartesianProduct = $this->generateCartesianProduct($input);
            foreach ($cartesianProduct as $key => $values) {
                /** @var AttributeValue $sizeValue */
                /** @var AttributeValue $colourValue */
                /** @var Attribute $sizeAttribute */
                /** @var Attribute $colourAttribute */
                $sizeValue       = $this->getReference('attribute_value_' . $values['size']);
                $colourValue     = $this->getReference('attribute_value_' . $values['colour']);
                $sizeAttribute   = $this->getReference('attribute_size');
                $colourAttribute = $this->getReference('attribute_colour');
                
                $variant = new Variant();
                $variant->setHierarchy(0);
                $variant->setWeight($product->getWeight());
                $variant->setModifierType('%');
                $variant->setModifierValue(rand(80, 200));
                $variant->setSellPrice(clone $sellPrice);
                $variant->setSymbol($product->getSku() . '-' . rand(0, 100));
                $variant->setStock(rand(0, 100));
                $variant->setEnabled(true);
                $variant->setProduct($product);
                
                $option = new VariantOption();
                $option->setAttribute($sizeAttribute);
                $option->setAttributeValue($sizeValue);
                $option->setVariant($variant);
                $variant->getOptions()->add($option);
                
                $option = new VariantOption();
                $option->setAttribute($colourAttribute);
                $option->setAttributeValue($colourValue);
                $option->setVariant($variant);
                $variant->getOptions()->add($option);
                
                $variants->add($variant);
                
                $product->setAttributeGroup($colourAttribute->getGroups()->first());
            }
            
            $product->setVariants($variants);
        }
        
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
    
    protected function generateCartesianProduct(array $input): array
    {
        $result = [];
        
        while (list($key, $values) = each($input)) {
            if (empty($values)) {
                continue;
            }
            
            if (empty($result)) {
                foreach ($values as $value) {
                    $result[] = [$key => $value];
                }
            } else {
                $append = [];
                
                foreach ($result as &$product) {
                    $product[$key] = array_shift($values);
                    $copy          = $product;
                    
                    foreach ($values as $item) {
                        $copy[$key] = $item;
                        $append[]   = $copy;
                    }
                    
                    array_unshift($values, $product[$key]);
                }
                
                $result = array_merge($result, $append);
            }
        }
        
        return $result;
    }
}

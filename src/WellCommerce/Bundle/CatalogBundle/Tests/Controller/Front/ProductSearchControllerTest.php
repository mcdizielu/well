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

namespace WellCommerce\Bundle\CatalogBundle\Tests\Controller\Front;

use Doctrine\Common\Collections\ArrayCollection;
use WellCommerce\Bundle\CatalogBundle\Entity\Product;
use WellCommerce\Bundle\CoreBundle\Test\Controller\Admin\AbstractAdminControllerTestCase;
use WellCommerce\Bundle\SearchBundle\Manager\SearchManagerInterface;
use WellCommerce\Component\Search\Request\SearchRequest;

/**
 * Class ProductSearchControllerTest
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ProductSearchControllerTest extends AbstractAdminControllerTestCase
{
    public function testIndexAction()
    {
        /** @var SearchManagerInterface $manager */
        $manager    = $this->container->get('search.manager');
        $collection = $this->container->get('product.repository')->getCollection();
        $type       = $manager->getType('product');
        
        $collection->map(function (Product $product) use ($type, $manager) {
            $field = $type->getFields()->get('sku');
            $field->setValue($product->getSku());
            $fields = new ArrayCollection([$field]);
            
            $searchRequest = new SearchRequest($type, $fields, '', 'en');
            $result        = $manager->search($searchRequest);
            $this->assertGreaterThanOrEqual(1, count($result));
            $this->assertContains($product->getId(), $result);
        });
        
        $collection->map(function (Product $product) use ($type, $manager) {
            $field = $type->getFields()->get('name');
            $field->setValue($product->translate()->getName());
            $fields = new ArrayCollection([$field]);
            
            $searchRequest = new SearchRequest($type, $fields, '', 'en');
            $result        = $manager->search($searchRequest);
            $this->assertGreaterThanOrEqual(1, count($result));
            $this->assertContains($product->getId(), $result);
        });
        
        $collection->map(function (Product $product) use ($type, $manager) {
            $fields = new ArrayCollection();
            
            $field = $type->getFields()->get('name');
            $field->setValue('');
            $fields->add($field);
            
            $field = $type->getFields()->get('sku');
            $field->setValue($product->getSku());
            $fields->add($field);
            
            $searchRequest = new SearchRequest($type, $fields, '', 'en');
            $result        = $manager->search($searchRequest);
            $this->assertGreaterThanOrEqual(1, count($result));
            $this->assertContains($product->getId(), $result);
        });
        
        $collection->map(function (Product $product) use ($type, $manager) {
            $fields = new ArrayCollection();
            
            $field = $type->getFields()->get('name');
            $field->setValue($product->translate()->getName());
            $fields->add($field);
            
            $field = $type->getFields()->get('sku');
            $field->setValue($product->getSku());
            $fields->add($field);
            
            $searchRequest = new SearchRequest($type, $fields, '', 'en');
            $result        = $manager->search($searchRequest);
            $this->assertGreaterThanOrEqual(1, count($result));
            $this->assertContains($product->getId(), $result);
        });
    }
    
    public function testQuickSearchAction()
    {
        /** @var SearchManagerInterface $manager */
        $manager    = $this->container->get('search.manager');
        $collection = $this->container->get('product.repository')->getCollection();
        $type       = $manager->getType('product');
        
        $collection->map(function (Product $product) use ($type, $manager) {
            $searchRequest = new SearchRequest($type, new ArrayCollection(), $product->getSku(), 'en');
            $result        = $manager->search($searchRequest);
            $this->assertGreaterThanOrEqual(1, count($result));
            $this->assertContains($product->getId(), $result);
        });
        
        $collection->map(function (Product $product) use ($type, $manager) {
            $searchRequest = new SearchRequest($type, new ArrayCollection(), $product->translate()->getName(), 'en');
            $result        = $manager->search($searchRequest);
            $this->assertGreaterThanOrEqual(1, count($result));
            $this->assertContains($product->getId(), $result);
        });
    }
}

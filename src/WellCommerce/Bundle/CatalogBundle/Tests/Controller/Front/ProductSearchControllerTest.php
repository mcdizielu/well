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

use WellCommerce\Bundle\CatalogBundle\Entity\Product;
use WellCommerce\Bundle\CoreBundle\Test\Controller\Admin\AbstractAdminControllerTestCase;

/**
 * Class ProductSearchControllerTest
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ProductSearchControllerTest extends AbstractAdminControllerTestCase
{
    public function testIndexAction()
    {
        $collection = $this->container->get('product.repository')->getCollection();

        $collection->map(function (Product $product) {
            $url     = $this->generateUrl('front.product_search.index', ['sku' => $product->getSku()]);
            $crawler = $this->client->request('GET', $url);

            $this->assertTrue($this->client->getResponse()->isSuccessful());

            $this->assertGreaterThan(0, $crawler->filter('html:contains("' . $product->translate()->getName() . '")')->count());
        });
    }
    
    public function testQuickSearchAction()
    {
        $collection = $this->container->get('product.repository')->getCollection();
        
        $collection->map(function (Product $product) {
            $url     = $this->generateUrl('front.product_search.quick', ['phrase' => $product->getSku()]);
            
            $this->client->request('GET', $url);
            $json = $this->client->getResponse()->getContent();
            
            $this->assertTrue($this->client->getResponse()->isSuccessful());
            $this->assertJson($json);
            $this->assertContains($product->translate()->getName(), $json);
            $result = json_decode($json, true);
            $this->assertArrayHasKey('liveSearchContent', $result);
        });
    }
}

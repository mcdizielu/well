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

namespace WellCommerce\Bundle\OrderBundle\Tests\Controller\Front;

use Doctrine\Common\Collections\Collection;
use WellCommerce\Bundle\CatalogBundle\Entity\Product;
use WellCommerce\Bundle\CoreBundle\Test\Controller\Front\AbstractFrontControllerTestCase;

/**
 * Class CartControllerTest
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class CartControllerTest extends AbstractFrontControllerTestCase
{
    public function testAddAction()
    {
        /** @var Collection $collection */
        $collection = $this->container->get('product.repository')->getCollection();
        
        $collection->map(function (Product $product) {
            $url     = $this->generateUrl('front.cart.add', ['id' => $product->getId(), 'variant' => null, 'quantity' => 1]);
            $crawler = $this->client->request('GET', $url);
            
            if ($product->getVariants()->count()) {
                $redirectUrl = $this->generateUrl('front.product.view', ['id' => $product->getId()]);
                $this->assertTrue($this->client->getResponse()->isRedirect($redirectUrl));
            } else {
                $this->assertTrue($this->client->getResponse()->isSuccessful());
                $this->assertJson($this->client->getResponse()->getContent());
            }
        });
    }
    
    public function testIndexAction()
    {
        $url     = $this->generateUrl('front.cart.index');
        $crawler = $this->client->request('GET', $url);
        $this->assertTrue($this->client->getResponse()->isSuccessful());
    }
}

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

namespace WellCommerce\Bundle\LastViewedBundle\Controller\Box;

use Symfony\Component\HttpFoundation\Response;
use WellCommerce\Bundle\CatalogBundle\Entity\Product;
use WellCommerce\Bundle\CoreBundle\Controller\Box\AbstractBoxController;
use WellCommerce\Bundle\LastViewedBundle\Entity\LastViewed;
use WellCommerce\Bundle\LastViewedBundle\Manager\LastViewedManager;
use WellCommerce\Component\DataSet\Conditions\Condition\In;
use WellCommerce\Component\DataSet\Conditions\ConditionsCollection;
use WellCommerce\Component\Layout\Collection\LayoutBoxSettingsCollection;

/**
 * Class LastViewedBoxController
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class LastViewedBoxController extends AbstractBoxController
{
    /**
     * @var LastViewedManager
     */
    protected $manager;
    
    public function indexAction(LayoutBoxSettingsCollection $boxSettings): Response
    {
        $dataset        = $this->get('product.dataset.front');
        $currentProduct = $this->getProductStorage()->getCurrentProduct();
        
        $products = $dataset->getResult('array', [
            'limit'      => $boxSettings->getParam('limit', 4),
            'page'       => 1,
            'order_by'   => 'hierarchy',
            'order_dir'  => 'asc',
            'conditions' => $this->createConditionsCollection($currentProduct),
        ]);
        
        return $this->displayTemplate('index', [
            'dataset'     => $products,
            'boxSettings' => $boxSettings,
        ]);
    }
    
    protected function createConditionsCollection(Product $product = null): ConditionsCollection
    {
        $identifiers        = [];
        $client             = $this->getSecurityHelper()->getCurrentClient();
        $sessionId          = $this->getRequestHelper()->getSessionId();
        $lastViewedProducts = $this->manager->getLastViewedProducts($client, $sessionId, $product);
        $lastViewedProducts->map(function (LastViewed $lastViewed) use (&$identifiers, $product) {
            $identifiers[] = $lastViewed->getProduct()->getId();
        });
        
        if (0 === count($identifiers)) {
            $identifiers = [0];
        }
        
        $conditions = new ConditionsCollection();
        $conditions->add(new In('id', $identifiers));
        
        return $conditions;
    }
}

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

use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\Response;
use WellCommerce\Bundle\CatalogBundle\Entity\Product;
use WellCommerce\Bundle\CoreBundle\Controller\Box\AbstractBoxController;
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
    public function indexAction(LayoutBoxSettingsCollection $boxSettings): Response
    {
        $product = $this->getProductStorage()->getCurrentProduct();
        $dataset = $this->get('product.dataset.front');

        $products = $dataset->getResult('array', [
            'limit'      => $boxSettings->getParam('limit', 12),
            'page'       => 1,
            'order_by'   => 'hierarchy',
            'order_dir'  => 'asc',
            'conditions' => $this->createConditionsCollection($product),
        ]);

        return $this->displayTemplate('index', [
            'dataset'     => $products,
            'product'     => $product,
            'boxSettings' => $boxSettings,
        ]);
    }

    protected function createConditionsCollection(Product $product): ConditionsCollection
    {
        /** @var Collection $similarProducts */
        $identifiers     = [];
        $similarProducts = $product->getSimilarProducts();
        $similarProducts->map(function (Product $similarProduct) use (&$identifiers) {
            $identifiers[] = $similarProduct->getId();
        });

        if (0 === count($identifiers)) {
            $identifiers = [0];
        }

        $conditions = new ConditionsCollection();
        $conditions->add(new In('id', $identifiers));

        return $conditions;
    }
}

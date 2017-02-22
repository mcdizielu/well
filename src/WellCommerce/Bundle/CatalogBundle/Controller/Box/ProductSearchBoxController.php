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

namespace WellCommerce\Bundle\CatalogBundle\Controller\Box;

use Symfony\Component\HttpFoundation\Response;
use WellCommerce\Component\Layout\Collection\LayoutBoxSettingsCollection;
use WellCommerce\Bundle\CoreBundle\Controller\Box\AbstractBoxController;
use WellCommerce\Component\DataSet\Conditions\ConditionsCollection;

/**
 * Class ProductSearchBoxController
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ProductSearchBoxController extends AbstractBoxController
{
    public function indexAction(LayoutBoxSettingsCollection $boxSettings): Response
    {
        $requestHelper = $this->getRequestHelper();
        $limit         = $this->getRequestHelper()->getAttributesBagParam('limit', $boxSettings->getParam('per_page', 12));
        $dataset       = $this->get('product_search.dataset.front');
        $conditions    = new ConditionsCollection();
        $conditions    = $this->get('layered_navigation.helper')->addLayeredNavigationConditions($conditions);
        
        $products = $dataset->getResult('array', [
            'limit'      => $limit,
            'page'       => $requestHelper->getAttributesBagParam('page', 1),
            'order_by'   => $requestHelper->getAttributesBagParam('orderBy', 'score'),
            'order_dir'  => $requestHelper->getAttributesBagParam('orderDir', 'asc'),
            'conditions' => $conditions,
        ]);
        
        return $this->displayTemplate('index', [
            'dataset' => $products,
        ]);
    }
}

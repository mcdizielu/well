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

namespace WellCommerce\Bundle\CmsBundle\Controller\Box;

use Symfony\Component\HttpFoundation\Response;
use WellCommerce\Bundle\CoreBundle\Controller\Box\AbstractBoxController;
use WellCommerce\Component\DataSet\Conditions\Condition\Eq;
use WellCommerce\Component\DataSet\Conditions\ConditionsCollection;
use WellCommerce\Component\Layout\Collection\LayoutBoxSettingsCollection;

/**
 * Class NewsFeedBoxController
 *
 * @author  diversantvlz
 */
class NewsFeedBoxController extends AbstractBoxController
{
    public function indexAction(LayoutBoxSettingsCollection $boxSettings): Response
    {
        $dataset       = $this->get('news.dataset.front');
        $requestHelper = $this->getRequestHelper();
        
        $conditions = new ConditionsCollection();
        $conditions->add(new Eq('publish', true));
        
        $conditions = $this->get('layered_navigation.helper')->addLayeredNavigationConditions($conditions);
        
        $newsFeed = $dataset->getResult('array', [
            'limit'      => $boxSettings->getParam('per_page', 12),
            'page'       => $requestHelper->getAttributesBagParam('page', 1),
            'order_by'   => 'startDate',
            'order_dir'  => 'desc',
            'conditions' => $conditions,
        ]);
        
        return $this->displayTemplate('index', [
            'dataset'     => $newsFeed,
            'boxSettings' => $boxSettings,
        ]);
    }
}

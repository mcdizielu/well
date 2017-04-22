<?php
/**
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
use WellCommerce\Bundle\CmsBundle\Request\NewsRequestStorage;
use WellCommerce\Bundle\CoreBundle\Controller\Box\AbstractBoxController;
use WellCommerce\Component\Layout\Collection\LayoutBoxSettingsCollection;

/**
 * Class NewsBoxController
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class NewsBoxController extends AbstractBoxController
{
    public function indexAction(LayoutBoxSettingsCollection $boxSettings): Response
    {
        $news = $this->getNewsRequestStorage()->getCurrentNews();

        return $this->displayTemplate('index', [
            'news'        => $news,
            'boxSettings' => $boxSettings,
        ]);
    }

    private function getNewsRequestStorage(): NewsRequestStorage
    {
        /** @var $newsRequestStorage NewsRequestStorage  */

        $newsRequestStorage = $this->get('news.request.storage');
        return $newsRequestStorage;
    }
}

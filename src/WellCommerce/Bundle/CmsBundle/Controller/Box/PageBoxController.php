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
use WellCommerce\Bundle\AppBundle\Collection\LayoutBoxSettingsCollection;
use WellCommerce\Bundle\CmsBundle\Request\PageRequestStorage;
use WellCommerce\Bundle\CoreBundle\Controller\Box\AbstractBoxController;

/**
 * Class PageBoxController
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class PageBoxController extends AbstractBoxController
{
    public function indexAction(LayoutBoxSettingsCollection $boxSettings): Response
    {
        $page = $this->getPageRequestStorage()->getCurrentPage();
        
        return $this->displayTemplate('index', [
            'page' => $page,
        ]);
    }
    
    private function getPageRequestStorage(): PageRequestStorage
    {
        return $this->get('page.request.storage');
    }
}

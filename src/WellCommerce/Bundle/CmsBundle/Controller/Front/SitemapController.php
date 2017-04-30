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

namespace WellCommerce\Bundle\CmsBundle\Controller\Front;

use Symfony\Component\HttpFoundation\Response;
use WellCommerce\Bundle\CoreBundle\Controller\Front\AbstractFrontController;
use WellCommerce\Component\Breadcrumb\Model\Breadcrumb;

/**
 * Class SitemapController
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class SitemapController extends AbstractFrontController
{
    public function indexAction(): Response
    {
        $this->getBreadcrumbProvider()->add(new Breadcrumb([
            'label' => 'sitemap.heading.index',
        ]));
        
        return $this->displayTemplate('index');
    }
}

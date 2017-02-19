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
use WellCommerce\Bundle\CmsBundle\Entity\Page;
use WellCommerce\Bundle\CmsBundle\Request\PageRequestStorage;
use WellCommerce\Bundle\CoreBundle\Controller\Front\AbstractFrontController;
use WellCommerce\Component\Breadcrumb\Model\Breadcrumb;

/**
 * Class PageController
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class PageController extends AbstractFrontController
{
    public function indexAction(Page $page): Response
    {
        if (false === $page->getPublish()) {
            return $this->redirectToRoute('front.home_page.index');
        }
        
        if (null !== $page->getParent()) {
            $this->getBreadcrumbProvider()->add(new Breadcrumb([
                'label' => $page->getParent()->translate()->getName(),
            ]));
        }
        
        $this->getBreadcrumbProvider()->add(new Breadcrumb([
            'label' => $page->translate()->getName(),
        ]));
        
        $this->getPageRequestStorage()->setCurrentPage($page);
        
        return $this->displayTemplate('index', [
            'page'     => $page,
            'metadata' => $page->translate()->getMeta(),
        ]);
    }
    
    private function getPageRequestStorage(): PageRequestStorage
    {
        return $this->get('page.request.storage');
    }
}

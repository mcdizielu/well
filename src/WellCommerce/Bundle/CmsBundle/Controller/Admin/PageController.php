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

namespace WellCommerce\Bundle\CmsBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WellCommerce\Bundle\AppBundle\Entity\LayoutBox;
use WellCommerce\Bundle\CmsBundle\Entity\Page;
use WellCommerce\Bundle\CoreBundle\Controller\Admin\AbstractAdminController;
use WellCommerce\Bundle\CoreBundle\Helper\Helper;

/**
 * Class PageController
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class PageController extends AbstractAdminController
{
    public function editLayoutAction(Page $page): Response
    {
        $boxes       = [];
        $layoutBoxes = $this->get('layout_box.repository')->getCollection();
        $layoutBoxes->map(function (LayoutBox $layoutBox) use (&$boxes) {
            list($type,) = explode('_', Helper::snake($layoutBox->getBoxType()));
            
            $boxes[$type][$layoutBox->getIdentifier()] = $layoutBox->translate()->getName();
        });
        
        ksort($boxes);
        
        return $this->displayTemplate('edit_layout', [
            'resource' => $page,
            'boxes'    => $boxes,
            'form'     => $this->getPageLayoutForm(),
        ]);
    }
    
    public function saveLayoutAction(Page $page, Request $request): Response
    {
        $page->setLayout($request->get('layout'));
        $this->manager->updateResource($page);
        
        return $this->jsonResponse([
            'success' => true,
        ]);
    }
    
    private function getPageLayoutForm()
    {
        return $this->get('page_layout.form_builder.admin')->createForm();
    }
}

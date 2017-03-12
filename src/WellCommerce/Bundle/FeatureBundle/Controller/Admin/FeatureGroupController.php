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

namespace WellCommerce\Bundle\FeatureBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WellCommerce\Bundle\CoreBundle\Controller\Admin\AbstractAdminController;
use WellCommerce\Bundle\FeatureBundle\Manager\FeatureGroupManager;

/**
 * Class FeatureGroupController
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class FeatureGroupController extends AbstractAdminController
{
    /**
     * @var FeatureGroupManager
     */
    protected $manager;
    
    public function ajaxIndexAction(Request $request): Response
    {
        if (!$request->isXmlHttpRequest()) {
            return $this->redirectToAction('index');
        }
        
        $setId     = (int)$request->request->get('setId');
        $productId = (int)$request->request->get('productId');
        
        return $this->jsonResponse([
            'groups' => $this->manager->getFeatureGroups($setId, $productId),
        ]);
    }
}

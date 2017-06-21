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

namespace WellCommerce\Bundle\CatalogBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use WellCommerce\Bundle\CatalogBundle\Entity\Producer;
use WellCommerce\Bundle\CoreBundle\Controller\Admin\AbstractAdminController;

/**
 * Class ProducerController
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ProducerController extends AbstractAdminController
{
    public function updateAction(Request $request): JsonResponse
    {
        $id   = $request->request->get('id');
        $data = $request->request->get('product');
        
        try {
            $producer = $this->manager->getRepository()->find($id);
            if ($producer instanceof Producer) {
                $producer->setHierarchy($data['hierarchy']);
                $this->manager->updateResource($producer);
            }
            $result = ['success' => $this->trans('producer.flash.success.saved')];
        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage()];
        }
        
        return $this->jsonResponse($result);
    }
}

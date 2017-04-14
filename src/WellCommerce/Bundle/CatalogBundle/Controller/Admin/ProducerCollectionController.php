<?php
/*
 * WellCommerce Open-Source E-Commerce Platform
 *
 * This file is part of the WellCommerce package.
 *
 * (c) Adam Piotrowski <rafal@wellcommerce.org>
 *
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */

namespace WellCommerce\Bundle\CatalogBundle\Controller\Admin;

use WellCommerce\Bundle\CoreBundle\Controller\Admin\AbstractAdminController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ProducerCollectionController
 *
 * @author  Rafał Martonik <rafal@wellcommerce.org>
 */
class ProducerCollectionController extends AbstractAdminController
{
    public function ajaxGetCollectionsByProducer(Request $request): Response
    {
        if (!$request->isXmlHttpRequest()) {
            return $this->redirectToAction('index');
        }
        
        $producerId                   = (int)$request->request->get('value');
        $producerCollectionOptions    = [];
        $producerCollections          = $this->getManager()->getRepository()->findBy(['producer' => $producerId]);
        $producerCollectionOptions[0] = [
            'sValue' => 0,
            'sLabel' => $this->trans('common.label.none'),
        ];
        
        foreach ($producerCollections AS $key => $producerCollection) {
            $producerCollectionOptions[$key + 1] = [
                'sValue' => $producerCollection->getId(),
                'sLabel' => $producerCollection->translate()->getName(),
            ];
        }
        
        return $this->jsonResponse(
            $producerCollectionOptions
        );
    }
}

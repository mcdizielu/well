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

namespace WellCommerce\Bundle\CatalogBundle\Controller\Front;

use Symfony\Component\HttpFoundation\Response;
use WellCommerce\Bundle\CatalogBundle\Entity\ProducerCollection;
use WellCommerce\Bundle\CoreBundle\Controller\Front\AbstractFrontController;
use WellCommerce\Component\Breadcrumb\Model\Breadcrumb;

/**
 * Class ProducerCollectionController
 *
 * @author  Rafał Martonik <rafal@wellcommerce.org>
 */
class ProducerCollectionController extends AbstractFrontController
{
    public function indexAction(ProducerCollection $producerCollection) : Response
    {
        $this->addBreadcrumbs($producerCollection);
        $this->getProducerCollectionStorage()->setCurrentProducerCollection($producerCollection);
        
        return $this->displayTemplate('index', [
            'producerCollection' => $producerCollection,
            'producer'           => $producerCollection->getProducer(),
            'metadata'           => $producerCollection->translate()->getMeta(),
        ]);
    }
    
    private function addBreadcrumbs(ProducerCollection $producerCollection)
    {
        
        $producer = $producerCollection->getProducer();
        $this->getBreadcrumbProvider()->add(new Breadcrumb([
            'label' => $producer->translate()->getName(),
            'url'   => $this->getRouterHelper()->generateUrl($producer->translate()->getRoute()->getId()),
        ]));
        
        $this->getBreadcrumbProvider()->add(new Breadcrumb([
            'label' => $producerCollection->translate()->getName(),
        ]));
        
    }
}

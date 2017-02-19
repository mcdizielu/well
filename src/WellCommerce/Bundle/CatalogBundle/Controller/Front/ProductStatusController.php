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
use WellCommerce\Bundle\CatalogBundle\Entity\ProductStatus;
use WellCommerce\Bundle\CoreBundle\Controller\Front\AbstractFrontController;
use WellCommerce\Component\Breadcrumb\Model\Breadcrumb;

/**
 * Class ProductStatusController
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ProductStatusController extends AbstractFrontController
{
    public function indexAction(ProductStatus $status): Response
    {
        $this->getBreadcrumbProvider()->add(new Breadcrumb([
            'label' => $status->translate()->getName(),
        ]));
        
        $this->getProductStatusStorage()->setCurrentProductStatus($status);
        
        return $this->displayTemplate('index', [
            'status'   => $status,
            'metadata' => $status->translate()->getMeta(),
        ]);
    }
}

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

namespace WellCommerce\Bundle\ReviewBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\JsonResponse;
use WellCommerce\Bundle\CoreBundle\Controller\Admin\AbstractAdminController;
use WellCommerce\Bundle\ReviewBundle\Entity\Review;

/**
 * Class ReviewController
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ReviewController extends AbstractAdminController
{
    public function enableAction(Review $review): JsonResponse
    {
        $review->setEnabled(true);
        $this->getManager()->updateResource($review);
        
        return $this->jsonResponse(['success' => true]);
    }
    
    public function disableAction(Review $review): JsonResponse
    {
        $review->setEnabled(false);
        $this->getManager()->updateResource($review);
        
        return $this->jsonResponse(['success' => true]);
    }
}

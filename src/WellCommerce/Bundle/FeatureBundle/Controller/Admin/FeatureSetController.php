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

use Doctrine\Common\Collections\Criteria;
use Knp\DoctrineBehaviors\Model\Sluggable\Transliterator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use WellCommerce\Bundle\CoreBundle\Controller\Admin\AbstractAdminController;
use WellCommerce\Bundle\FeatureBundle\Entity\FeatureSet;

/**
 * Class FeatureSetController
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class FeatureSetController extends AbstractAdminController
{
    public function ajaxIndexAction(Request $request): JsonResponse
    {
        if (!$request->isXmlHttpRequest()) {
            return $this->redirectToAction('index');
        }
        
        return $this->jsonResponse([
            'sets' => $this->getFeatureSets(),
        ]);
    }
    
    public function getFeatureSets(): array
    {
        $groups = $this->manager->getRepository()->matching(new Criteria());
        $sets   = [];
        
        $groups->map(function (FeatureSet $set) use (&$sets) {
            $sets[] = [
                'id'               => $set->getId(),
                'name'             => $set->translate()->getName(),
                'current_category' => false,
            ];
        });
        
        usort($sets, function ($a, $b) {
            return strcasecmp(Transliterator::unaccent($a['name']), Transliterator::unaccent($b['name']));
        });
        
        return $sets;
    }
}

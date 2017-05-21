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

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use WellCommerce\Bundle\CmsBundle\Entity\News;
use WellCommerce\Bundle\CoreBundle\Controller\Front\AbstractFrontController;
use WellCommerce\Component\Breadcrumb\Model\Breadcrumb;

/**
 * Class NewsController
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class NewsController extends AbstractFrontController
{
    public function indexAction(Request $request): Response
    {
        $this->getBreadcrumbProvider()->add(new Breadcrumb([
            'label' => $this->trans('news.heading.index'),
        ]));
        
        return $this->displayTemplate('index');
    }
    
    public function viewAction(News $news): Response
    {
        $this->getBreadcrumbProvider()->add(new Breadcrumb([
            'url'   => $this->getRouterHelper()->generateUrl('front.news.index'),
            'label' => $this->trans('news.heading.index'),
        ]));
        
        $this->getBreadcrumbProvider()->add(new Breadcrumb([
            'label' => $news->translate()->getTopic(),
        ]));
        
        $this->getMetadataHelper()->setMetadata($news->translate()->getMeta());
        
        return $this->displayTemplate('view', [
            'news' => $news,
        ]);
    }
    
    protected function findOr404(Request $request, array $criteria = [])
    {
        // check whether request contains ID attribute
        if (!$request->attributes->has('id')) {
            throw new \LogicException('Request does not have "id" attribute set.');
        }
        
        $criteria['id'] = $request->attributes->get('id');
        
        if (null === $resource = $this->getManager()->getRepository()->findOneBy($criteria)) {
            throw new NotFoundHttpException(sprintf('Resource not found'));
        }
        
        return $resource;
    }
}

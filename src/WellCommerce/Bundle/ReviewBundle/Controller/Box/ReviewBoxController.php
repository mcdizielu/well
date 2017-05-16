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

namespace WellCommerce\Bundle\ReviewBundle\Controller\Box;

use Symfony\Component\HttpFoundation\Response;
use WellCommerce\Bundle\CoreBundle\Controller\Box\AbstractBoxController;
use WellCommerce\Bundle\ReviewBundle\Entity\Review;
use WellCommerce\Bundle\ReviewBundle\Repository\ReviewRepositoryInterface;
use WellCommerce\Component\Layout\Collection\LayoutBoxSettingsCollection;


/**
 * Class ReviewBoxController
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ReviewBoxController extends AbstractBoxController
{
    public function indexAction(LayoutBoxSettingsCollection $boxSettings): Response
    {
        /** @var ReviewRepositoryInterface $repository */
        $repository = $this->manager->getRepository();
        $product    = $this->getProductStorage()->getCurrentProduct();
        
        /** @var Review $resource */
        $resource = $this->getManager()->initResource();
        $resource->setProduct($product);
        
        $currentRoute = $product->translate()->getRoute()->getId();
        $form         = $this->formBuilder->createForm($resource);
        
        if ($form->handleRequest()->isSubmitted()) {
            if ($form->isValid()) {
                if (false === $this->get('security.authorization_checker')->isGranted('ROLE_CLIENT')
                    && false === $this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')
                ) {
                    $resource->setEnabled(0);
                }
                $this->getManager()->createResource($resource);
                
                $this->getFlashHelper()->addSuccess('review.flash.success');
                
                return $this->getRouterHelper()->redirectTo('dynamic_' . $currentRoute);
            }
            
            $this->getFlashHelper()->addError('review.flash.error');
        }
        
        return $this->displayTemplate('index', [
            'form'        => $form,
            'product'     => $product,
            'reviews'     => $repository->getProductReviews($product),
            'boxSettings' => $boxSettings,
        ]);
    }
    
    public function reportAction(Review $review)
    {
        $currentRoute        = $review->getProduct()->translate()->getRoute()->getId();
        $mailerConfiguration = $this->getShopStorage()->getCurrentShop()->getMailerConfiguration();
        
        $this->getMailerHelper()->sendEmail([
            'recipient'     => $mailerConfiguration->getFrom(),
            'subject'       => $this->trans('review.email.heading.report'),
            'template'      => 'WellCommerceAppBundle:Email:report_review.html.twig',
            'parameters'    => [
                'review' => $review,
            ],
            'configuration' => $mailerConfiguration,
        ]);
        
        $this->getFlashHelper()->addSuccess('report.flash.success');
        
        return $this->redirectToRoute('dynamic_' . $currentRoute);
    }
}

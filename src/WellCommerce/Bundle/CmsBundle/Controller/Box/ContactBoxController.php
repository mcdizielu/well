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

namespace WellCommerce\Bundle\CmsBundle\Controller\Box;

use Symfony\Component\HttpFoundation\Response;
use WellCommerce\Component\Layout\Collection\LayoutBoxSettingsCollection;
use WellCommerce\Bundle\CmsBundle\Entity\ContactTicket;
use WellCommerce\Bundle\CoreBundle\Controller\Box\AbstractBoxController;

/**
 * Class ContactBoxController
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ContactBoxController extends AbstractBoxController
{
    public function indexAction(LayoutBoxSettingsCollection $boxSettings): Response
    {
        /** @var ContactTicket $resource */
        $resource = $this->get('contact_ticket.manager')->initResource();
        $form     = $this->formBuilder->createForm($resource);
        
        if ($form->handleRequest()->isSubmitted()) {
            if ($form->isValid()) {
                $this->getManager()->createResource($resource);
                
                $this->getMailerHelper()->sendEmail([
                    'recipient'     => [$resource->getEmail()],
                    'subject'       => $resource->getSubject(),
                    'template'      => 'WellCommerceAppBundle:Email:contact.html.twig',
                    'parameters'    => [
                        'contact' => $resource,
                    ],
                    'configuration' => $this->getShopStorage()->getCurrentShop()->getMailerConfiguration(),
                ]);
                
                $this->getFlashHelper()->addSuccess('contact_ticket.flash.success');
                
                return $this->getRouterHelper()->redirectTo('front.contact.index');
            }
            
            $this->getFlashHelper()->addError('contact_ticket.flash.error');
        }
        
        return $this->displayTemplate('index', [
            'form' => $form,
        ]);
        
    }
}

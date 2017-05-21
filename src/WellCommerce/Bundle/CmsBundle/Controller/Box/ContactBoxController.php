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

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Response;
use WellCommerce\Bundle\AppBundle\Entity\Client;
use WellCommerce\Bundle\AppBundle\Service\ReCaptcha\Helper\ReCaptchaHelper;
use WellCommerce\Bundle\CmsBundle\Entity\Contact;
use WellCommerce\Bundle\CmsBundle\Entity\ContactTicket;
use WellCommerce\Bundle\CoreBundle\Controller\Box\AbstractBoxController;
use WellCommerce\Component\Layout\Collection\LayoutBoxSettingsCollection;

/**
 * Class ContactBoxController
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ContactBoxController extends AbstractBoxController
{
    private $allowedMimeTypes = ['image/jpg', 'image/png', 'application/pdf'];
    
    public function indexAction(LayoutBoxSettingsCollection $boxSettings): Response
    {
        /** @var ContactTicket $resource */
        $resource = $this->get('contact_ticket.manager')->initResource();
        $client   = $this->getSecurityHelper()->getCurrentClient();
        
        if ($client instanceof Client) {
            $resource->setEmail($client->getContactDetails()->getEmail());
            $resource->setName($client->getContactDetails()->getFirstName());
            $resource->setSurname($client->getContactDetails()->getLastName());
            $resource->setPhone($client->getContactDetails()->getPhone());
        }
        
        $form = $this->formBuilder->createForm($resource, [
            'enctype' => 'multipart/form-data',
        ]);
        
        if ($form->handleRequest()->isSubmitted()) {
            if ($form->isValid()) {
                if ($this->getReCaptchaHelper()->isValid()) {
                    $this->getManager()->createResource($resource);
                    
                    $this->getMailerHelper()->sendEmail([
                        'recipient'           => $this->getRecipients($resource),
                        'reply_to'            => $resource->getEmail(),
                        'subject'             => $resource->getSubject(),
                        'template'            => 'WellCommerceCmsBundle:Email:contact.html.twig',
                        'parameters'          => [
                            'contact' => $resource,
                        ],
                        'dynamic_attachments' => $this->getDynamicAttachments(),
                        'configuration'       => $this->getShopStorage()->getCurrentShop()->getMailerConfiguration(),
                    ]);
                    
                    $this->getFlashHelper()->addSuccess('contact_ticket.flash.success');
                }
                
                return $this->getRouterHelper()->redirectTo('front.contact.index');
            }
            
            $this->getFlashHelper()->addError('contact_ticket.flash.error');
        }
        
        return $this->displayTemplate('index', [
            'form'             => $form,
            'boxSettings'      => $boxSettings,
            'allowedMimeTypes' => $this->allowedMimeTypes,
        ]);
    }
    
    private function getRecipients(ContactTicket $ticket): array
    {
        $recipients   = [];
        $recipients[] = $this->getShopStorage()->getCurrentShop()->getMailerConfiguration()->getFrom();
        
        if ($ticket->getContact() instanceof Contact) {
            $recipients[] = $ticket->getContact()->translate()->getEmail();
        }
        
        return $recipients;
    }
    
    private function getDynamicAttachments(): array
    {
        $dynamicAttachments = [];
        $request            = $this->getRequestHelper()->getCurrentRequest();
        $uploadedFile       = $request->files->get('attachment');
        if ($uploadedFile instanceof UploadedFile) {
            if ($uploadedFile->isValid() && in_array($uploadedFile->getMimeType(), $this->allowedMimeTypes)) {
                $dynamicAttachments[] = [
                    'data' => file_get_contents($uploadedFile->getPathname()),
                    'name' => $uploadedFile->getClientOriginalName(),
                    'type' => $uploadedFile->getClientMimeType(),
                ];
            }
        }
        
        return $dynamicAttachments;
    }
    
    private function getReCaptchaHelper(): ReCaptchaHelper
    {
        return $this->get('recaptcha.helper');
    }
}

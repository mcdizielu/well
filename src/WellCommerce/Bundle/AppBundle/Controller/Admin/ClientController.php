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

namespace WellCommerce\Bundle\AppBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WellCommerce\Bundle\AppBundle\Entity\Client;
use WellCommerce\Bundle\CoreBundle\Controller\Admin\AbstractAdminController;

/**
 * Class ClientController
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ClientController extends AbstractAdminController
{
    public function addAction(Request $request): Response
    {
        /** @var Client $resource */
        $resource = $this->getManager()->initResource();
        $form     = $this->formBuilder->createForm($resource, [
            'validation_groups' => ['client_admin_registration']
        ]);
        
        if ($form->handleRequest()->isSubmitted()) {
            if ($form->isValid()) {
                $this->getManager()->createResource($resource);
                $password = $form->getValue()['required_data']['clientDetails']['clientDetails.hashedPassword'];
                
                $this->getMailerHelper()->sendEmail([
                    'recipient'     => $resource->getContactDetails()->getEmail(),
                    'subject'       => $this->getTranslatorHelper()->trans('client.email.heading.register'),
                    'template'      => 'WellCommerceAppBundle:Email:register_admin.html.twig',
                    'parameters'    => [
                        'client'   => $resource,
                        'password' => $password,
                    ],
                    'configuration' => $resource->getShop()->getMailerConfiguration(),
                ]);
            }
            
            return $this->createFormDefaultJsonResponse($form);
        }
        
        return $this->displayTemplate('add', [
            'form' => $form,
        ]);
    }
}

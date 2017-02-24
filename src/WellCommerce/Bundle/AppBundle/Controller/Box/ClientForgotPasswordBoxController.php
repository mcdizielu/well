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

namespace WellCommerce\Bundle\AppBundle\Controller\Box;

use WellCommerce\Bundle\AppBundle\Entity\Client;
use WellCommerce\Bundle\AppBundle\Exception\ResetPasswordException;
use WellCommerce\Bundle\AppBundle\Manager\ClientManager;
use WellCommerce\Bundle\CoreBundle\Controller\Box\AbstractBoxController;
use WellCommerce\Component\Form\Elements\FormInterface;

/**
 * Class ClientForgotPasswordBoxController
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ClientForgotPasswordBoxController extends AbstractBoxController
{
    public function resetAction()
    {
        $form = $this->formBuilder->createForm();
        
        if ($form->handleRequest()->isSubmitted()) {
            $data = $form->getValue();
            
            try {
                $client = $this->getManager()->getClientByUsername($data['_username']);
                $this->getManager()->resetPassword($client);
                $this->getFlashHelper()->addSuccess('client.flash.reset_password.success');
                
                $this->getMailerHelper()->sendEmail([
                    'recipient'     => $client->getContactDetails()->getEmail(),
                    'subject'       => $this->getTranslatorHelper()->trans('client.email.heading.reset_password'),
                    'template'      => 'WellCommerceAppBundle:Email:reset_password.html.twig',
                    'parameters'    => [
                        'client' => $client,
                    ],
                    'configuration' => $client->getShop()->getMailerConfiguration(),
                ]);
                
            } catch (ResetPasswordException $e) {
                $this->getFlashHelper()->addError($e->getMessage());
            }
            
            return $this->getRouterHelper()->redirectTo('front.client_password.reset');
        }
        
        return $this->displayTemplate('reset', [
            'form' => $form,
        ]);
    }
    
    public function changeAction()
    {
        $hash   = $this->getRequestHelper()->getAttributesBagParam('hash');
        $client = $this->getManager()->getRepository()->findOneBy(['clientDetails.resetPasswordHash' => $hash]);
        
        if (!$client instanceof Client) {
            return $this->getRouterHelper()->redirectToAction('reset');
        }
        
        $client->getClientDetails()->setPassword('');
        $client->getClientDetails()->setLegacyPasswordEncoder(null);
        $client->getClientDetails()->setLegacyPassword(null);
        
        $form = $this->createChangePasswordForm($client);
        
        if ($form->handleRequest()->isSubmitted()) {
            if ($form->isValid()) {
                $this->getManager()->updateResource($client);
                $this->getFlashHelper()->addSuccess('client.flash.change_password.success');
                
                return $this->getRouterHelper()->redirectTo('front.client.login');
            }
            
            $this->getFlashHelper()->addError('client.flash.change_password.error');
        }
        
        
        return $this->displayTemplate('change', [
            'form' => $form,
        ]);
    }
    
    protected function getManager(): ClientManager
    {
        return parent::getManager();
    }
    
    private function createChangePasswordForm(Client $client): FormInterface
    {
        return $this->get('client_change_password.form_builder.front')->createForm([
            'name'              => 'change_password',
            'validation_groups' => ['client_password_change'],
        ], $client);
    }
}

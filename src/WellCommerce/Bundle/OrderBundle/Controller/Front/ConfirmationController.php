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

namespace WellCommerce\Bundle\OrderBundle\Controller\Front;

use Symfony\Component\HttpFoundation\Response;
use WellCommerce\Bundle\CoreBundle\Controller\Front\AbstractFrontController;
use WellCommerce\Bundle\OrderBundle\Entity\Order;
use WellCommerce\Bundle\OrderBundle\Entity\Payment;

/**
 * Class ConfirmationController
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ConfirmationController extends AbstractFrontController
{
    public function indexAction(): Response
    {
        $order = $this->getOrderProvider()->getCurrentOrder();
        $order->setConfirmed(true);
        
        if (!$order->isConfirmable()) {
            return $this->redirectToRoute('front.cart.index');
        }
        
        $form = $this->formBuilder->createForm($order);
        
        if ($form->handleRequest()->isSubmitted()) {
            if ($form->isValid()) {
                $this->getManager()->updateResource($order);
                $payment = $this->getPaymentForOrder($order);
                
                return $this->redirectToRoute('front.payment.initialize', ['token' => $payment->getToken()]);
            }
            
            if (count($form->getError())) {
                $this->getFlashHelper()->addError('order.form.error.confirmation');
            }
        }
        
        return $this->displayTemplate('index', [
            'form'     => $form,
            'order'    => $order,
            'elements' => $form->getChildren(),
        ]);
    }
    
    private function getPaymentForOrder(Order $order): Payment
    {
        return $order->getPayments()->first();
    }
}

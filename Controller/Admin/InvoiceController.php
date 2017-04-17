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

namespace WellCommerce\Bundle\InvoiceBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WellCommerce\Bundle\CoreBundle\Controller\Admin\AbstractAdminController;
use WellCommerce\Bundle\OrderBundle\Entity\Order;
use WellCommerce\Bundle\OrderBundle\Manager\InvoiceManager;

/**
 * Class InvoiceController
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class InvoiceController extends AbstractAdminController
{
    /**
     * @var InvoiceManager
     */
    protected $manager;
    
    public function addAction(Request $request): Response
    {
        $orderId = $request->get('order');
        $order   = $this->get('order.repository')->find($orderId);
        
        if (!$order instanceof Order) {
            return $this->redirectToAction('index');
        }
        
        $this->getOrderProvider()->setCurrentOrder($order);
        $invoice = $this->manager->prepareInvoiceForOrder($order);
        $form    = $this->formBuilder->createForm($invoice);
        
        if ($form->handleRequest()->isSubmitted()) {
            if ($form->isValid()) {
                $this->getManager()->createResource($invoice);
            }
            
            $redirectTo = $this->getRouterHelper()->generateUrl('admin.order.edit', ['id' => $orderId]);
            
            $this->getFlashHelper()->addSuccess('invoice.flash.success');
            
            return $this->createFormDefaultJsonResponse($form, $redirectTo);
        }
        
        return $this->displayTemplate('add', [
            'form'    => $form,
            'invoice' => $invoice,
        ]);
    }
    
    public function printAction(string $guid): Response
    {
    }
}

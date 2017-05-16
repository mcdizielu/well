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
use WellCommerce\Bundle\InvoiceBundle\Entity\Invoice;
use WellCommerce\Bundle\InvoiceBundle\Manager\InvoiceManager;
use WellCommerce\Bundle\InvoiceBundle\Processor\InvoiceProcessorCollection;
use WellCommerce\Bundle\InvoiceBundle\Processor\InvoiceProcessorInterface;
use WellCommerce\Bundle\OrderBundle\Entity\Order;

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
        $invoice    = $this->manager->prepareInvoiceForOrder($order);
        $form       = $this->formBuilder->createForm($invoice);
        $redirectTo = null;
        
        if ($form->handleRequest()->isSubmitted()) {
            if ($form->isValid()) {
                $this->getInvoiceProcessor($invoice)->save($invoice);
                $this->getManager()->createResource($invoice);
                $redirectTo = $this->getRouterHelper()->generateUrl('admin.order.edit', ['id' => $orderId]);
                $this->getFlashHelper()->addSuccess('invoice.flash.success');
            }
            
            return $this->createFormDefaultJsonResponse($form, $redirectTo);
        }
        
        return $this->displayTemplate('add', [
            'form'    => $form,
            'invoice' => $invoice,
        ]);
    }
    
    public function editAction(int $id): Response
    {
        $invoice = $this->getManager()->getRepository()->find($id);
        
        if ($invoice instanceof Invoice) {
            return $this->redirectToAction('print', ['guid' => $invoice->getGuid()]);
        }
        
        return $this->redirectToAction('index');
    }
    
    public function printAction(string $guid): Response
    {
        $invoice = $this->getManager()->getRepository()->findOneBy(['guid' => $guid]);
        if ($invoice instanceof Invoice) {
            return $this->getInvoiceProcessor($invoice)->download($invoice);
        }
        
        return $this->redirectToAction('index');
    }
    
    public function sendAction(string $guid): Response
    {
        $invoice = $this->getManager()->getRepository()->findOneBy(['guid' => $guid]);
        if ($invoice instanceof Invoice) {
            $this->getInvoiceProcessor($invoice)->send($invoice);
            $this->getFlashHelper()->addSuccess('invoice.flash.send_success');
        }
        
        return $this->redirectToRoute('admin.order.edit', ['id' => $invoice->getOrder()->getId()]);
    }
    
    private function getInvoiceProcessor(Invoice $invoice): InvoiceProcessorInterface
    {
        /** @var InvoiceProcessorCollection $collection */
        $collection = $this->get('invoice.processor.collection');
        
        return $collection->get($invoice->getProcessor());
    }
}

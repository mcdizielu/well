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
namespace WellCommerce\Bundle\CoreBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WellCommerce\Bundle\CoreBundle\Controller\AbstractController;
use WellCommerce\Bundle\CoreBundle\Entity\EntityInterface;
use WellCommerce\Bundle\CoreBundle\Manager\ManagerInterface;
use WellCommerce\Bundle\OrderBundle\Provider\Admin\OrderProviderInterface;
use WellCommerce\Component\DataGrid\DataGridInterface;
use WellCommerce\Component\DataSet\Conditions\ConditionsCollection;
use WellCommerce\Component\DataSet\Conditions\ConditionsResolver;
use WellCommerce\Component\DataSet\DataSetInterface;
use WellCommerce\Component\Form\FormBuilderInterface;

/**
 * Class AbstractAdminController
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
abstract class AbstractAdminController extends AbstractController
{
    /**
     * @var null|DataGridInterface
     */
    protected $dataGrid;
    
    /**
     * @var null|DataSetInterface
     */
    protected $dataSet;
    
    public function __construct(
        ManagerInterface $manager = null,
        FormBuilderInterface $formBuilder = null,
        DataGridInterface $dataGrid = null,
        DataSetInterface $dataSet = null
    ) {
        parent::__construct($manager, $formBuilder);
        $this->dataGrid = $dataGrid;
        $this->dataSet  = $dataSet;
    }
    
    public function indexAction(): Response
    {
        return $this->displayTemplate('index', [
            'datagrid' => $this->dataGrid,
        ]);
    }
    
    public function gridAction(Request $request): Response
    {
        $page               = ($request->request->get('starting_from', 0) / $request->request->get('limit', 10)) + 1;
        $conditions         = new ConditionsCollection();
        $conditionsResolver = new ConditionsResolver();
        $conditionsResolver->resolveConditions($request->request->get('where'), $conditions);
        
        $requestOptions = [
            'page'       => $page,
            'limit'      => $request->request->get('limit', 10),
            'order_by'   => $request->request->get('order_by', 'id'),
            'order_dir'  => $request->request->get('order_dir', 'desc'),
            'conditions' => $conditions,
        ];
        
        try {
            $results = $this->dataSet->getResult('datagrid', $requestOptions);
        } catch (\Exception $e) {
            $results = nl2br($e->getMessage());
        }
        
        return $this->jsonResponse($results);
    }
    
    public function addAction(Request $request): Response
    {
        $resource = $this->getManager()->initResource();
        $form     = $this->formBuilder->createForm($resource);
        
        if ($form->handleRequest()->isSubmitted()) {
            if ($form->isValid()) {
                $this->getManager()->createResource($resource);
            }
            
            return $this->createFormDefaultJsonResponse($form);
        }
        
        return $this->displayTemplate('add', [
            'form' => $form,
        ]);
    }
    
    public function editAction(int $id): Response
    {
        $resource = $this->getManager()->getRepository()->find($id);
        
        if (!$resource instanceof EntityInterface) {
            return $this->redirectToAction('index');
        }
        
        $form = $this->formBuilder->createForm($resource);
        
        if ($form->handleRequest()->isSubmitted()) {
            if ($form->isValid()) {
                $this->getManager()->updateResource($resource);
            }
            
            return $this->createFormDefaultJsonResponse($form);
        }
        
        return $this->displayTemplate('edit', [
            'form'     => $form,
            'resource' => $resource,
        ]);
    }
    
    public function deleteAction(int $id): Response
    {
        try {
            $resource = $this->getManager()->getRepository()->findOneBy(['id' => $id]);
            $this->getManager()->removeResource($resource);
        } catch (\Exception $e) {
            return $this->jsonResponse(['error' => $e->getTraceAsString()]);
        }
        
        return $this->jsonResponse(['success' => true]);
    }
    
    public function deleteGroupAction(Request $request): Response
    {
        $identifiers = $request->request->filter('identifiers');
        
        foreach ($identifiers as $id) {
            $resource = $this->getManager()->getRepository()->findOneBy(['id' => $id]);
            $this->getManager()->removeResource($resource, false);
        }
        
        $this->getEntityManager()->flush();
        
        return $this->jsonResponse(['success' => true]);
    }
    
    protected function getOrderProvider(): OrderProviderInterface
    {
        return $this->get('order.provider.admin');
    }
}

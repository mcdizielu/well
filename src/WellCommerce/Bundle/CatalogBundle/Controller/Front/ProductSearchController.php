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

namespace WellCommerce\Bundle\CatalogBundle\Controller\Front;

use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use WellCommerce\Bundle\CoreBundle\Controller\Front\AbstractFrontController;
use WellCommerce\Bundle\SearchBundle\Manager\SearchManagerInterface;
use WellCommerce\Component\Breadcrumb\Model\Breadcrumb;
use WellCommerce\Component\DataSet\Conditions\ConditionsCollection;
use WellCommerce\Component\Form\Elements\FormInterface;
use WellCommerce\Component\Form\FormBuilderInterface;
use WellCommerce\Component\Search\Model\FieldInterface;
use WellCommerce\Component\Search\Model\TypeInterface;
use WellCommerce\Component\Search\Request\SearchRequestInterface;

/**
 * Class SearchController
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ProductSearchController extends AbstractFrontController
{
    public function indexAction(SearchRequestInterface $searchRequest): Response
    {
        $this->getSearchManager()->search($searchRequest);
        
        $this->getBreadcrumbProvider()->add(new Breadcrumb([
            'label' => $this->trans('search.heading.index'),
        ]));
        
        $this->getBreadcrumbProvider()->add(new Breadcrumb([
            'label' => $searchRequest->getPhrase(),
        ]));
        
        return $this->displayTemplate('index', [
            'phrase' => $searchRequest->getPhrase(),
        ]);
    }
    
    public function advancedSearchAction(Request $request)
    {
        /** @var FormBuilderInterface $builder */
        $type    = $this->getSearchManager()->getType($request->get('_type'));
        $fields  = $type->getFields();
        $builder = $this->get('advanced_search.form_builder.front');
        $form    = $builder->createForm();
        
        $fields->map(function (FieldInterface $field) use ($builder, $form) {
            $options = $field->getAdvancedOptions();
            $name    = $field->getName();
            
            if (in_array($options['field']['type'], ['select', 'multi_select'])) {
                $element = $builder->getElement($options['field']['type'], [
                    'name'    => $name,
                    'label'   => $options['field']['label'],
                    'options' => $this->get($options['field']['dataset'])->getResult('select'),
                ]);
            } else {
                $element = $builder->getElement($options['field']['type'], [
                    'name'  => $name,
                    'label' => $options['field']['label'],
                ]);
            }
            
            $form->addChild($element);
        });
        
        if ($form->handleRequest()->isSubmitted()) {
            return $this->redirectToAction('index', $this->prepareSearchParameters($fields, $form->getValue()));
        }
        
        return $this->displayTemplate('advanced', ['form' => $form]);
    }
    
    public function quickSearchAction(SearchRequestInterface $searchRequest): JsonResponse
    {
        $identifiers = $this->getSearchManager()->search($searchRequest);
        $dataset     = $this->get('product_search.dataset.front');
        $conditions  = new ConditionsCollection();
        $conditions  = $this->get('layered_navigation.helper')->addLayeredNavigationConditions($conditions);
        $settings    = $this->container->getParameter('quick_search');
        
        $products = $dataset->getResult('array', [
            'limit'      => $settings['limit'],
            'page'       => 1,
            'order_by'   => $settings['order_by'],
            'order_dir'  => $settings['order_dir'],
            'conditions' => $conditions,
        ]);
        
        $liveSearchContent = $this->renderView('WellCommerceCatalogBundle:Front/ProductSearch:view.html.twig', [
            'dataset' => $products,
        ]);
        
        return $this->jsonResponse([
            'liveSearchContent' => $liveSearchContent,
            'total'             => count($identifiers),
        ]);
    }
    
    private function prepareSearchParameters(Collection $fields, array $formData): array
    {
        $params = [];
        
        $fields->map(function (FieldInterface $field) use (&$params, $formData) {
            if (isset($formData[$field->getName()])) {
                $params[$field->getName()] = $formData[$field->getName()];
            }
        });
        
        return $params;
    }
    
    private function getSearchManager(): SearchManagerInterface
    {
        return $this->get('search.manager');
    }
}

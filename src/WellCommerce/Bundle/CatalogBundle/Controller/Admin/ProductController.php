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

namespace WellCommerce\Bundle\CatalogBundle\Controller\Admin;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Exception\ValidatorException;
use WellCommerce\Bundle\CatalogBundle\Entity\Product;
use WellCommerce\Bundle\CatalogBundle\Exception\ProductNotFoundException;
use WellCommerce\Bundle\CoreBundle\Controller\Admin\AbstractAdminController;

/**
 * Class ProductController
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ProductController extends AbstractAdminController
{
    /**
     * Updates product data from DataGrid request
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function updateAction(Request $request): JsonResponse
    {
        $id   = $request->request->get('id');
        $data = $request->request->get('product');
        
        try {
            $this->quickUpdateProduct($id, $data);
            $result = ['success' => $this->trans('product.flash.success.saved')];
        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage()];
        }
        
        return $this->jsonResponse($result);
    }
    
    protected function quickUpdateProduct(int $id, array $data)
    {
        $product       = $this->findProduct($id);
        $entityManager = $this->getDoctrineHelper()->getEntityManager();
        
        $product->setSku($data['sku']);
        $product->setWeight($data['weight']);
        $product->setStock($data['stock']);
        $product->getSellPrice()->setGrossAmount($data['grossAmount']);
        
        $errors = $this->getValidatorHelper()->validate($product);
        if ($errors->count()) {
            throw new ValidatorException('Product data is not valid: ' . (string)$errors);
        }
        
        $entityManager->flush();
    }
    
    protected function findProduct(int $id): Product
    {
        $product = $this->manager->getRepository()->find($id);
        if (!$product instanceof Product) {
            throw new ProductNotFoundException($id);
        }
        
        return $product;
    }
}

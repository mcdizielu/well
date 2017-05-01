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

namespace WellCommerce\Bundle\SimilarProductBundle\EventListener;

use WellCommerce\Bundle\CatalogBundle\Entity\Product;
use WellCommerce\Bundle\CoreBundle\EventListener\AbstractEventSubscriber;
use WellCommerce\Component\Form\Event\FormEvent;

/**
 * Class SimilarProductSubscriber
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class SimilarProductSubscriber extends AbstractEventSubscriber
{
    public static function getSubscribedEvents()
    {
        return [
            'admin.product.pre_form_init' => ['onProductFormAdminInit'],
        ];
    }

    public function onProductFormAdminInit(FormEvent $event)
    {
        $resource = $event->getResource();
        if ($resource instanceof Product) {
            $form    = $event->getForm();
            $builder = $event->getFormBuilder();

            $similarProductData = $form->addChild($builder->getElement('nested_fieldset', [
                'name'  => 'similar_products',
                'label' => 'similar_product.fieldset.products',
            ]));

            $similarProductData->addChild($builder->getElement('datagrid_select', [
                'name'             => 'similarProducts',
                'label'            => $this->trans('similar_product.fieldset.products'),
                'key'              => 'id',
                'columns'          => $this->getProductColumns(),
                'selected_columns' => $this->getProductColumns(),
                'load_route'       => 'admin.product.grid',
                'repeat_min'       => 0,
                'repeat_max'       => 50,
                'transformer'      => $builder->getRepositoryTransformer('collection', $this->container->get('product.repository')),
            ]));
        }
    }
    
    protected function getProductColumns(): array
    {
        return [
            [
                'id'         => 'id',
                'caption'    => $this->trans('common.label.id'),
                'sorting'    => [
                    'default_order' => 'asc',
                ],
                'appearance' => [
                    'width'   => 90,
                    'visible' => false,
                ],
                'filter'     => [
                    'type' => 2,
                ],
            ],
            [
                'id'         => 'name',
                'caption'    => $this->trans('common.label.name'),
                'appearance' => [
                    'width' => 200,
                ],
                'filter'     => [
                    'type' => 1,
                ],
            ],
            [
                'id'         => 'sku',
                'caption'    => $this->trans('common.label.sku'),
                'appearance' => [
                    'width' => 0,
                ],
                'filter'     => [
                    'type' => 1,
                ],
            ],
            [
                'id'         => 'barcode',
                'caption'    => $this->trans('product.label.barcode'),
                'appearance' => [
                    'width' => 0,
                ],
                'filter'     => [
                    'type' => 2,
                ],
            ],
        ];
    }
}

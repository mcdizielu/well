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

use Doctrine\Common\Collections\ArrayCollection;
use WellCommerce\Bundle\CatalogBundle\Entity\Product;
use WellCommerce\Bundle\CoreBundle\Doctrine\Event\EntityEvent;
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
            'product.post_init'           => ['onProductInit'],
        ];
    }
    
    public function onProductInit(EntityEvent $event)
    {
        $entity = $event->getEntity();
        if ($entity instanceof Product) {
            $entity->setSimilarProducts(new ArrayCollection());
        }
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
            
            $similarProductData->addChild($builder->getElement('product_select', [
                'name'        => 'similarProducts',
                'label'       => 'similar_product.fieldset.products',
                'load_route'  => 'admin.product.grid',
                'repeat_min'  => 0,
                'repeat_max'  => 50,
                'transformer' => $builder->getRepositoryTransformer('collection', $this->container->get('product.repository')),
            ]));
        }
    }
}

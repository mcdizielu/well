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

namespace WellCommerce\Bundle\CatalogBundle\Controller\Box;

use Symfony\Component\HttpFoundation\Response;
use WellCommerce\Bundle\CoreBundle\Controller\Box\AbstractBoxController;
use WellCommerce\Component\Layout\Collection\LayoutBoxSettingsCollection;
use WellCommerce\Component\DataSet\Conditions\Condition\Eq;
use WellCommerce\Component\DataSet\Conditions\ConditionsCollection;

/**
 * Class ProducerCollectionMenuBoxController
 *
 * @author  Rafał Martonik <rafal@wellcommerce.org>
 */
class ProducerCollectionMenuBoxController extends AbstractBoxController
{
    /**
     * {@inheritdoc}
     */
    public function indexAction(LayoutBoxSettingsCollection $boxSettings) : Response
    {
        $conditions          = $this->getCurrentProducerCollectionMenuConditions();
        $producerCollections = $this->get('producer_collection.dataset.front')->getResult('array', ['conditions' => $conditions],
            ['pagination' => false]);
        
        return $this->displayTemplate('index', [
            'producerCollections'      => $producerCollections,
            'activeProducerCollection' => $this->getProducerCollectionStorage()->getCurrentProducerCollection(),
            'producer'                 => $this->getProducerCollectionStorage()->getCurrentProducerCollection()->getProducer(),
        ]);
    }
    
    /**
     * @return ConditionsCollection
     */
    protected function getCurrentProducerCollectionMenuConditions() : ConditionsCollection
    {
        $conditions = new ConditionsCollection();
        $conditions->add(new Eq('producerId',
            $this->getProducerCollectionStorage()->getCurrentProducerCollection()->getProducer()->getId()));
        
        return $conditions;
    }
    
}

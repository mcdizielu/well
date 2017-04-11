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

namespace WellCommerce\Bundle\CatalogBundle\Request\Storage;

use WellCommerce\Bundle\CatalogBundle\Entity\ProducerCollection;

/**
 * Class ProducerCollectionStorage
 *
 * @author  Rafał Martonik <rafal@wellcommerce.org>
 */
class ProducerCollectionStorage implements ProducerCollectionStorageInterface
{
    /**
     * @var ProducerCollection
     */
    protected $currentProducerCollection;
    
    /**
     * {@inheritdoc}
     */
    public function setCurrentProducerCollection(ProducerCollection $producerCollection)
    {
        $this->currentProducerCollection = $producerCollection;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getCurrentProducerCollection() : ProducerCollection
    {
        return $this->currentProducerCollection;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getCurrentProducerCollectionIdentifier() : int
    {
        return $this->getCurrentProducerCollection()->getId();
    }
    
    /**
     * {@inheritdoc}
     */
    public function hasCurrentProducerCollection() : bool
    {
        return $this->currentProducerCollection instanceof ProducerCollection;
    }
    
}

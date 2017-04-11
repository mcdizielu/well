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
 * Interface ProducerCollectionStorageInterface
 *
 * @author  Rafał Martonik <rafal@wellcommerce.org>
 */
interface ProducerCollectionStorageInterface
{
    /**
     * @param ProducerCollection $producer
     */
    public function setCurrentProducerCollection(ProducerCollection $producer);
    
    /**
     * @return ProducerCollection
     */
    public function getCurrentProducerCollection() : ProducerCollection;
    
    /**
     * @return int
     */
    public function getCurrentProducerCollectionIdentifier() : int;
    
    /**
     * @return bool
     */
    public function hasCurrentProducerCollection() : bool;
}

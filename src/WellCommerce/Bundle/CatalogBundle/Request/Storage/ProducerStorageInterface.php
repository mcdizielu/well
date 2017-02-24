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

use WellCommerce\Bundle\CatalogBundle\Entity\Producer;

/**
 * Interface ProducerStorageInterface
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
interface ProducerStorageInterface
{
    public function setCurrentProducer(Producer $producer);
    
    public function getCurrentProducer(): Producer;
    
    public function getCurrentProducerIdentifier(): int;
    
    public function hasCurrentProducer(): bool;
}

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
 * Class ProducerStorage
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ProducerStorage implements ProducerStorageInterface
{
    /**
     * @var Producer
     */
    protected $currentProducer;

    /**
     * {@inheritdoc}
     */
    public function setCurrentProducer(Producer $producer)
    {
        $this->currentProducer = $producer;
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrentProducer() : Producer
    {
        return $this->currentProducer;
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrentProducerIdentifier() : int
    {
        return $this->getCurrentProducer()->getId();
    }

    /**
     * {@inheritdoc}
     */
    public function hasCurrentProducer() : bool
    {
        return $this->currentProducer instanceof Producer;
    }

}

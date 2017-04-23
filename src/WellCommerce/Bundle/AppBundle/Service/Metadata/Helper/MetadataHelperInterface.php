<?php

namespace WellCommerce\Bundle\AppBundle\Service\Metadata\Helper;

use WellCommerce\Bundle\AppBundle\Entity\Meta;

/**
 * Interface MetadataHelperInterface
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
interface MetadataHelperInterface
{
    public function setMetadata(Meta $meta);
    
    public function setFallbackMetadata(Meta $meta);
    
    public function getMetadata(): Meta;
}

<?php

namespace WellCommerce\Bundle\AppBundle\Entity;

use Knp\DoctrineBehaviors\Model\Blameable\Blameable;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use WellCommerce\Bundle\CoreBundle\Doctrine\Behaviours\Identifiable;
use WellCommerce\Bundle\CoreBundle\Entity\EntityInterface;

/**
 * Class SystemConfiguration
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class SystemConfiguration implements EntityInterface
{
    use Identifiable;
    use Timestampable;
    use Blameable;
    
    private $paramName  = '';
    private $paramValue = '';
    private $nodeName   = '';
    
    public function getParamName(): string
    {
        return $this->paramName;
    }
    
    public function setParamName(string $paramName)
    {
        $this->paramName = $paramName;
    }
    
    public function getParamValue(): string
    {
        return $this->paramValue;
    }
    
    public function setParamValue(string $paramValue)
    {
        $this->paramValue = $paramValue;
    }
    
    public function getNodeName(): string
    {
        return $this->nodeName;
    }
    
    public function setNodeName(string $nodeName)
    {
        $this->nodeName = $nodeName;
    }
}

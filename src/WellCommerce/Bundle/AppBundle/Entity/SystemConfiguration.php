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
    
    private $name       = '';
    private $parameters = [];
    
    public function getName() : string
    {
        return $this->name;
    }
    
    public function setName(string $name)
    {
        $this->name = $name;
    }

    public function getParameters() : array
    {
        return $this->parameters;
    }

    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;
    }
}

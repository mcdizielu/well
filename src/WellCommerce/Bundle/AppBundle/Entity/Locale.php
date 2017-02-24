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

namespace WellCommerce\Bundle\AppBundle\Entity;

use Knp\DoctrineBehaviors\Model\Blameable\Blameable;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use WellCommerce\Bundle\CoreBundle\Doctrine\Behaviours\Enableable;
use WellCommerce\Bundle\CoreBundle\Doctrine\Behaviours\Identifiable;
use WellCommerce\Bundle\CoreBundle\Entity\EntityInterface;

/**
 * Class Locale
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class Locale implements EntityInterface
{
    use Identifiable;
    use Enableable;
    use Timestampable;
    use Blameable;
    
    protected $code = '';
    
    /**
     * @var Currency
     */
    protected $currency;
    
    public function getCode(): string
    {
        return $this->code;
    }
    
    public function setCode(string $code)
    {
        $this->code = $code;
    }
    
    public function getCurrency()
    {
        return $this->currency;
    }
    
    public function setCurrency(Currency $currency = null)
    {
        $this->currency = $currency;
    }
}

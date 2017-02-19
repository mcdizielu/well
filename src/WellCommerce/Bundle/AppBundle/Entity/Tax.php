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
use Knp\DoctrineBehaviors\Model\Translatable\Translatable;
use WellCommerce\Bundle\CoreBundle\Doctrine\Behaviours\Identifiable;
use WellCommerce\Bundle\CoreBundle\Entity\EntityInterface;

/**
 * Class Tax
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class Tax implements EntityInterface
{
    use Identifiable;
    use Translatable;
    use Timestampable;
    use Blameable;
    
    protected $value = 0.00;
    
    public function getValue(): float
    {
        return $this->value;
    }
    
    public function setValue(float $value)
    {
        $this->value = $value;
    }
    
    public function translate($locale = null, $fallbackToDefault = true): TaxTranslation
    {
        return $this->doTranslate($locale, $fallbackToDefault);
    }
}

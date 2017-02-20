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
namespace WellCommerce\Bundle\OrderBundle\Entity;

use Knp\DoctrineBehaviors\Model\Blameable\Blameable;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Knp\DoctrineBehaviors\Model\Translatable\Translatable;
use WellCommerce\Bundle\CoreBundle\Doctrine\Behaviours\Identifiable;
use WellCommerce\Bundle\CoreBundle\Entity\EntityInterface;

/**
 * Class OrderStatusGroup
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class OrderStatusGroup implements EntityInterface
{
    use Identifiable;
    use Timestampable;
    use Blameable;
    use Translatable;
    
    public function translate($locale = null, $fallbackToDefault = true): OrderStatusGroupTranslation
    {
        return $this->doTranslate($locale, $fallbackToDefault);
    }
}

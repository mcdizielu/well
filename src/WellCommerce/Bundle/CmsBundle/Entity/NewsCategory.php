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

namespace WellCommerce\Bundle\CmsBundle\Entity;

use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Knp\DoctrineBehaviors\Model\Translatable\Translatable;
use WellCommerce\Bundle\CoreBundle\Doctrine\Behaviours\Identifiable;
use WellCommerce\Bundle\CoreBundle\Entity\EntityInterface;

/**
 * Class NewsCategory
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class NewsCategory implements EntityInterface
{
    use Identifiable;
    use Translatable;
    use Timestampable;
    
    public function translate($locale = null, $fallbackToDefault = true): NewsCategoryTranslation
    {
        return $this->doTranslate($locale, $fallbackToDefault);
    }
}

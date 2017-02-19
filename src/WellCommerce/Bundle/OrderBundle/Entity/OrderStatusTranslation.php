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

use Knp\DoctrineBehaviors\Model\Translatable\Translation;
use WellCommerce\Bundle\CoreBundle\Entity\AbstractTranslation;

/**
 * Class OrderStatusTranslation
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class OrderStatusTranslation extends AbstractTranslation
{
    use Translation;
    
    protected $name           = '';
    protected $defaultComment = '';
    
    public function getName(): string
    {
        return $this->name;
    }
    
    public function setName(string $name)
    {
        $this->name = $name;
    }
    
    public function getDefaultComment(): string
    {
        return $this->defaultComment;
    }
    
    public function setDefaultComment(string $defaultComment)
    {
        $this->defaultComment = $defaultComment;
    }
}

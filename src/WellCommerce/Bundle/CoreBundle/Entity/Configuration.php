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

namespace WellCommerce\Bundle\CoreBundle\Entity;

use WellCommerce\Bundle\AppBundle\Entity\ShopAwareTrait;
use WellCommerce\Bundle\CoreBundle\Doctrine\Behaviours\Identifiable;

/**
 * Class Configuration
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class Configuration implements EntityInterface
{
    use Identifiable;
    
    protected $type    = '';
    protected $path    = '';
    protected $label   = '';
    protected $comment = '';
    protected $value   = '';
    
    public function getType(): string
    {
        return $this->type;
    }
    
    public function setType(string $type)
    {
        $this->type = $type;
    }
    
    public function getPath(): string
    {
        return $this->path;
    }
    
    public function setPath(string $path)
    {
        $this->path = $path;
    }
    
    public function getLabel(): string
    {
        return $this->label;
    }
    
    public function setLabel(string $label)
    {
        $this->label = $label;
    }
    
    public function getComment(): string
    {
        return $this->comment;
    }
    
    public function setComment(string $comment)
    {
        $this->comment = $comment;
    }
    
    public function getValue(): string
    {
        return $this->value;
    }
    
    public function setValue(string $value)
    {
        $this->value = $value;
    }
}

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

namespace WellCommerce\Bundle\CoreBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Class UniqueRoute
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class UniqueRoute extends Constraint
{
    public $message    = 'This value is already used in {{ type }}. Follow <a target="_blank" href="{{ url }}">this link</a>';
    public $service    = 'routing.orm.validator.unique';
    public $fields     = [];
    public $errorPath  = null;
    public $ignoreNull = true;
    
    public function validatedBy()
    {
        return $this->service;
    }

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}

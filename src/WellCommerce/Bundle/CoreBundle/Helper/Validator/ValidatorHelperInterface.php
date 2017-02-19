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

namespace WellCommerce\Bundle\CoreBundle\Helper\Validator;

use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * Interface ValidatorHelperInterface
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
interface ValidatorHelperInterface
{
    const DEFAULT_VALIDATOR_GROUPS = ['Default'];
    
    public function validate($value, $constraints = null, array $groups = []): ConstraintViolationListInterface;
    
    public function isValid($value, $constraints = null, array $groups = []): bool;
}

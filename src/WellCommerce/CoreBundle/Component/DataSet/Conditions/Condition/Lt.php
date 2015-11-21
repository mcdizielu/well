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

namespace WellCommerce\CoreBundle\Component\DataSet\Conditions\Condition;

use WellCommerce\CoreBundle\Component\DataSet\Conditions\AbstractCondition;
use WellCommerce\CoreBundle\Component\DataSet\Conditions\ConditionInterface;

/**
 * Class Lt
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class Lt extends AbstractCondition implements ConditionInterface
{
    protected $operator = 'lt';
}

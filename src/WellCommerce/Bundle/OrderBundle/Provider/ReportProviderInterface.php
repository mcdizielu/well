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

namespace WellCommerce\Bundle\OrderBundle\Provider;

use DateTime;
use WellCommerce\Bundle\OrderBundle\Context\LineChartContext;

/**
 * Interface ReportProviderInterface
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
interface ReportProviderInterface
{
    public function getSummary(DateTime $startDate, DateTime $endDate);
    
    public function getChartData(DateTime $startDate, DateTime $endDate): LineChartContext;
}

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

namespace WellCommerce\Bundle\AppBundle\Copier;

use WellCommerce\Bundle\AppBundle\Entity\Locale;

/**
 * Interface LocaleCopierInterface
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
interface LocaleCopierInterface
{
    public function copyLocaleData(Locale $sourceLocale, Locale $targetLocale);
}

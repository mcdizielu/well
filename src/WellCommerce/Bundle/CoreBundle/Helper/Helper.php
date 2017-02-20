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
namespace WellCommerce\Bundle\CoreBundle\Helper;

use Knp\DoctrineBehaviors\Model\Sluggable\Transliterator;

/**
 * Class Helper
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class Helper
{
    public static function snake(string $value, string $delimiter = '_'): string
    {
        $replace = '$1' . $delimiter . '$2';
        
        return ctype_lower($value) ? $value : strtolower(preg_replace('/(.)([A-Z])/', $replace, $value));
    }
    
    public static function urlize(string $text, string $delimiter = '-'): string
    {
        return Transliterator::urlize($text, $delimiter);
    }
}

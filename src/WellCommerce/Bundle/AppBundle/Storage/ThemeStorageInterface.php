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

namespace WellCommerce\Bundle\AppBundle\Storage;

use WellCommerce\Bundle\AppBundle\Entity\Theme;


/**
 * Interface ThemeStorageInterface
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
interface ThemeStorageInterface
{
    public function setCurrentTheme(Theme $theme);
    
    public function getCurrentTheme(): Theme;
    
    public function getCurrentThemeFolder(): string;
    
    public function hasCurrentTheme(): bool;
}

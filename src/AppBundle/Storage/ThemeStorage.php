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
 * Class ThemeStorage
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ThemeStorage implements ThemeStorageInterface
{
    /**
     * @var Theme
     */
    protected $currentTheme;
    
    public function setCurrentTheme(Theme $theme)
    {
        $this->currentTheme = $theme;
    }
    
    public function getCurrentTheme(): Theme
    {
        return $this->currentTheme;
    }
    
    public function getCurrentThemeFolder(): string
    {
        return $this->currentTheme->getFolder();
    }
    
    public function hasCurrentTheme(): bool
    {
        return $this->currentTheme instanceof Theme;
    }
    
}

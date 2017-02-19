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

namespace WellCommerce\Bundle\CmsBundle\Request;

use WellCommerce\Bundle\CmsBundle\Entity\News;

/**
 * Class NewsContext
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class NewsRequestStorage
{
    protected $currentNews;
    
    public function setCurrentNews(News $news)
    {
        $this->currentNews = $news;
    }
    
    public function getCurrentNews(): News
    {
        return $this->currentNews;
    }
    
    public function hasCurrentNews(): bool
    {
        return $this->currentNews instanceof News;
    }
}

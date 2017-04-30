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

namespace WellCommerce\Bundle\CmsBundle\Twig;

use WellCommerce\Bundle\CoreBundle\Helper\Router\RouterHelperInterface;
use WellCommerce\Component\DataSet\DataSetInterface;

/**
 * Class PageExtension
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class PageExtension extends \Twig_Extension
{
    /**
     * @var DataSetInterface
     */
    protected $dataset;
    
    /**
     * @var RouterHelperInterface
     */
    protected $routerHelper;
    
    public function __construct(DataSetInterface $dataset, RouterHelperInterface $routerHelper)
    {
        $this->dataset      = $dataset;
        $this->routerHelper = $routerHelper;
    }
    
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('cmsPages', [$this, 'getCmsPages'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('getPageUrl', [$this, 'getPageUrl'], ['is_safe' => ['html']]),
        ];
    }
    
    public function getPageUrl(array $page): string
    {
        if (null !== $page['redirectRoute']) {
            return $this->routerHelper->generateUrl($page['redirectRoute']);
        }
        
        if (null !== $page['redirectUrl']) {
            return $page['redirectUrl'];
        }
        
        return $page['route'];
    }
    
    public function getCmsPages(): array
    {
        return $this->dataset->getResult('tree', [
            'order_by' => 'hierarchy',
        ]);
    }
    
    public function getName()
    {
        return 'cms_page';
    }
}

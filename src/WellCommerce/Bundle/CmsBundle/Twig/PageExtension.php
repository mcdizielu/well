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
    
    public function __construct(DataSetInterface $dataset)
    {
        $this->dataset = $dataset;
    }
    
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('cmsPages', [$this, 'getCmsPages'], ['is_safe' => ['html']]),
        ];
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

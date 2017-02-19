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
class NewsExtension extends \Twig_Extension
{
    /**
     * @var DataSetInterface
     */
    protected $dataset;
    
    /**
     * Constructor
     *
     * @param DataSetInterface $dataset
     */
    public function __construct(DataSetInterface $dataset)
    {
        $this->dataset = $dataset;
    }
    
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('latestNews', [$this, 'getNews'], ['is_safe' => ['html']]),
        ];
    }
    
    public function getNews(int $limit = 4): array
    {
        return $this->dataset->getResult('array', [
            'order_by'  => 'startDate',
            'order_dir' => 'desc',
            'limit'     => $limit,
        ]);
    }
    
    public function getName()
    {
        return 'news';
    }
}

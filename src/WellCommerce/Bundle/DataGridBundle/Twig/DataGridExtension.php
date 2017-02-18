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
namespace WellCommerce\Bundle\DataGridBundle\Twig;

use WellCommerce\Component\DataGrid\DataGridInterface;

/**
 * Class DataGridExtension
 *
 * @author Adam Piotrowski <adam@wellcommerce.org>
 */
final class DataGridExtension extends \Twig_Extension
{
    /**
     * @var string
     */
    private $templateName;
    
    /**
     * @var \Twig_Environment
     */
    private $environment;
    
    public function __construct($templateName, \Twig_Environment $environment)
    {
        $this->templateName = $templateName;
        $this->environment  = $environment;
    }
    
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('datagrid_renderer', [$this, 'render'], ['is_safe' => ['html', 'javascript']]),
            new \Twig_SimpleFunction('render_datagrid_options', [$this, 'renderOptions'], ['is_safe' => ['html', 'javascript']]),
        ];
    }
    
    public function render(DataGridInterface $datagrid): string
    {
        return $this->environment->render($this->templateName, [
            'datagrid' => $datagrid,
        ]);
    }
    
    public function renderOptions($options): string
    {
        return $options;
    }
    
    public function getName()
    {
        return 'datagrid_renderer';
    }
}

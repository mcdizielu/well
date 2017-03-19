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
namespace WellCommerce\Bundle\AppBundle\Twig;

use WellCommerce\Bundle\CoreBundle\Doctrine\Repository\RepositoryInterface;
use WellCommerce\Component\Layout\Model\LayoutBoxInterface;
use WellCommerce\Component\Layout\Renderer\LayoutBoxRendererInterface;

/**
 * Class LayoutBoxExtension
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class LayoutBoxExtension extends \Twig_Extension
{
    /**
     * @var LayoutBoxRendererInterface
     */
    private $renderer;
    
    /**
     * @var RepositoryInterface
     */
    private $repository;
    
    public function __construct(LayoutBoxRendererInterface $renderer, RepositoryInterface $repository)
    {
        $this->renderer   = $renderer;
        $this->repository = $repository;
    }
    
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('layout_box', [$this, 'getLayoutBoxContent'], ['is_safe' => ['html']]),
        ];
    }
    
    public function getLayoutBoxContent($identifier, $params = []): string
    {
        $layoutBox = $this->repository->findOneBy(['identifier' => $identifier]);
        
        if ($layoutBox instanceof LayoutBoxInterface) {
            return $this->renderer->render($layoutBox, $params);
        }
        
        return '';
    }
    
    public function getName()
    {
        return 'layout_box';
    }
}

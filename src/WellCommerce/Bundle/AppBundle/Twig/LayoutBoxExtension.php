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

use WellCommerce\Bundle\AppBundle\Entity\Client;
use WellCommerce\Bundle\AppBundle\Entity\LayoutBox;
use WellCommerce\Bundle\CoreBundle\Doctrine\Repository\RepositoryInterface;
use WellCommerce\Bundle\CoreBundle\Helper\Security\SecurityHelperInterface;
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
    
    /**
     * @var SecurityHelperInterface
     */
    private $security;
    
    public function __construct(LayoutBoxRendererInterface $renderer, RepositoryInterface $repository, SecurityHelperInterface $security)
    {
        $this->renderer   = $renderer;
        $this->repository = $repository;
        $this->security   = $security;
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
        
        if ($layoutBox instanceof LayoutBox) {
            if ($this->isVisible($layoutBox)) {
                return $this->renderer->render($layoutBox, $params);
            }
        }
        
        return '';
    }
    
    public function getName()
    {
        return 'layout_box';
    }
    
    private function isVisible(LayoutBox $layoutBox): bool
    {
        $clientGroups = $layoutBox->getClientGroups();
        $client       = $this->security->getCurrentClient();
        
        if ($clientGroups->count()) {
            if ($client instanceof Client) {
                $clientGroup = $client->getClientGroup();
                if ($clientGroups->contains($clientGroup)) {
                    return true;
                }
            }
    
            return false;
        }
        
        return true;
    }
}

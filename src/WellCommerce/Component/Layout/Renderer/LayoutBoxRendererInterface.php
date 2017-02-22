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

namespace WellCommerce\Component\Layout\Renderer;

use WellCommerce\Component\Layout\Model\LayoutBoxInterface;

/**
 * Interface LayoutBoxRendererInterface
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
interface LayoutBoxRendererInterface
{
    public function render(LayoutBoxInterface $layoutBox, array $params): string;
}

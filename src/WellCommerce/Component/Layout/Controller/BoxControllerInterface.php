<?php


namespace WellCommerce\Component\Layout\Controller;

use Symfony\Component\HttpFoundation\Response;
use WellCommerce\Component\Layout\Collection\LayoutBoxSettingsCollection;

/**
 * Interface BoxControllerInterface
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
interface BoxControllerInterface
{
    public function indexAction(LayoutBoxSettingsCollection $boxSettings): Response;
}

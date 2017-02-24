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

namespace WellCommerce\Bundle\RoutingBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use WellCommerce\Bundle\CoreBundle\Controller\ControllerInterface;
use WellCommerce\Bundle\RoutingBundle\Generator\SlugGeneratorInterface;

/**
 * Class RouteController
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class RouteController implements ControllerInterface
{
    /**
     * @var SlugGeneratorInterface
     */
    private $generator;
    
    public function __construct(SlugGeneratorInterface $generator)
    {
        $this->generator = $generator;
    }
    
    public function generateAction(Request $request): JsonResponse
    {
        $slug = $this->generator->generate(
            $request->get('name'),
            $request->get('id'),
            $request->get('locale'),
            $request->get('fields')
        );
        
        return new JsonResponse([
            'slug' => $slug,
        ]);
    }
}

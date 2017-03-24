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

namespace WellCommerce\Bundle\CoreBundle\Helper\Process;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\ProcessBuilder;

/**
 * Interface ProcessHelperInterface
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
interface ProcessHelperInterface
{
    /**
     * Creates a Process instance
     *
     * @param array $arguments
     * @param int   $timeout
     *
     * @return Process
     */
    public function createProcess(array $arguments, int $timeout = 720): Process;
    
    /**
     * Creates a ProcessBuilder instance
     *
     * @param array $arguments
     * @param int   $timeout
     *
     * @return ProcessBuilder
     */
    public function createProcessBuilder(array $arguments, $timeout = 720): ProcessBuilder;
}

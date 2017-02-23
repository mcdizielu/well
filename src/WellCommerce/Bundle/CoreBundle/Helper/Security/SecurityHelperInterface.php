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

namespace WellCommerce\Bundle\CoreBundle\Helper\Security;

use Symfony\Component\HttpFoundation\Request;
use WellCommerce\Bundle\AppBundle\Entity\Client;
use WellCommerce\Bundle\AppBundle\Entity\User;

/**
 * Interface SecurityHelperInterface
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
interface SecurityHelperInterface
{
    public function getCurrentUser();
    
    public function getCurrentClient();
    
    public function getCurrentAdmin();
    
    public function getAuthenticatedClient(): Client;
    
    public function getAuthenticatedAdmin(): User;
    
    public function isActiveFirewall(string $name): bool;
    
    public function isActiveAdminFirewall(): bool;
    
    public function getFirewallNameForRequest(Request $request);
    
    public function generateRandomPassword(int $length = 8): string;
}

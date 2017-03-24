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

namespace WellCommerce\Bundle\CoreBundle\Helper\Package;

use Packagist\Api\Client;
use Packagist\Api\Result\Package as PackagistPackage;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class PackageHelper
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class PackageHelper implements PackageHelperInterface
{
    /**
     * @var Client
     */
    protected $client;
    
    public function __construct(Client $client)
    {
        $this->client             = $client;
    }
    
    public function getPackages(array $criteria)
    {
        $this->client->setPackagistUrl(self::PACKAGIST_URL);
        
        return $this->client->all($criteria);
    }
    
    public function getPackage(string $name): PackagistPackage
    {
        return $this->client->get($name);
    }
}

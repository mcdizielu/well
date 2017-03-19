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

namespace WellCommerce\Bundle\AppBundle\Manager;

use ComposerRevisions\Revisions;
use Doctrine\ORM\EntityNotFoundException;
use Packagist\Api\Result\Package as RemotePackage;
use Symfony\Component\HttpFoundation\Request;
use WellCommerce\Bundle\AppBundle\Entity\Package;
use WellCommerce\Bundle\CoreBundle\Helper\Package\PackageHelperInterface;
use WellCommerce\Bundle\CoreBundle\Manager\AbstractManager;

/**
 * Class PackageManager
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class PackageManager extends AbstractManager
{
    /**
     * Searches for all packages of particular type and adds them to Smuggler
     */
    public function syncPackages($type)
    {
        $searchResults = $this->getHelper()->getPackages(['type' => $type]);
        
        foreach ($searchResults as $result) {
            $package = $this->getHelper()->getPackage($result);
            $this->syncPackage($package);
        }
        
        $this->getEntityManager()->flush();
    }
    
    protected function syncPackage(RemotePackage $remotePackage)
    {
        $repository   = $this->getRepository();
        $localPackage = $repository->findOneBy(['fullName' => $remotePackage->getName()]);
        if (!$localPackage instanceof Package) {
            $this->addPackage($remotePackage);
        } else {
            $this->setPackageVersions($localPackage);
            $this->getDoctrineHelper()->getEntityManager()->flush();
        }
    }
    
    protected function addPackage(RemotePackage $remotePackage)
    {
        list($vendor, $name) = explode('/', $remotePackage->getName());
        $package = new Package();
        $package->setFullName($remotePackage->getName());
        $package->setName($name);
        $package->setVendor($vendor);
        $this->setPackageVersions($package);
        $this->getDoctrineHelper()->getEntityManager()->persist($package);
    }
    
    public function getConsoleCommandArguments(Request $request)
    {
        $port      = (int)$request->attributes->get('port');
        $package   = $request->attributes->get('id');
        $operation = $request->attributes->get('operation');
        
        return [
            'app/console',
            'wellcommerce:package:' . $operation,
            '--port=' . $port,
            '--package=' . $package,
        ];
    }
    
    public function changePackageStatus(Request $request)
    {
        $id         = $request->attributes->get('id');
        $em         = $this->getDoctrineHelper()->getEntityManager();
        $repository = $this->getRepository();
        $package    = $repository->find($id);
        
        if (null === $package) {
            throw new EntityNotFoundException($repository->getMetaData()->getName(), $id);
        }
        
        $this->setPackageVersions($package);
        
        $em->flush();
    }
    
    protected function setPackageVersions(Package $package)
    {
        $branch        = PackageHelperInterface::DEFAULT_BRANCH_VERSION;
        $remotePackage = $this->getHelper()->getPackage($package->getFullName());
        $remoteVersion = $this->getPackageVersionReference($remotePackage->getVersions()[$branch]);
        $localVersion  = '';
        
        if (isset(Revisions::$byName[$package->getFullName()])) {
            $localVersion = Revisions::$byName[$package->getFullName()];
        }
        
        $package->setLocalVersion($localVersion);
        $package->setRemoteVersion($remoteVersion);
    }
    
    protected function getPackageVersionReference(RemotePackage\Version $version): string
    {
        return $version->getSource()->getReference();
    }
    
    private function getHelper(): PackageHelperInterface
    {
        return $this->get('package.helper');
    }
}

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

namespace WellCommerce\Bundle\AppBundle\Command\Admin;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Config\Util\XmlUtils;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\HttpKernel\KernelInterface;
use WellCommerce\Bundle\AppBundle\Entity\AdminMenu;
use WellCommerce\Bundle\CoreBundle\Doctrine\Repository\RepositoryInterface;

/**
 * Class RefreshMenuCommand
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class RefreshMenuCommand extends ContainerAwareCommand
{
    /**
     * @var EntityManager
     */
    private $em;
    
    /**
     * @var RepositoryInterface
     */
    private $repository;
    
    protected function configure()
    {
        $this->setDescription('Refreshes the admin menu');
        $this->setName('wellcommerce:admin:menu-refresh');
    }
    
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->em         = $this->getContainer()->get('doctrine.helper')->getEntityManager();
        $this->repository = $this->getContainer()->get('admin_menu.repository');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $files = $this->locateFiles();
        
        foreach ($files as $file) {
            $output->writeln(sprintf('<info>Reimporting %s</info>', $file->getRealPath()));
            $this->importFile($file);
        }
    }
    
    private function importFile(SplFileInfo $file)
    {
        $xml = $this->parseFile($file);
        foreach ($xml->documentElement->getElementsByTagName('item') as $item) {
            $dom = simplexml_import_dom($item);
            $this->addMenuItem($dom);
        }
    }
    
    protected function addMenuItem(\SimpleXMLElement $xml)
    {
        $item   = $this->repository->findOneBy(['identifier' => (string)$xml->identifier]);
        $parent = $this->repository->findOneBy(['identifier' => (string)$xml->parent]);
        
        if (!$item instanceof AdminMenu) {
            $item = new AdminMenu();
        }
        
        $item->setCssClass((string)$xml->css_class);
        $item->setIdentifier((string)$xml->identifier);
        $item->setName((string)$xml->name);
        $item->setRouteName((string)$xml->route_name);
        $item->setHierarchy((int)$xml->hierarchy);
        $item->setParent($parent);
        
        $this->em->persist($item);
        $this->em->flush();
    }
    
    /**
     * @return array|SplFileInfo
     */
    private function locateFiles(): array
    {
        $filesystem = new Filesystem();
        $files      = [];
        
        foreach ($this->getKernel()->getBundles() as $bundle) {
            $directory = $bundle->getPath() . '/Resources/config/admin_menu';
            if (false === $filesystem->exists($directory)) {
                continue;
            }
            
            $finder = new Finder();
            
            /** @var SplFileInfo $file */
            foreach ($finder->files()->name('*.xml')->in($directory) as $file) {
                $files[] = $file;
            }
        }
        
        return $files;
    }
    
    private function parseFile(SplFileInfo $file): \DOMDocument
    {
        return XmlUtils::loadFile($file->getRealPath());
    }
    
    private function getKernel(): KernelInterface
    {
        return $this->getContainer()->get('kernel');
    }
}

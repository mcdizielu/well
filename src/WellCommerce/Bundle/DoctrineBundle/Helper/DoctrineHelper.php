<?php
/**
 * WellCommerce Open-Source E-Commerce Platform
 *
 * This file is part of the WellCommerce package.
 *
 * (c) Adam Piotrowski <adam@wellcommerce.org>
 *
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */

namespace WellCommerce\Bundle\DoctrineBundle\Helper;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\Common\Persistence\Mapping\ClassMetadataFactory;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Filter\SQLFilter;
use Doctrine\ORM\Query\FilterCollection;

/**
 * Class DoctrineHelper
 *
 * @author Adam Piotrowski <adam@wellcommerce.org>
 */
final class DoctrineHelper implements DoctrineHelperInterface
{
    /**
     * @var ManagerRegistry
     */
    private $registry;
    
    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }
    
    public function disableFilter(string $filter)
    {
        $this->getDoctrineFilters()->disable($filter);
    }
    
    public function enableFilter(string $filter) : SQLFilter
    {
        return $this->getDoctrineFilters()->enable($filter);
    }
    
    public function getDoctrineFilters() : FilterCollection
    {
        return $this->getEntityManager()->getFilters();
    }
    
    public function getEntityManager() : EntityManagerInterface
    {
        return $this->registry->getManager();
    }
    
    public function getClassMetadata(string $className) : ClassMetadata
    {
        return $this->getEntityManager()->getClassMetadata($className);
    }
    
    public function getClassMetadataForEntity($object) : ClassMetadata
    {
        return $this->getClassMetadata($this->getRealClass($object));
    }
    
    public function getAllMetadata() : array
    {
        return $this->getMetadataFactory()->getAllMetadata();
    }
    
    public function hasClassMetadataForEntity($object) : bool
    {
        return $this->getMetadataFactory()->hasMetadataFor($this->getRealClass($object));
    }
    
    public function getMetadataFactory() : ClassMetadataFactory
    {
        return $this->getEntityManager()->getMetadataFactory();
    }

    private function getRealClass($object) : string
    {
        return ClassUtils::getRealClass(get_class($object));
    }
}

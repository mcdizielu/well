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
namespace WellCommerce\Bundle\DoctrineBundle\Repository;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityRepository as BaseEntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\QueryBuilder;
use WellCommerce\Bundle\CoreBundle\Helper\Helper;

/**
 * Class EntityRepository
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class EntityRepository extends BaseEntityRepository implements RepositoryInterface
{
    public function getMetadata(): ClassMetadata
    {
        return $this->_class;
    }
    
    public function getAlias(): string
    {
        $parts      = explode('\\', $this->getEntityName());
        $entityName = end($parts);
        
        return Helper::snake($entityName);
    }
    
    public function getQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder($this->getAlias());
    }
    
    public function getCollection(Criteria $criteria = null): Collection
    {
        if (null === $criteria) {
            $criteria = new Criteria();
        }
        
        return $this->matching($criteria);
    }
}

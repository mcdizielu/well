<?php

namespace WellCommerce\Component\DataSet\Event;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\EventDispatcher\Event;

/**
 * Class DataSetQueryBuilderEvent
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class DataSetQueryBuilderEvent extends Event
{
    const EVENT_SUFFIX = 'dataset.query_builder.create';
    
    /**
     * @var QueryBuilder
     */
    private $queryBuilder;
    
    public function __construct(QueryBuilder $queryBuilder)
    {
        $this->queryBuilder = $queryBuilder;
    }
    
    public function getQueryBuilder(): QueryBuilder
    {
        return $this->queryBuilder;
    }
}

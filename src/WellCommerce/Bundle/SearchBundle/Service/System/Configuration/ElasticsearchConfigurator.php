<?php

namespace WellCommerce\Bundle\SearchBundle\Service\System\Configuration;

use WellCommerce\Bundle\AppBundle\Service\System\Configuration\AbstractSystemConfigurator;
use WellCommerce\Component\Search\Adapter\ElasticSearch\ElasticSearchQueryBuilder;
use WellCommerce\Component\Search\Adapter\SearchAdapterConfiguratorInterface;

/**
 * Class ElasticSearchConfigurator
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ElasticSearchConfigurator extends AbstractSystemConfigurator implements SearchAdapterConfiguratorInterface
{
    public function getAlias(): string
    {
        return 'elastic_search';
    }
    
    public function saveParameters(array $parameters)
    {
    }

    public function getSearchAdapterOptions(): array
    {
        return [
            'shards'        => 2,
            'replicas'      => 2,
            'indexPrefix'   => $this->kernel->getContainer()->getParameter('search_index_prefix'),
            'termMinLength' => 3,
            'maxResults'    => 100,
            'builderClass'  => ElasticSearchQueryBuilder::class,
        ];
    }
}

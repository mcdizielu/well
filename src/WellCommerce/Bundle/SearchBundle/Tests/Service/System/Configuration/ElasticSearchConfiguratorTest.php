<?php

namespace WellCommerce\Bundle\SearchBundle\Tests\Service\System\Configuration;

use WellCommerce\Bundle\CoreBundle\Test\AbstractTestCase;
use WellCommerce\Component\Search\Adapter\ElasticSearch\ElasticSearchQueryBuilder;

/**
 * Class ElasticSearchConfiguratorTest
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ElasticSearchConfiguratorTest extends AbstractTestCase
{
    public function testGetSearchAdapterOptions()
    {
        $configurator = $this->container->get('elasticsearch.system.configurator');
        $options      = $configurator->getSearchAdapterOptions();

        $this->assertEquals(2, $options['shards']);
        $this->assertEquals(2, $options['replicas']);
        $this->assertEquals(3, $options['termMinLength']);
        $this->assertEquals(100, $options['maxResults']);
        $this->assertEquals(ElasticSearchQueryBuilder::class, $options['builderClass']);
    }
}

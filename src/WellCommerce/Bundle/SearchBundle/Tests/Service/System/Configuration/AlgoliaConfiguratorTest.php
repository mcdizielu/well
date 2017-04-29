<?php

namespace WellCommerce\Bundle\SearchBundle\Tests\Service\System\Configuration;

use WellCommerce\Bundle\CoreBundle\Test\AbstractTestCase;
use WellCommerce\Component\Search\Adapter\Algolia\AlgoliaQueryBuilder;

/**
 * Class AlgoliaConfiguratorTest
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class AlgoliaConfiguratorTest extends AbstractTestCase
{
    public function testGetSearchAdapterOptions()
    {
        $configurator = $this->container->get('algolia.system.configurator');
        $options      = $configurator->getSearchAdapterOptions();

        $this->assertEquals('C2VGT4PGWH', $options['appId']);
        $this->assertEquals('b48fb9f6a4eee57f10e3079af095533b', $options['apiKey']);
        $this->assertEquals(3, $options['termMinLength']);
        $this->assertEquals(100, $options['maxResults']);
        $this->assertEquals(AlgoliaQueryBuilder::class, $options['builderClass']);
    }
}

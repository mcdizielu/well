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

namespace WellCommerce\Bundle\CatalogBundle\Controller\Box;

use Symfony\Component\HttpFoundation\Response;
use WellCommerce\Bundle\CoreBundle\Controller\Box\AbstractBoxController;
use WellCommerce\Component\DataSet\Conditions\Condition\In;
use WellCommerce\Component\DataSet\Conditions\ConditionsCollection;
use WellCommerce\Component\Layout\Collection\LayoutBoxSettingsCollection;

/**
 * Class ProducerMenuBoxController
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ProducerMenuBoxController extends AbstractBoxController
{
    public function indexAction(LayoutBoxSettingsCollection $boxSettings): Response
    {
        $producers = $this->get('producer.dataset.front')->getResult('array', [
            'limit'      => $boxSettings->getParam('limit', 10),
            'order_by'   => 'hierarchy',
            'order_dir'  => 'asc',
            'conditions' => $this->createConditionsCollection($boxSettings->getParam('producers', [])),
        ]);
        
        return $this->displayTemplate('index', [
            'producers'      => $producers,
            'activeProducer' => $this->getProducerStorage()->getCurrentProducerIdentifier(),
            'boxSettings'    => $boxSettings,
        ]);
    }
    
    protected function createConditionsCollection(array $identifiers): ConditionsCollection
    {
        $conditions = new ConditionsCollection();
        
        if (count($identifiers)) {
            $conditions->add(new In('id', $identifiers));
        }
        
        return $conditions;
    }
}

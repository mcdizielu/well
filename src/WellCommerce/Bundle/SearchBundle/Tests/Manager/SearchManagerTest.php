<?php

namespace WellCommerce\Bundle\SearchBundle\Tests\Manager;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use WellCommerce\Bundle\CoreBundle\Entity\EntityInterface;
use WellCommerce\Bundle\CoreBundle\Test\AbstractTestCase;
use WellCommerce\Bundle\SearchBundle\Manager\SearchManagerInterface;
use WellCommerce\Component\Search\Model\FieldInterface;
use WellCommerce\Component\Search\Model\TypeInterface;
use WellCommerce\Component\Search\Request\SearchRequest;
use WellCommerce\Component\Search\Request\SearchRequestInterface;

/**
 * Class SearchManagerTest
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class SearchManagerTest extends AbstractTestCase
{
    public function testSearchAction()
    {
        /** @var SearchManagerInterface $manager */
        $manager  = $this->container->get('search.manager');
        $requests = $this->getSearchRequests();
        $requests->map(function (SearchRequestInterface $request) use ($manager) {
            $result = $manager->search($request);
            $this->assertNotEmpty($result);
        });
    }
    
    public function getSearchRequests(): Collection
    {
        /** @var SearchManagerInterface $manager */
        $manager  = $this->container->get('search.manager');
        $types    = $manager->getTypes();
        $requests = new ArrayCollection();
        
        
        $types->map(function (TypeInterface $type) use ($manager, $requests) {
            $collection = $this->container->get($type->getName() . '.repository')->getCollection();
            $collection->map(function (EntityInterface $entity) use ($type, $requests) {
                $fields   = $type->getFields();
                $language = new ExpressionLanguage();
                
                $fields->map(function (FieldInterface $field) use ($language, $entity) {
                    $value = $this->getFieldValue($field->getValueExpression(), $entity);
                    $field->setValue($value);
                });
                
                $requests->add(new SearchRequest($type, $fields, '', 'en'));
            });
        });
        
        return $requests;
    }
    
    private function getFieldValue(string $expression, EntityInterface $entity): string
    {
        $language = new ExpressionLanguage();
        
        $value = $language->evaluate($expression, [
            'resource' => $entity,
            'locale'   => 'en',
        ]);
        
        return $value ?? '';
    }
}

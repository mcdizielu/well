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

namespace WellCommerce\Component\Search\Adapter\Algolia;

use AlgoliaSearch\Client;
use Symfony\Component\OptionsResolver\OptionsResolver;
use WellCommerce\Component\Search\Adapter\AdapterInterface;
use WellCommerce\Component\Search\Adapter\QueryBuilderInterface;
use WellCommerce\Component\Search\Model\DocumentInterface;
use WellCommerce\Component\Search\Model\FieldInterface;
use WellCommerce\Component\Search\Request\SearchRequestInterface;

/**
 * Class AlgoliaSearchAdapter
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class AlgoliaSearchAdapter implements AdapterInterface
{
    /**
     * @var array
     */
    private $options = [];
    
    /**
     * @var Client
     */
    private $client;
    
    /**
     * ElasticSearchAdapter constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $this->options = $resolver->resolve($options);
    }
    
    public function search(SearchRequestInterface $request): array
    {
        $indexName = $this->getIndexName($request->getLocale(), $request->getType()->getName());
        $index     = $this->getClient()->initIndex($indexName);
        $body      = $this->createQueryBuilder($request)->getQuery();
        $results   = $index->search($request->getPhrase(), [
            'attributesToRetrieve' => $body,
            'hitsPerPage'          => $this->options['result_limit'],
        ]);
        
        return $this->processResults($results);
    }
    
    public function addDocument(DocumentInterface $document)
    {
        $indexName = $this->getIndexName($document->getLocale(), $document->getType()->getName());
        $index     = $this->getClient()->initIndex($indexName);
        
        $index->addObject($this->createDocumentBody($document), $document->getIdentifier());
    }
    
    public function removeDocument(DocumentInterface $document)
    {
        $indexName = $this->getIndexName($document->getLocale(), $document->getType()->getName());
        $index     = $this->getClient()->initIndex($indexName);
        
        $index->deleteObject($document->getIdentifier());
    }
    
    public function updateDocument(DocumentInterface $document)
    {
        $indexName        = $this->getIndexName($document->getLocale(), $document->getType()->getName());
        $index            = $this->getClient()->initIndex($indexName);
        $body             = $this->createDocumentBody($document);
        $body['objectID'] = $document->getIdentifier();
        
        $index->saveObject($body);
    }
    
    public function getIndexName(string $locale, string $type): string
    {
        return sprintf('%s%s_%s', $this->options['index_prefix'], $locale, $type);
    }
    
    public function createIndex(string $locale, string $type)
    {
    }
    
    public function removeIndex(string $locale, string $type)
    {
        $indexName = $this->getIndexName($locale, $type);
        
        return $this->getClient()->deleteIndex($indexName);
    }
    
    public function flushIndex(string $locale, string $type)
    {
        $indexName = $this->getIndexName($locale, $type);
        $index     = $this->getClient()->initIndex($indexName);
        
        return $index->clearIndex();
    }
    
    public function getStats(string $locale)
    {
        return $this->getClient()->getLogs(0, 100);
    }
    
    private function createQueryBuilder(SearchRequestInterface $request): QueryBuilderInterface
    {
        return new $this->options['query_builder_class']($request, $this->options['query_min_length']);
    }
    
    private function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired([
            'app_id',
            'app_key',
            'result_limit',
            'index_prefix',
            'query_builder_class',
        ]);
        
        $resolver->setDefault('query_min_length', 3);
        $resolver->setDefault('result_limit', 100);
        $resolver->setDefault('query_builder_class', AlgoliaQueryBuilder::class);
        
        $resolver->setAllowedTypes('app_id', 'string');
        $resolver->setAllowedTypes('app_key', 'string');
        $resolver->setAllowedTypes('index_prefix', 'string');
        $resolver->setAllowedTypes('query_min_length', 'integer');
        $resolver->setAllowedTypes('result_limit', 'integer');
        $resolver->setAllowedTypes('query_builder_class', 'string');
    }
    
    private function createDocumentBody(DocumentInterface $document): array
    {
        $body = [];
        
        $document->getFields()->map(function (FieldInterface $field) use (&$body) {
            $body[$field->getName()] = $field->getValue();
        });
        
        return $body;
    }
    
    private function getClient(): Client
    {
        if (null === $this->client) {
            $this->client = new Client($this->options['app_id'], $this->options['app_key']);
        }
        
        return $this->client;
    }
    
    private function processResults(array $results): array
    {
        $identifiers = [];
        
        foreach ($results['hits'] as $hit) {
            $identifiers[] = $hit['objectID'];
        }
        
        return $identifiers;
    }
}

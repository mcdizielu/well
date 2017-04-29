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

namespace WellCommerce\Component\Search\Adapter\ElasticSearch;

use Doctrine\Common\Collections\Collection;
use Elasticsearch\Client;
use Elasticsearch\ClientBuilder;
use WellCommerce\Component\Search\Adapter\AdapterInterface;
use WellCommerce\Component\Search\Adapter\QueryBuilderInterface;
use WellCommerce\Component\Search\Adapter\SearchAdapterConfiguratorInterface;
use WellCommerce\Component\Search\Model\DocumentInterface;
use WellCommerce\Component\Search\Model\FieldInterface;
use WellCommerce\Component\Search\Request\SearchRequestInterface;

/**
 * Class ElasticSearchAdapter
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class ElasticSearchAdapter implements AdapterInterface
{
    /**
     * @var SearchAdapterConfiguratorInterface
     */
    private $configurator;
    
    /**
     * @var Client
     */
    private $client;
    
    public function __construct(SearchAdapterConfiguratorInterface $configurator)
    {
        $this->configurator = $configurator;
    }
    
    public function search(SearchRequestInterface $request): array
    {
        $params = [
            'index' => $this->getIndexName($request->getLocale()),
            'type'  => $request->getType()->getName(),
            "size"  => $this->getOption('maxResults'),
            'body'  => $this->createQueryBuilder($request)->getQuery(),
        ];
        
        $results = $this->getClient()->search($params);
        
        return $this->processResults($results);
    }
    
    public function addDocument(DocumentInterface $document)
    {
        $params = [
            'index' => $this->getIndexName($document->getLocale()),
            'type'  => $document->getType()->getName(),
            'id'    => $document->getIdentifier(),
            'body'  => $this->createDocumentBody($document),
        ];
        
        $this->getClient()->index($params);
    }
    
    public function addDocuments(Collection $documents, string $locale, string $type)
    {
        $documents->map(function (DocumentInterface $document) {
            $this->addDocument($document);
        });
    }
    
    public function removeDocument(DocumentInterface $document)
    {
        $params = [
            'index' => $this->getIndexName($document->getLocale()),
            'type'  => $document->getType()->getName(),
            'id'    => $document->getIdentifier(),
        ];
        
        $this->getClient()->delete($params);
    }
    
    public function updateDocument(DocumentInterface $document)
    {
        $params = [
            'index' => $this->getIndexName($document->getLocale()),
            'type'  => $document->getType()->getName(),
            'id'    => $document->getIdentifier(),
            'body'  => $this->createDocumentBody($document),
        ];
        
        $this->getClient()->update($params);
    }
    
    public function getIndexName(string $locale): string
    {
        return sprintf('%s%s', $this->getOption('indexPrefix'), $locale);
    }
    
    public function createIndex(string $locale, string $type)
    {
        return $this->getClient()->indices()->create([
            'index' => $this->getIndexName($locale),
            'body'  => [
                'settings' => [
                    'number_of_shards'   => $this->getOption('shards'),
                    'number_of_replicas' => $this->getOption('replicas'),
                ],
            ],
        ]);
    }
    
    public function removeIndex(string $locale, string $type)
    {
        if (false === $this->hasIndex($locale)) {
            return false;
        }
        
        return $this->getClient()->indices()->delete([
            'index' => $this->getIndexName($locale),
        ]);
    }
    
    public function flushIndex(string $locale, string $type)
    {
        if (false === $this->hasIndex($locale)) {
            return $this->createIndex($locale, $type);
        }
        
        return $this->getClient()->indices()->flush([
            'index' => $this->getIndexName($locale),
        ]);
    }
    
    public function getStats(string $locale)
    {
        return $this->getClient()->indices()->stats([
            'index' => $this->getIndexName($locale),
        ]);
    }
    
    private function createQueryBuilder(SearchRequestInterface $request): QueryBuilderInterface
    {
        $queryBuilderClass = $this->getOption('builderClass');

        return new $queryBuilderClass($request, $this->getOption('termMinLength'));
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
            $this->client = ClientBuilder::create()->build();
        }
        
        return $this->client;
    }
    
    private function hasIndex(string $locale): bool
    {
        return $this->getClient()->indices()->exists([
            'index' => $this->getIndexName($locale),
        ]);
    }
    
    private function processResults(array $results): array
    {
        $identifiers = [];
        
        foreach ($results['hits']['hits'] as $hit) {
            $identifiers[] = $hit['_id'];
        }
        
        return $identifiers;
    }
    
    private function getOption(string $name)
    {
        return $this->configurator->getSearchAdapterOptions()[$name];
    }
}

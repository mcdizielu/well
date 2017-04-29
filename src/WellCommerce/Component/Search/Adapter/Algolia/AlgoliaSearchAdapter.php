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
use Doctrine\Common\Collections\Collection;
use WellCommerce\Component\Search\Adapter\AdapterInterface;
use WellCommerce\Component\Search\Adapter\QueryBuilderInterface;
use WellCommerce\Component\Search\Adapter\SearchAdapterConfiguratorInterface;
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
        $indexName = $this->getIndexName($request->getLocale(), $request->getType()->getName());
        $index     = $this->getClient()->initIndex($indexName);
        $body      = $this->createQueryBuilder($request)->getQuery();
        $results   = $index->search($request->getPhrase(), [
            'attributesToRetrieve' => $body,
            'hitsPerPage'          => $this->getOption('maxResults'),
        ]);
        
        return $this->processResults($results);
    }
    
    public function addDocument(DocumentInterface $document)
    {
        $indexName = $this->getIndexName($document->getLocale(), $document->getType()->getName());
        $index     = $this->getClient()->initIndex($indexName);
        
        $index->addObject($this->createDocumentBody($document), $document->getIdentifier());
    }
    
    public function addDocuments(Collection $documents, string $locale, string $type)
    {
        $request = [];
        
        $documents->map(function (DocumentInterface $document) use (&$request) {
            $body             = $this->createDocumentBody($document);
            $body['objectID'] = $document->getIdentifier();
            $request[]        = $body;
        });
        
        $indexName = $this->getIndexName($locale, $type);
        $index     = $this->getClient()->initIndex($indexName);
        
        if (!empty($request)) {
            $index->addObjects($request);
        }
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
        return sprintf('%s%s_%s', $this->getOption('indexPrefix'), $locale, $type);
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
            $this->client = new Client($this->getOption('appId'), $this->getOption('apiKey'));
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
    
    private function getOption(string $name)
    {
        return $this->configurator->getSearchAdapterOptions()[$name];
    }
}

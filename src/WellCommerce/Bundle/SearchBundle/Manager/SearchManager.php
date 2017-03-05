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

namespace WellCommerce\Bundle\SearchBundle\Manager;

use Doctrine\Common\Collections\Collection;
use WellCommerce\Component\Search\Adapter\AdapterInterface;
use WellCommerce\Component\Search\Exception\TypeNotFoundException;
use WellCommerce\Component\Search\Model\DocumentInterface;
use WellCommerce\Component\Search\Model\TypeInterface;
use WellCommerce\Component\Search\Request\SearchRequestInterface;
use WellCommerce\Component\Search\Storage\SearchResultStorage;

/**
 * Class SearchManager
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class SearchManager implements SearchManagerInterface
{
    /**
     * @var Collection
     */
    private $types;
    
    /**
     * @var SearchResultStorage
     */
    private $storage;
    
    /**
     * @var AdapterInterface
     */
    private $adapter;
    
    /**
     * SearchManager constructor.
     *
     * @param Collection          $types
     * @param SearchResultStorage $storage
     * @param AdapterInterface    $adapter
     */
    public function __construct(Collection $types, SearchResultStorage $storage, AdapterInterface $adapter)
    {
        $this->types   = $types;
        $this->storage = $storage;
        $this->adapter = $adapter;
    }
    
    public function search(SearchRequestInterface $request): array
    {
        $result = $this->adapter->search($request);
        
        $this->storage->setResult($result);
        
        return $result;
    }
    
    public function addDocument(DocumentInterface $document)
    {
        return $this->adapter->addDocument($document);
    }
    
    public function addDocuments(Collection $documents, string $locale, string $type)
    {
        return $this->adapter->addDocuments($documents, $locale, $type);
    }
    
    public function updateDocument(DocumentInterface $document)
    {
        return $this->adapter->addDocument($document);
    }
    
    public function removeDocument(DocumentInterface $document)
    {
        return $this->adapter->addDocument($document);
    }
    
    public function createIndex(string $locale, string $type)
    {
        $this->adapter->createIndex($locale, $type);
    }
    
    public function flushIndex(string $locale, string $type)
    {
        $this->adapter->flushIndex($locale, $type);
    }
    
    public function removeIndex(string $locale, string $type)
    {
        $this->adapter->removeIndex($locale, $type);
    }
    
    public function getType(string $type): TypeInterface
    {
        if (false === $this->types->containsKey($type)) {
            throw new TypeNotFoundException($type, $this->types->getKeys());
        }
        
        return $this->types->get($type);
    }
    
    public function getTypes(): Collection
    {
        return $this->types;
    }
}

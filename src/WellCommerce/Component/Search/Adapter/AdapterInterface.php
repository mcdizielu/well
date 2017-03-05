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

namespace WellCommerce\Component\Search\Adapter;

use Doctrine\Common\Collections\Collection;
use WellCommerce\Component\Search\Model\DocumentInterface;
use WellCommerce\Component\Search\Request\SearchRequestInterface;

/**
 * Interface AdapterInterface
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
interface AdapterInterface
{
    public function search(SearchRequestInterface $request): array;
    
    public function createIndex(string $locale, string $type);
    
    public function removeIndex(string $locale, string $type);
    
    public function flushIndex(string $locale, string $type);
    
    public function addDocument(DocumentInterface $document);
    
    public function addDocuments(Collection $documents, string $locale, string $type);
    
    public function updateDocument(DocumentInterface $document);
    
    public function removeDocument(DocumentInterface $document);
}

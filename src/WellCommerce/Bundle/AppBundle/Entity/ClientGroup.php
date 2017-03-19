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
namespace WellCommerce\Bundle\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Knp\DoctrineBehaviors\Model\Blameable\Blameable;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;
use Knp\DoctrineBehaviors\Model\Translatable\Translatable;
use WellCommerce\Bundle\CmsBundle\Entity\Page;
use WellCommerce\Bundle\CoreBundle\Doctrine\Behaviours\Identifiable;
use WellCommerce\Bundle\CoreBundle\Entity\EntityInterface;

/**
 * Class ClientGroup
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ClientGroup implements EntityInterface
{
    use Identifiable;
    use Translatable;
    use Timestampable;
    use Blameable;
    
    protected $discount = 0.00;
    
    /**
     * @var Collection
     */
    protected $clients;
    
    /**
     * @var Collection
     */
    protected $pages;
    
    public function __construct()
    {
        $this->clients = new ArrayCollection();
        $this->pages   = new ArrayCollection();
    }
    
    public function getDiscount(): float
    {
        return $this->discount;
    }
    
    public function setDiscount(float $discount)
    {
        $this->discount = $discount;
    }
    
    public function setClients(Collection $clients)
    {
        $this->clients = $clients;
    }
    
    public function getClients(): Collection
    {
        return $this->clients;
    }
    
    public function addClient(Client $client)
    {
        $this->clients->add($client);
    }
    
    public function getPages(): Collection
    {
        return $this->pages;
    }
    
    public function setPages(Collection $pages)
    {
        $this->pages = $pages;
    }
    
    public function addPage(Page $page)
    {
        $this->pages->add($page);
    }
    
    public function translate($locale = null, $fallbackToDefault = true): ClientGroupTranslation
    {
        return $this->doTranslate($locale, $fallbackToDefault);
    }
}


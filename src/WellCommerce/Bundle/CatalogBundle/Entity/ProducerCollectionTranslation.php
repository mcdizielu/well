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

namespace WellCommerce\Bundle\CatalogBundle\Entity;

use Knp\DoctrineBehaviors\Model\Translatable\Translation;
use WellCommerce\Bundle\AppBundle\Entity\Meta;
use WellCommerce\Bundle\CatalogBundle\Entity\ProducerCollectionRoute;
use WellCommerce\Bundle\CoreBundle\Entity\LocaleAwareInterface;
use WellCommerce\Bundle\CoreBundle\Entity\RoutableSubjectInterface;
use WellCommerce\Bundle\CoreBundle\Entity\RoutableTrait;
use WellCommerce\Bundle\CoreBundle\Entity\Route;

/**
 * Class ProducerCollectionTranslation
 *
 * @author Rafał Martonik <rafal@wellcommerce.org>
 */
class ProducerCollectionTranslation implements RoutableSubjectInterface, LocaleAwareInterface
{
    use Translation;
    use RoutableTrait;
    
    protected $name        = '';
    protected $description = '';
    protected $meta;
    
    public function __construct()
    {
        $this->meta = new Meta();
    }
    
    public function getName(): string
    {
        return $this->name;
    }
    
    public function setName(string $name)
    {
        $this->name = $name;
    }
    
    public function getDescription(): string
    {
        return $this->description;
    }
    
    public function setDescription(string $description)
    {
        $this->description = $description;
    }
    
    public function getMeta(): Meta
    {
        return $this->meta;
    }
    
    public function setMeta(Meta $meta)
    {
        $this->meta = $meta;
    }
    
    public function getRouteEntity(): Route
    {
        return new ProducerCollectionRoute();
    }
}

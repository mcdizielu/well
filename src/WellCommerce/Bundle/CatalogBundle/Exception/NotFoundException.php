<?php

namespace WellCommerce\Bundle\CatalogBundle\Exception;

/**
 * Class NotFoundException
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class NotFoundException extends \Exception
{
    public static function product(int $id): NotFoundException
    {
        return new self(sprintf('Product with ID "%s" was not found.', $id));
    }
    
    public static function variant(int $id): NotFoundException
    {
        return new self(sprintf('Variant with ID "%s" was not found.', $id));
    }
}

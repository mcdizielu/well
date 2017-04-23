<?php

namespace WellCommerce\Bundle\AppBundle\Exception;

/**
 * Class NotFoundException
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class NotFoundException extends \Exception
{
    public static function currencyRate(string $baseCurrency, string $targetCurrency): NotFoundException
    {
        return new self(sprintf('No exchange rate found for base "%s" and target "%s" currency.', $baseCurrency, $targetCurrency));
    }
    
    public static function currencyRates(string $targetCurrency): NotFoundException
    {
        return new self(sprintf('No exchange rates found for "%s"', $targetCurrency));
    }
    
    public static function layoutBox(int $id): NotFoundException
    {
        return new self(sprintf('LayoutBox with ID "%s" was not found.', $id));
    }
}

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

namespace WellCommerce\Bundle\AppBundle\Helper;

use InvalidArgumentException;
use WellCommerce\Bundle\AppBundle\Entity\CurrencyRate;
use WellCommerce\Bundle\CoreBundle\Helper\Request\RequestHelperInterface;
use WellCommerce\Bundle\CoreBundle\Doctrine\Repository\RepositoryInterface;

/**
 * Class CurrencyHelper
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class CurrencyHelper implements CurrencyHelperInterface
{
    /**
     * @var RequestHelperInterface
     */
    private $requestHelper;
    
    /**
     * @var RepositoryInterface
     */
    private $repository;
    
    /**
     * @var array
     */
    protected $exchangeRates = [];
    
    /**
     * @var null
     */
    private $forcedLocale;
    
    public function __construct(RequestHelperInterface $requestHelper, RepositoryInterface $repository, $forcedLocale = null)
    {
        $this->requestHelper = $requestHelper;
        $this->repository    = $repository;
        $this->forcedLocale  = $forcedLocale;
    }
    
    public function format(float $amount, string $currency = null, string $locale = null): string
    {
        if (null === $currency) {
            $currency = $this->requestHelper->getCurrentCurrency();
        }
        
        $locale    = $this->getLocale($locale);
        $formatter = new \NumberFormatter($locale, \NumberFormatter::CURRENCY);
        if (false === $result = $formatter->formatCurrency($amount, $currency)) {
            $message = sprintf('Cannot format price with amount "%s" and currency "%s" for locale "%s"', $amount, $currency, $locale);
            throw new InvalidArgumentException($message);
        }
        
        return $result;
    }
    
    public function convert(float $amount, string $baseCurrency = null, string $targetCurrency = null, int $quantity = 1): float
    {
        $exchangeRate    = $this->getCurrencyRate($baseCurrency, $targetCurrency);
        $exchangedAmount = round($amount * $exchangeRate, 2);
        
        return round($exchangedAmount * $quantity, 2);
    }
    
    public function getCurrencyRate(string $baseCurrency = null, string $targetCurrency = null): float
    {
        $currentCurrency = $this->requestHelper->getCurrentCurrency();
        $baseCurrency    = (null === $baseCurrency) ? $currentCurrency : $baseCurrency;
        $targetCurrency  = (null === $targetCurrency) ? $currentCurrency : $targetCurrency;
        
        $this->loadExchangeRates($targetCurrency);
        
        if (!isset($this->exchangeRates[$targetCurrency][$baseCurrency])) {
            $message = sprintf('No exchange rate found for "%s" and "%s" currency.', $baseCurrency, $targetCurrency);
            throw new InvalidArgumentException($message);
        }
        
        return $this->exchangeRates[$targetCurrency][$baseCurrency];
    }
    
    public function convertAndFormat(
        float $amount,
        string $baseCurrency = null,
        string $targetCurrency = null,
        int $quantity = 1,
        string $locale = null
    ): string {
        $converted = $this->convert($amount, $baseCurrency, $targetCurrency, $quantity);
        
        return $this->format($converted, $targetCurrency, $locale);
    }
    
    private function loadExchangeRates($targetCurrency)
    {
        if (!isset($this->exchangeRates[$targetCurrency])) {
            $currencyRates = $this->repository->findBy(['currencyTo' => $targetCurrency]);
            if (count($currencyRates) === 0) {
                $message = sprintf('There are no exchange rates for "%s".', $targetCurrency);
                throw new \InvalidArgumentException($message);
            }
            foreach ($currencyRates as $rate) {
                $this->setExchangeRate($rate, $targetCurrency);
            }
        }
    }
    
    private function setExchangeRate(CurrencyRate $rate, string $targetCurrency)
    {
        $this->exchangeRates[$targetCurrency][$rate->getCurrencyFrom()] = $rate->getExchangeRate();
    }
    
    private function getLocale(string $locale = null): string
    {
        if (null === $locale) {
            if (null === $this->forcedLocale) {
                return $this->requestHelper->getCurrentLocale();
            } else {
                return $this->forcedLocale;
            }
        }
        
        return $locale;
    }
}

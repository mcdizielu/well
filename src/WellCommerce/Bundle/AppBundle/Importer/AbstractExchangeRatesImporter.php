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

namespace WellCommerce\Bundle\AppBundle\Importer;

use WellCommerce\Bundle\AppBundle\Entity\CurrencyRate;
use WellCommerce\Bundle\CoreBundle\Helper\Doctrine\DoctrineHelperInterface;
use WellCommerce\Bundle\CoreBundle\Doctrine\Repository\RepositoryInterface;

/**
 * Class AbstractExchangeRatesImporter
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
abstract class AbstractExchangeRatesImporter
{
    /**
     * @var RepositoryInterface
     */
    protected $currencyRepository;
    
    /**
     * @var RepositoryInterface
     */
    protected $currencyRateRepository;
    
    /**
     * @var DoctrineHelperInterface
     */
    protected $helper;
    
    /**
     * @var array
     */
    protected $managedCurrencies = [];
    
    /**
     * AbstractExchangeRatesImporter constructor.
     *
     * @param RepositoryInterface     $currencyRepository
     * @param RepositoryInterface     $currencyRateRepository
     * @param DoctrineHelperInterface $helper
     */
    public function __construct(
        RepositoryInterface $currencyRepository,
        RepositoryInterface $currencyRateRepository,
        DoctrineHelperInterface $helper
    ) {
        $this->currencyRepository     = $currencyRepository;
        $this->currencyRateRepository = $currencyRateRepository;
        $this->helper                 = $helper;
        
        $this->setManagedCurrencies();
    }
    
    /**
     * Adds new rate or updates existing one
     *
     * @param string $currencyFrom
     * @param string $currencyTo
     * @param float  $rate
     */
    protected function addUpdateExchangeRate($currencyFrom, $currencyTo, $rate)
    {
        if (!in_array($currencyTo, $this->managedCurrencies)) {
            return false;
        }
        
        $exchangeRate = $this->currencyRateRepository->findOneBy([
            'currencyFrom' => $currencyFrom,
            'currencyTo'   => $currencyTo,
        ]);
        
        if (null === $exchangeRate) {
            $exchangeRate = new CurrencyRate();
            $exchangeRate->setCurrencyFrom($currencyFrom);
            $exchangeRate->setCurrencyTo($currencyTo);
            $exchangeRate->setExchangeRate($rate);
            $this->helper->getEntityManager()->persist($exchangeRate);
        } else {
            $exchangeRate->setExchangeRate($rate);
        }
        
        return true;
    }
    
    /**
     * Sets all managed currencies
     */
    protected function setManagedCurrencies()
    {
        foreach ($this->getCurrencies() as $currency) {
            $this->managedCurrencies[] = $currency->getCode();
        }
    }
    
    /**
     * Returns all currencies from repository
     *
     * @return \WellCommerce\Bundle\AppBundle\Entity\Currency[]
     */
    protected function getCurrencies()
    {
        return $this->currencyRepository->findAll();
    }
    
    /**
     * Formats the exchange rate
     *
     * @param float $rate
     *
     * @return string
     */
    protected function formatExchangeRate($rate)
    {
        return number_format($rate, 4, '.', '');
    }
}

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
namespace WellCommerce\Bundle\AppBundle\Twig;

use WellCommerce\Bundle\AppBundle\Entity\ClientBillingAddress;
use WellCommerce\Bundle\AppBundle\Entity\ClientContactDetails;
use WellCommerce\Bundle\AppBundle\Entity\ClientShippingAddress;

/**
 * Class ClientAddressFormatterExtension
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ClientAddressFormatterExtension extends \Twig_Extension
{
    const LINES_SEPARATOR = '<br />';
    
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('format_billing_address', [$this, 'formatBillingAddress'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('format_shipping_address', [$this, 'formatShippingAddress'], ['is_safe' => ['html']]),
            new \Twig_SimpleFunction('format_contact_details', [$this, 'formatContactDetails'], ['is_safe' => ['html']]),
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'address_formatter';
    }
    
    /**
     * Formats the billing address
     *
     * @param ClientBillingAddress $address
     * @param string               $lineSeparator
     *
     * @return string
     */
    public function formatBillingAddress(ClientBillingAddress $address, $lineSeparator = self::LINES_SEPARATOR)
    {
        $lines   = [];
        $lines[] = sprintf('%s %s', $address->getFirstName(), $address->getLastName());
        if ('' !== $address->getCompanyName()) {
            $lines[] = $address->getCompanyName();
        }
        $lines[] = $address->getLine1();
        $lines[] = $address->getLine2();
        $lines[] = sprintf('%s, %s %s', $address->getCountry(), $address->getPostalCode(), $address->getCity());
        
        return implode($lineSeparator, $lines);
    }
    
    /**
     * Formats the shipping address
     *
     * @param ClientShippingAddress $address
     * @param string                $lineSeparator
     *
     * @return string
     */
    public function formatShippingAddress(ClientShippingAddress $address, $lineSeparator = self::LINES_SEPARATOR)
    {
        $lines   = [];
        $lines[] = sprintf('%s %s', $address->getFirstName(), $address->getLastName());
        if ('' !== $address->getCompanyName()) {
            $lines[] = $address->getCompanyName();
        }
        $lines[] = $address->getLine1();
        $lines[] = $address->getLine2();
        $lines[] = sprintf('%s, %s %s', $address->getCountry(), $address->getPostalCode(), $address->getCity());
        
        return implode($lineSeparator, $lines);
    }
    
    public function formatContactDetails(ClientContactDetails $details, string $lineSeparator = self::LINES_SEPARATOR): string
    {
        $lines   = [];
        $lines[] = sprintf('%s %s', $details->getFirstName(), $details->getLastName());
        $lines[] = sprintf('%s %s', $details->getPhone(), $details->getSecondaryPhone());
        $lines[] = $details->getEmail();
        
        return implode($lineSeparator, $lines);
    }
}

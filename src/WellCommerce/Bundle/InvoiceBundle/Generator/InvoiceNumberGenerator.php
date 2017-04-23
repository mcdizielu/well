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

namespace WellCommerce\Bundle\InvoiceBundle\Generator;

use Carbon\Carbon;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use WellCommerce\Bundle\CoreBundle\Helper\Doctrine\DoctrineHelperInterface;
use WellCommerce\Bundle\InvoiceBundle\Entity\Invoice;

/**
 * Class OrderNumberGenerator
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class InvoiceNumberGenerator implements InvoiceNumberGeneratorInterface
{
    /**
     * @var DoctrineHelperInterface
     */
    private $helper;
    
    /**
     * @var string
     */
    private $numberFormat;
    
    public function __construct(DoctrineHelperInterface $helper, string $numberFormat)
    {
        $this->helper       = $helper;
        $this->numberFormat = $numberFormat;
    }
    
    public function generate(Invoice $invoice)
    {
        $lastId   = $this->getLastId();
        $language = new ExpressionLanguage();
        $number   = $language->evaluate(
            $this->numberFormat,
            [
                'nextId'      => $lastId + 1,
                'month'       => Carbon::instance($invoice->getDate())->format('m'),
                'year'        => Carbon::instance($invoice->getDate())->format('Y'),
                'day'         => Carbon::instance($invoice->getDate())->format('d'),
                'orderNumber' => $invoice->getOrder()->getNumber(),
            ]
        );
        
        $invoice->setNumber($number);
    }
    
    private function getLastId(): int
    {
        return $this->helper->getEntityManager()->createQueryBuilder()
            ->select('MAX(i.id)')
            ->from(Invoice::class, 'i')
            ->getQuery()
            ->getSingleScalarResult();
    }
}

<?php

/**
 * WellCommerce Open-Source E-Commerce Platform
 *
 * This file is part of the WellCommerce package.
 *
 * (c) Adam Piotrowski <adam@wellcommerce.org>
 *
 * For the full copyright and license information,
 * please view the LICENSE file that was distributed with this source code.
 */

namespace WellCommerce\Bundle\OrderBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use WellCommerce\Bundle\AppBundle\Entity\ClientBillingAddress;
use WellCommerce\Bundle\OrderBundle\Entity\Order;

/**
 * Class CompanyAddressValidator
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class CompanyAddressValidator extends ConstraintValidator
{
    public function validate($entity, Constraint $constraint)
    {
        if (!$entity instanceof Order) {
            throw new \InvalidArgumentException('Expected instance of Order');
        }
        
        if (false === $entity->isIssueInvoice()) {
            return;
        }
        
        if ($this->context instanceof ExecutionContextInterface) {
            $billingAddress = $entity->getBillingAddress();
            if (false === $this->isValidCompanyName($billingAddress)) {
                $this->context->buildViolation('order.company_name_not_valid')->atPath('companyName')->addViolation();
            }
            
            if (false === $this->isValidVatId($billingAddress)) {
                $this->context->buildViolation('order.vatid_not_valid')->atPath('vatId')->addViolation();
            }
        }
    }
    
    private function isValidCompanyName(ClientBillingAddress $address): bool
    {
        return strlen($address->getCompanyName()) > 0;
    }
    
    private function isValidVatId(ClientBillingAddress $address): bool
    {
        return strlen($address->getVatId()) > 0;
    }
}

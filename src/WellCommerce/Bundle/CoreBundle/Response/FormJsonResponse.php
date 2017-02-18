<?php

namespace WellCommerce\Bundle\CoreBundle\Response;

use Symfony\Component\HttpFoundation\JsonResponse;
use WellCommerce\Component\Form\Elements\FormInterface;

/**
 * Class FormJsonResponse
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class FormJsonResponse extends JsonResponse
{
    public function __construct(FormInterface $form, string $redirectTo)
    {
        $data = [
            'valid'      => $form->isValid(),
            'continue'   => $form->isAction('continue'),
            'next'       => $form->isAction('next'),
            'redirectTo' => $redirectTo,
            'error'      => $form->getError(),
        ];
        
        parent::__construct($data);
    }
}

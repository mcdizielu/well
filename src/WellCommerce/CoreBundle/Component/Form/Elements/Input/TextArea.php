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

namespace WellCommerce\CoreBundle\Component\Form\Elements\Input;

use Symfony\Component\OptionsResolver\OptionsResolver;
use WellCommerce\CoreBundle\Component\Form\Elements\ElementInterface;

/**
 * Class TextArea
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class TextArea extends TextField implements ElementInterface
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setRequired([
            'rows',
            'cols',
        ]);

        $resolver->setDefaults([
            'rows' => 20,
            'cols' => 50,
        ]);

        $resolver->setAllowedTypes([
            'rows' => 'int',
            'cols' => 'int',
        ]);
    }
}

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

namespace WellCommerce\Component\DataGrid\Configuration\EventHandler;

use Symfony\Component\OptionsResolver\OptionsResolver;
use WellCommerce\Component\DataGrid\DataGridInterface;

/**
 * Class EditRowEventHandler
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class EditRowEventHandler extends ClickRowEventHandler
{
    public function getFunctionName(): string
    {
        return 'edit_row';
    }
    
    protected function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        
        $resolver->setDefaults([
            'row_action' => DataGridInterface::ACTION_EDIT,
        ]);
    }
}

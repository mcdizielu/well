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
 * Class DeleteGroupEventHandler
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class DeleteGroupEventHandler extends AbstractEventHandler
{
    public function getFunctionName(): string
    {
        return 'delete_group';
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        
        $resolver->setRequired([
            'route',
            'group_action',
        ]);
        
        $resolver->setDefaults([
            'route'        => false,
            'group_action' => DataGridInterface::ACTION_DELETE_GROUP,
        ]);
        
        $resolver->setAllowedTypes('route', ['bool', 'string']);
        $resolver->setAllowedTypes('group_action', ['bool', 'string']);
    }
}

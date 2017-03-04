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

namespace WellCommerce\Bundle\StandardEditionBundle\Composer;

use Composer\Script\Event;
use Sensio\Bundle\DistributionBundle\Composer\ScriptHandler as SensioScriptHandler;

/**
 * Class ScriptHandler
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class ScriptHandler extends SensioScriptHandler
{
    public static function installApplication(Event $event)
    {
        $options    = static::getOptions($event);
        $consoleDir = static::getConsoleDir($event, 'Install WellCommerce');
        
        static::executeCommand($event, $consoleDir, 'wellcommerce:install', $options['process-timeout']);
    }
    
    public static function updateApplication(Event $event)
    {
        $options    = static::getOptions($event);
        $consoleDir = static::getConsoleDir($event, 'Update WellCommerce');
        
        static::executeCommand($event, $consoleDir, 'wellcommerce:doctrine:enhance', $options['process-timeout']);
    }
    
    public static function populateEnvironment(Event $event)
    {
        $secret            = hash('sha1', uniqid(mt_rand(), true));
        $searchIndexPrefix = sprintf('wellcommerce_%s_', hash('sha1', uniqid(mt_rand(), true)));
        
        putenv("SYMFONY_SECRET={$secret}");
        putenv("SEARCH_INDEX_PREFIX={$searchIndexPrefix}");
        
        $io = $event->getIO();
        $io->write('SYMFONY_SECRET=' . getenv('SYMFONY_SECRET'));
        $io->write('SEARCH_INDEX_PREFIX=' . getenv('SEARCH_INDEX_PREFIX'));
    }
}

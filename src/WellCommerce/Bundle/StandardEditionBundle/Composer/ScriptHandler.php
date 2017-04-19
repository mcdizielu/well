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

use Composer\Package\Package;
use Composer\Repository\InstalledRepositoryInterface;
use Composer\Script\Event;
use gossi\codegen\generator\CodeGenerator;
use gossi\codegen\model\PhpTrait;
use Sensio\Bundle\DistributionBundle\Composer\ScriptHandler as SensioScriptHandler;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\PhpExecutableFinder;

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
        
        static::generateEnhancerTraits($event);
        
        static::executeCommand($event, $consoleDir, 'wellcommerce:install', $options['process-timeout']);
    }
    
    public static function updateApplication(Event $event)
    {
        $options    = static::getOptions($event);
        $consoleDir = static::getConsoleDir($event, 'Update WellCommerce');
        
        static::generateEnhancerTraits($event);
        
        static::executeCommand($event, $consoleDir, 'wellcommerce:doctrine:enhance', $options['process-timeout']);
        static::executeCommand($event, $consoleDir, 'wellcommerce:admin:menu-refresh', $options['process-timeout']);
    }
    
    public static function populateEnvironment(Event $event)
    {
        $phpFinder         = new PhpExecutableFinder();
        $secret            = hash('sha1', uniqid(mt_rand(), true));
        $searchIndexPrefix = sprintf('wellcommerce_%s_', hash('sha1', uniqid(mt_rand(), true)));
        $phpExecutablePath = $phpFinder->find(true);
        
        putenv("SYMFONY_SECRET={$secret}");
        putenv("SEARCH_INDEX_PREFIX={$searchIndexPrefix}");
        putenv("PHP_BIN_PATH={$phpExecutablePath}");
        
        $io = $event->getIO();
        $io->write('SYMFONY_SECRET=' . getenv('SYMFONY_SECRET'));
        $io->write('SEARCH_INDEX_PREFIX=' . getenv('SEARCH_INDEX_PREFIX'));
        $io->write('PHP_BIN_PATH=' . getenv('PHP_BIN_PATH'));
    }
    
    protected static function generateEnhancerTraits(Event $event)
    {
        /** @var InstalledRepositoryInterface $installedRepo */
        $installedRepo = $event->getComposer()->getRepositoryManager()->getLocalRepository();
        $io            = $event->getIO();
        $fs            = new Filesystem();
        
        /** @var Package $package */
        foreach ($installedRepo->getCanonicalPackages() as $package) {
            $enhancerTraits = $package->getExtra()['enhancer-traits'] ?? [];
            if (count($enhancerTraits)) {
                foreach ($enhancerTraits as $traitClass => $targetFilename) {
                    if (false === $fs->exists($targetFilename)) {
                        $io->write(sprintf('<info>Generating %s for %s</info>', $traitClass, $package->getName()));
                        $fs->dumpFile($targetFilename, static::generateTrait($traitClass));
                    }
                }
            }
        }
    }
    
    protected static function generateTrait(string $traitClass): string
    {
        $trait = new PhpTrait($traitClass);
        
        $generator = new CodeGenerator([
            'generateDocblock'        => false,
            'generateEmptyDocblock'   => false,
            'generateScalarTypeHints' => true,
            'generateReturnTypeHints' => true,
            'propertySorting'         => true,
        ]);
        
        return '<?php' . str_repeat(PHP_EOL, 2) . $generator->generate($trait);
    }
}

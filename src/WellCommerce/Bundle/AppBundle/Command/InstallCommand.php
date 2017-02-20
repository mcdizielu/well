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

namespace WellCommerce\Bundle\AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use WellCommerce\Bundle\CoreBundle\Console\Action\ClearCacheAction;
use WellCommerce\Bundle\CoreBundle\Console\Action\InstallAssetsAction;
use WellCommerce\Bundle\CoreBundle\Console\Action\InstallDatabaseAction;
use WellCommerce\Bundle\CoreBundle\Console\Action\InstallFixturesAction;
use WellCommerce\Bundle\CoreBundle\Console\Executor\ConsoleActionExecutorInterface;
use WellCommerce\Bundle\SearchBundle\Console\Action\ReindexAction;

/**
 * Class InstallCommand
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class InstallCommand extends Command
{
    /**
     * @var ConsoleActionExecutorInterface
     */
    protected $executor;
    
    /**
     * InstallCommand constructor.
     *
     * @param ConsoleActionExecutorInterface $executor
     */
    public function __construct(ConsoleActionExecutorInterface $executor)
    {
        parent::__construct();
        $this->executor = $executor;
    }
    
    protected function configure()
    {
        $this->setDescription('Installs WellCommerce Application');
        $this->setName('wellcommerce:app:install');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $actions = [
            new ClearCacheAction(),
            new InstallDatabaseAction(),
            new InstallFixturesAction(),
            new ReindexAction(),
            new InstallAssetsAction(),
        ];
        
        $this->executor->execute($actions, $output);
    }
}

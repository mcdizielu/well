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

namespace WellCommerce\Bundle\OrderBundle\Command;

use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use WellCommerce\Bundle\OrderBundle\Entity\Order;

/**
 * Class PurgeCartsCommand
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class PurgeCartsCommand extends ContainerAwareCommand
{
    /**
     * @var EntityManagerInterface
     */
    private $em;
    
    protected function configure()
    {
        $this->setDescription('Purges all non-confirmed orders');
        $this->setName('wellcommerce:order:purge');
    }
    
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->em = $this->getContainer()->get('doctrine.helper')->getEntityManager();
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->write('Purging all non-confirmed orders. Please wait for process to finish.', true);
        
        $this->getOrders()->map(function (Order $order) {
            $this->em->remove($order);
        });
        
        $this->em->flush();
    }
    
    protected function getOrders(): Collection
    {
        $criteria = new Criteria();
        $criteria->where($criteria->expr()->eq('confirmed', false));
        
        return $this->getContainer()->get('order.repository')->getCollection($criteria);
    }
}

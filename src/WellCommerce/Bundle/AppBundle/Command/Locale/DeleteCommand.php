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

namespace WellCommerce\Bundle\AppBundle\Command\Locale;

use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use WellCommerce\Bundle\AppBundle\Entity\Locale;
use WellCommerce\Bundle\CoreBundle\Doctrine\Repository\EntityRepository;
use WellCommerce\Bundle\CoreBundle\Doctrine\Repository\RepositoryInterface;
use WellCommerce\Bundle\CoreBundle\Entity\LocaleAwareInterface;
use WellCommerce\Bundle\CoreBundle\Helper\Doctrine\DoctrineHelperInterface;

/**
 * Class DeleteCommand
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
class DeleteCommand extends ContainerAwareCommand
{
    /**
     * @var RepositoryInterface
     */
    protected $repository;
    
    /**
     * @var DoctrineHelperInterface
     */
    protected $doctrineHelper;
    
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;
    
    protected function configure()
    {
        $this->setDescription('Deletes a locale and related translatable entities');
        $this->setName('locale:delete');
    }
    
    protected function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->repository     = $this->getContainer()->get('locale.repository');
        $this->doctrineHelper = $this->getContainer()->get('doctrine.helper');
        $this->entityManager  = $this->doctrineHelper->getEntityManager();
    }
    
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sourceLocales = $this->getSourceLocales();
        if (1 === count($sourceLocales)) {
            $output->write('<error>Cannot delete any locale. Only one exists.</error>', true);
            
            return;
        }
        
        $helper        = $this->getHelper('question');
        $question      = new ChoiceQuestion('Please select a locale which will be deleted:', $sourceLocales);
        $choosenLocale = $helper->ask($input, $output, $question);
        
        $this->deleteLocaleData($choosenLocale, $output);
        
        $this->entityManager->flush();
        
        $output->writeln(sprintf('<info>Deleted the locale "%s"</info>', $choosenLocale));
    }
    
    protected function deleteLocaleData($localeCode, OutputInterface $output)
    {
        $metadata = $this->doctrineHelper->getAllMetadata();
        $locale   = $this->repository->findOneBy(['code' => $localeCode]);
        if (!$locale instanceof Locale) {
            throw new InvalidArgumentException(sprintf('Wrong locale code "%s" was given', $localeCode));
        }
        
        foreach ($metadata as $classMetadata) {
            $reflectionClass = $classMetadata->getReflectionClass();
            if ($reflectionClass->implementsInterface(LocaleAwareInterface::class)) {
                $repository = $this->entityManager->getRepository($reflectionClass->getName());
                $this->deleteTranslatableEntities($repository, $locale, $output);
            }
        }
        
        $this->entityManager->remove($locale);
    }
    
    protected function deleteTranslatableEntities(EntityRepository $repository, Locale $locale, OutputInterface $output)
    {
        $criteria = new Criteria();
        $criteria->where($criteria->expr()->eq('locale', $locale->getCode()));
        $collection = $repository->matching($criteria);
        
        $collection->map(function (LocaleAwareInterface $entity) {
            $this->entityManager->remove($entity);
        });
        
        $output->write(sprintf(
            'Deleted <info>%s</info> entities <info>%s</info>',
            $collection->count(),
            $repository->getClassName()
        ), true);
    }
    
    protected function getSourceLocales(): array
    {
        $locales    = [];
        $collection = $this->repository->getCollection();
        
        $collection->map(function (Locale $locale) use (&$locales) {
            $locales[$locale->getCode()] = $locale->getCode();
        });
        
        return $locales;
    }
}

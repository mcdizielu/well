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

namespace WellCommerce\Bundle\CoreBundle\DependencyInjection;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use WellCommerce\Bundle\AppBundle\Helper\CurrencyHelperInterface;
use WellCommerce\Bundle\AppBundle\Storage\ShopStorageInterface;
use WellCommerce\Bundle\CoreBundle\Helper\Flash\FlashHelperInterface;
use WellCommerce\Bundle\CoreBundle\Helper\Image\ImageHelperInterface;
use WellCommerce\Bundle\CoreBundle\Helper\Mailer\MailerHelperInterface;
use WellCommerce\Bundle\CoreBundle\Helper\Request\RequestHelperInterface;
use WellCommerce\Bundle\CoreBundle\Helper\Security\SecurityHelperInterface;
use WellCommerce\Bundle\CoreBundle\Helper\Templating\TemplatingHelperInterface;
use WellCommerce\Bundle\CoreBundle\Helper\Translator\TranslatorHelperInterface;
use WellCommerce\Bundle\CoreBundle\Helper\Validator\ValidatorHelperInterface;
use WellCommerce\Bundle\CoreBundle\Helper\Doctrine\DoctrineHelperInterface;
use WellCommerce\Bundle\RoutingBundle\Helper\RouterHelperInterface;
use WellCommerce\Component\Breadcrumb\Provider\BreadcrumbProviderInterface;

/**
 * Class AbstractContainerAware
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
abstract class AbstractContainerAware
{
    /**
     * @var ContainerInterface
     */
    protected $container;
    
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }
    
    protected function has(string $id): bool
    {
        return $this->container->has($id);
    }
    
    protected function get(string $id)
    {
        return $this->container->get($id);
    }
    
    protected function trans(string $id, array $params = [], string $domain = TranslatorHelperInterface::DEFAULT_TRANSLATION_DOMAIN): string
    {
        return $this->getTranslatorHelper()->trans($id, $params, $domain);
    }
    
    protected function getTranslatorHelper(): TranslatorHelperInterface
    {
        return $this->get('translator.helper');
    }
    
    protected function getFlashHelper(): FlashHelperInterface
    {
        return $this->get('flash.helper');
    }
    
    protected function getDoctrineHelper(): DoctrineHelperInterface
    {
        return $this->get('doctrine.helper');
    }
    
    protected function getRequestHelper(): RequestHelperInterface
    {
        return $this->get('request.helper');
    }
    
    protected function getRouterHelper(): RouterHelperInterface
    {
        return $this->get('router.helper');
    }
    
    protected function getImageHelper(): ImageHelperInterface
    {
        return $this->get('image.helper');
    }
    
    protected function getLocales(): array
    {
        return $this->get('locale.repository')->findAll();
    }
    
    protected function getCurrencyHelper(): CurrencyHelperInterface
    {
        return $this->get('currency.helper');
    }
    
    protected function getSecurityHelper(): SecurityHelperInterface
    {
        return $this->get('security.helper');
    }
    
    protected function getMailerHelper(): MailerHelperInterface
    {
        return $this->get('mailer.helper');
    }
    
    protected function getTemplatingHelper(): TemplatingHelperInterface
    {
        return $this->get('templating.helper');
    }
    
    protected function getValidatorHelper(): ValidatorHelperInterface
    {
        return $this->get('validator.helper');
    }
    
    protected function getEntityManager(): EntityManagerInterface
    {
        return $this->getDoctrineHelper()->getEntityManager();
    }
    
    protected function getEventDispatcher(): EventDispatcherInterface
    {
        return $this->get('event_dispatcher');
    }
    
    protected function getShopStorage(): ShopStorageInterface
    {
        return $this->get('shop.storage');
    }
    
    protected function getBreadcrumbProvider(): BreadcrumbProviderInterface
    {
        return $this->get('breadcrumb.provider');
    }
}

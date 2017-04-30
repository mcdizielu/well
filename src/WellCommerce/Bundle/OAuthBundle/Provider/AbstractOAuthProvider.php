<?php

namespace WellCommerce\Bundle\OAuthBundle\Provider;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use WellCommerce\Bundle\AppBundle\Manager\ClientManager;
use WellCommerce\Bundle\CoreBundle\DependencyInjection\AbstractContainerAware;

/**
 * Class AbstractOAuthProvider
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
abstract class AbstractOAuthProvider extends AbstractContainerAware implements OAuthProviderInterface
{
    /**
     * @var array
     */
    protected $options = [];

    public function __construct(array $options = [])
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $this->options = $resolver->resolve($options);
    }

    public function getCredentials(Request $request)
    {
        if ($request->get('_route') !== $this->options['redirect_route']) {
            return null;
        }

        if ($code = $request->query->get('code')) {
            return $code;
        }

        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return $this->getRouterHelper()->redirectTo($this->options['failure_route']);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return $this->getRouterHelper()->redirectTo($this->options['success_route']);
    }

    protected function getRedirectUrl(): string
    {
        return $this->getRouterHelper()->generateUrl($this->options['redirect_route']);
    }

    protected function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired([
            'success_route',
            'failure_route',
            'redirect_route',
        ]);

        $resolver->setDefaults([
            'success_route'  => 'front.client_settings.index',
            'failure_route'  => 'front.client.login',
            'redirect_route' => 'front.oauth.check',
        ]);

        $resolver->setAllowedTypes('success_route', 'string');
        $resolver->setAllowedTypes('failure_route', 'string');
        $resolver->setAllowedTypes('redirect_route', 'string');
    }

    protected function getClientManager(): ClientManager
    {
        return $this->get('client.manager');
    }
}

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

namespace WellCommerce\Bundle\OAuthBundle\Security;

use League\OAuth2\Client\Provider\Facebook;
use League\OAuth2\Client\Provider\FacebookUser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use WellCommerce\Bundle\OAuthBundle\Manager\FacebookClientManager;
use WellCommerce\Bundle\RoutingBundle\Helper\RouterHelperInterface;

/**
 * Class FacebookAuthenticator
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class FacebookAuthenticator extends AbstractGuardAuthenticator
{
    /**
     * @var FacebookClientManager
     */
    private $facebookClientManager;
    
    /**
     * @var RouterHelperInterface
     */
    private $routerHelper;
    
    /**
     * @var Facebook
     */
    private $facebookProvider;
    
    /**
     * @var array
     */
    private $options;
    
    public function __construct(FacebookClientManager $facebookClientManager, RouterHelperInterface $routerHelper, array $options)
    {
        $this->facebookClientManager = $facebookClientManager;
        $this->routerHelper          = $routerHelper;
        $resolver                    = new OptionsResolver();
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
    }
    
    public function getUser($authorizationCode, UserProviderInterface $userProvider)
    {
        $accessToken = $this->getProvider()->getAccessToken(
            'authorization_code',
            ['code' => $authorizationCode]
        );
        
        /** @var FacebookUser $userDetails */
        $userDetails = $this->getProvider()->getResourceOwner($accessToken);
        
        return $this->facebookClientManager->getClient($userDetails);
    }
    
    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }
    
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);
        
        return $this->routerHelper->redirectTo('front.client.login');
    }
    
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return $this->routerHelper->redirectTo('front.client_order.index');
    }
    
    public function supportsRememberMe()
    {
        return true;
    }
    
    public function start(Request $request, AuthenticationException $authException = null)
    {
        $authUrl = $this->getProvider()->getAuthorizationUrl([
            'scopes' => $this->options['scopes'],
        ]);
        
        return $this->routerHelper->redirectToUrl($authUrl);
    }
    
    public function getProvider()
    {
        if (null === $this->facebookProvider) {
            $this->facebookProvider = new Facebook([
                'clientId'        => $this->options['app_id'],
                'clientSecret'    => $this->options['app_secret'],
                'redirectUri'     => $this->options['redirect_uri'],
                'graphApiVersion' => $this->options['graph_version'],
            ]);
        }
        
        return $this->facebookProvider;
    }
    
    public function createAuthenticatedToken(UserInterface $user, $providerKey)
    {
        return new UsernamePasswordToken(
            $user,
            null,
            $providerKey,
            $user->getRoles()
        );
    }
    
    private function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired([
            'app_id',
            'app_secret',
            'page_token',
            'scopes',
            'graph_version',
            'redirect_route',
            'redirect_uri',
        ]);
        
        $resolver->setDefault('graph_version', 'v2.8');
        $resolver->setDefault('scopes', ['email']);
        $resolver->setDefault('redirect_route', 'oauth.facebook.check');
        $resolver->setDefault('redirect_uri', function (Options $options) {
            return $this->routerHelper->generateUrl($options['redirect_route']);
        });
        
        $resolver->setAllowedTypes('app_id', ['string', 'int', 'null']);
        $resolver->setAllowedTypes('app_secret', ['string', 'null']);
        $resolver->setAllowedTypes('page_token', ['string', 'null']);
        $resolver->setAllowedTypes('scopes', 'array');
        $resolver->setAllowedTypes('graph_version', 'string');
        $resolver->setAllowedTypes('redirect_route', 'string');
        $resolver->setAllowedTypes('redirect_uri', 'string');
    }
}

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

use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use WellCommerce\Bundle\CoreBundle\Helper\Request\RequestHelperInterface;
use WellCommerce\Bundle\OAuthBundle\Provider\OAuthProviderInterface;

/**
 * Class OAuthAuthenticator
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class OAuthAuthenticator extends AbstractGuardAuthenticator
{
    const PROVIDER_SESSION_KEY = '_oauth_provider';

    /**
     * @var Collection
     */
    private $providers;

    /**
     * @var RequestHelperInterface
     */
    private $requestHelper;

    public function __construct(Collection $providers, RequestHelperInterface $requestHelper)
    {
        $this->providers     = $providers;
        $this->requestHelper = $requestHelper;
    }
    
    public function getCredentials(Request $request)
    {
        if (null === $this->requestHelper->getSessionAttribute(self::PROVIDER_SESSION_KEY)) {
            return null;
        }

        return $this->getProvider()->getCredentials($request);
    }
    
    public function getUser($authorizationCode, UserProviderInterface $userProvider)
    {
        return $this->getProvider()->getUser($authorizationCode, $userProvider);
    }
    
    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }
    
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $provider = $this->getProvider();

        $this->requestHelper->setSessionAttribute(Security::AUTHENTICATION_ERROR, $exception);
        $this->requestHelper->setSessionAttribute(self::PROVIDER_SESSION_KEY, null);

        return $provider->onAuthenticationFailure($request, $exception);
    }
    
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $provider = $this->getProvider();

        $this->requestHelper->setSessionAttribute(self::PROVIDER_SESSION_KEY, null);

        return $provider->onAuthenticationSuccess($request, $token, $providerKey);
    }
    
    public function supportsRememberMe()
    {
        return true;
    }
    
    public function start(Request $request, AuthenticationException $authException = null)
    {
        return $this->getProvider()->start($request, $authException);
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
    
    private function getProvider(): OAuthProviderInterface
    {
        $name = $this->requestHelper->getSessionAttribute(self::PROVIDER_SESSION_KEY);
        
        return $this->providers->get($name);
    }
}

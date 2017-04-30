<?php

namespace WellCommerce\Bundle\OAuthBundle\Provider;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

/**
 * Interface OAuthProviderInterface
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
interface OAuthProviderInterface
{
    public function getCredentials(Request $request);

    public function getUser($authorizationCode, UserProviderInterface $userProvider);

    public function start(Request $request, AuthenticationException $authException = null);

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception);

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey);
}

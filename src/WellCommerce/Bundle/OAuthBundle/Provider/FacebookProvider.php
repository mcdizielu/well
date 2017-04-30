<?php

namespace WellCommerce\Bundle\OAuthBundle\Provider;

use League\OAuth2\Client\Provider\Facebook;
use League\OAuth2\Client\Provider\FacebookUser;
use League\OAuth2\Client\Token\AccessToken;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use WellCommerce\Bundle\AppBundle\Entity\Client;

/**
 * Class FacebookProvider
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class FacebookProvider extends AbstractOAuthProvider
{
    const GRAPH_VERSION = 'v2.8';
    const SCOPES        = ['email'];
    
    /**
     * @var Facebook
     */
    private $facebook;
    
    public function getUser($authorizationCode, UserProviderInterface $userProvider)
    {
        $accessToken = $this->getFacebook()->getAccessToken('authorization_code', ['code' => $authorizationCode]);
        $userDetails = $this->getResourceOwner($accessToken);
        $email       = $userDetails->getEmail();
        
        $client = $this->getClientManager()->getRepository()->findOneBy([
            'clientDetails.username' => $email,
        ]);
        
        if (!$client instanceof Client) {
            $client = $this->autoRegister($userDetails);
        }
        
        return $client;
    }
    
    public function start(Request $request, AuthenticationException $authException = null)
    {
        $authUrl = $this->getFacebook()->getAuthorizationUrl([
            'scopes' => self::SCOPES,
        ]);
        
        return $this->getRouterHelper()->redirectToUrl($authUrl);
    }
    
    private function getResourceOwner(AccessToken $accessToken): FacebookUser
    {
        return $this->getFacebook()->getResourceOwner($accessToken);
    }
    
    private function getFacebook(): Facebook
    {
        if (null === $this->facebook) {
            $this->facebook = new Facebook([
                'clientId'        => $this->getShopStorage()->getCurrentShop()->getFacebookConnectAppId(),
                'clientSecret'    => $this->getShopStorage()->getCurrentShop()->getFacebookConnectAppSecret(),
                'redirectUri'     => $this->getRedirectUrl(),
                'graphApiVersion' => self::GRAPH_VERSION,
            ]);
        }
        
        return $this->facebook;
    }
    
    private function autoRegister(FacebookUser $facebookUser): Client
    {
        $firstName = $facebookUser->getFirstName();
        $lastName  = $facebookUser->getLastName();
        $email     = $facebookUser->getEmail();
        
        /** @var $client Client */
        $client = $this->getClientManager()->initResource();
        $client->getClientDetails()->setUsername($email);
        $client->getClientDetails()->setPassword($this->getSecurityHelper()->generateRandomPassword());
        
        $client->getContactDetails()->setEmail($email);
        $client->getContactDetails()->setFirstName($firstName);
        $client->getContactDetails()->setLastName($lastName);
        
        $this->getClientManager()->createResource($client);
        
        return $client;
    }
}

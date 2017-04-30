<?php

namespace WellCommerce\Bundle\OAuthBundle\Provider;

use League\OAuth2\Client\Provider\Google;
use League\OAuth2\Client\Provider\GoogleUser;
use League\OAuth2\Client\Token\AccessToken;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use WellCommerce\Bundle\AppBundle\Entity\Client;

/**
 * Class GoogleProvider
 *
 * @author  Adam Piotrowski <adam@wellcommerce.org>
 */
final class GoogleProvider extends AbstractOAuthProvider
{
    /**
     * @var Google
     */
    private $google;

    public function getUser($authorizationCode, UserProviderInterface $userProvider)
    {
        $accessToken = $this->getGoogle()->getAccessToken('authorization_code', ['code' => $authorizationCode]);
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
        $authUrl = $this->getGoogle()->getAuthorizationUrl();

        return $this->getRouterHelper()->redirectToUrl($authUrl);
    }

    private function getResourceOwner(AccessToken $accessToken): GoogleUser
    {
        return $this->getGoogle()->getResourceOwner($accessToken);
    }

    private function getGoogle(): Google
    {
        if (null === $this->google) {
            $this->google = new Google([
                'clientId'     => $this->getShopStorage()->getCurrentShop()->getGoogleConnectAppId(),
                'clientSecret' => $this->getShopStorage()->getCurrentShop()->getGoogleConnectAppSecret(),
                'redirectUri'  => $this->getRedirectUrl(),
            ]);
        }

        return $this->google;
    }

    private function autoRegister(GoogleUser $googleUser): Client
    {
        $firstName = $googleUser->getFirstName();
        $lastName  = $googleUser->getLastName();
        $email     = $googleUser->getEmail();

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

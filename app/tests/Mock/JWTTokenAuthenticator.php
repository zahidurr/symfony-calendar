<?php

namespace App\Tests\Mock;

use StirlingBlue\SecurityBundle\Security\JWTTokenAuthenticator as BaseJWTTokenAuthenticator;
use StirlingBlue\SecurityBundle\Security\JWTUser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class JWTTokenAuthenticator extends BaseJWTTokenAuthenticator
{
    use MockTrait;

    public function supports(Request $request): bool
    {
        return $this->mock ? true : parent::supports($request);
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        if ($this->mock) {
            $payload = json_decode($credentials, true);

            return JWTUser::createFromPayload($payload);
        }

        return $this->mock ? true : parent::getUser($credentials, $userProvider);
    }

    public function checkCredentials($credentials, UserInterface $user): bool
    {
        return $this->mock ? true : parent::checkCredentials($credentials, $user);
    }
}

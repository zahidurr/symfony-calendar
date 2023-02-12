<?php

declare(strict_types=1);

namespace App\Tests\Traits;

use StirlingBlue\SecurityBundle\Security\JWTUser;

/**
 * Trait JwtTrait.
 */
trait JwtTrait
{
    public function createMockJwt(string|array|JWTUser $user = []): string
    {
        if (is_string($user)) {
            $fixtureUser = $this->getFixtureValue($user);

            return $fixtureUser instanceof JWTUser ? $this->createMockJwt($fixtureUser) : $user;
        }

        if ($user instanceof JWTUser) {
            return $this->createMockJwt($this->JwtUserToArray($user));
        }

        return $this->createFakeJwtFromArray($user);
    }

    public function createFakeJwtFromArray(array $customFields = []): string
    {
        return json_encode(
            $customFields
            + $this->JwtUserToArray($this->getFixtureValue(self::BASE_USER_ALIAS))
        );
    }

    /**
     * @param $alias
     */
    public function createJwtForFixtureUser(string $alias): string
    {
        return $this->createMockJwt($this->getFixtureValue($alias));
    }

    private function JwtUserToArray(JWTUser $user): array
    {
        return [
            'userId' => $user->getUserId(),
            'username' => $user->getUsername(),
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
            'segment' => $user->getSegment(),
            'partner' => $user->getPartner(),
            'organizationId' => $user->getOrganizationId(),
            'organizationType' => $user->getOrganizationType(),
            'roles' => $user->getRoles(),
            'organizationTenantId' => $user->getOrganizationTenantId(),
            'originalUser' => $user->getOriginalUser(),
            'businessAccount' => $user->getBusinessAccount(),
        ];
    }
}

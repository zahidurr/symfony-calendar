<?php

declare(strict_types=1);

namespace App\Tests\Traits;

use App\Tests\DTO\PreparedObject;
use StirlingBlue\SecurityBundle\Security\JWTUser;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyPath;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Trait RestTestTrait.
 */
trait RestTestTrait
{
    use RestTrait;

    public function getAndAssert(string|PreparedObject $url = '', string|array|JWTUser $user = [], array $query = [], int $expectedCode = Response::HTTP_OK, array $expectedContains = []): ResponseInterface
    {
        $response = $this->get($this->urlFromObject($url), $this->createMockJwt($user), $query);
        $this->assertResponse($expectedCode, $expectedContains);

        return $response;
    }

    public function postAndAssert(string $url = '', string|array|JWTUser $user = [], array $parameters = [], array $query = [], int $expectedCode = Response::HTTP_OK, array $expectedContains = []): ResponseInterface
    {
        $response = $this->post($url, $this->createMockJwt($user), $parameters, $query);
        $this->assertResponse($expectedCode, $expectedContains);

        return $response;
    }

    public function patchAndAssert(string|PreparedObject $object, string|array|JWTUser $user = [], array $parameters = [], array $query = [], int $expectedCode = Response::HTTP_OK, array $expectedContains = []): ResponseInterface
    {
        $response = $this->patch($this->urlFromObject($object), $this->createMockJwt($user), $parameters, $query);
        $this->assertResponse($expectedCode, $expectedContains);

        return $response;
    }

    public function deleteAndAssert(string|PreparedObject $object, string|array|JWTUser $user, int $expectedCode = Response::HTTP_NO_CONTENT, array $expectedContains = []): ResponseInterface
    {
        $response = $this->delete($this->urlFromObject($object), $this->createMockJwt($user));
        $this->assertResponse($expectedCode, $expectedContains);

        return $response;
    }

    public function assertResponse(int $code, array $contains): void
    {
        self::assertResponseStatusCodeSame($code);
        foreach ($contains as $expectedContain) {
            self::assertJsonContains($expectedContain);
        }
    }

    public function urlFromObject(string|PreparedObject $object): string
    {
        if ($object instanceof PreparedObject) {
            $object = (string) $this->getObject($object->class, $object->criteria)->getId();
        }

        return '' === $object ? $object : '/'.$object;
    }

    public static function assertResponseHasValue(ResponseInterface $response, string $path, bool $has = true): void
    {
        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        $responseAsArray = $response->toArray(false);

        $propertyPath = new PropertyPath($path);
        if ($has) {
            self::assertArrayHasKey(
                $propertyPath->getElement($propertyPath->getLength() - 1),
                null === $propertyPath->getParent()
                    ? $responseAsArray
                    : $propertyAccessor->getValue($responseAsArray, $propertyPath->getParent()),
                sprintf('Failed asserting that the Response has %s value', $path)
            );
        } else {
            self::assertArrayNotHasKey(
                $propertyPath->getElement($propertyPath->getLength() - 1),
                null === $propertyPath->getParent()
                    ? $responseAsArray
                    : $propertyAccessor->getValue($responseAsArray, $propertyPath->getParent()),
                sprintf('Failed asserting that the Response has no %s value', $path)
            );
        }
    }
}

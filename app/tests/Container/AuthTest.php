<?php

declare(strict_types=1);

namespace App\Tests\Container;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Kernel;
use StirlingBlue\SecurityBundle\Security\JWTTokenAuthenticator;

/**
 * Class AuthTest.
 */
class AuthTest extends ApiTestCase
{
    public function testAuth(): void
    {
        static::createClient();
        self::assertInstanceOf(Kernel::class, self::$kernel);
        self::assertEquals('test', self::$kernel->getEnvironment());
        self::assertTrue(self::$container->has(JWTTokenAuthenticator::class));
        self::assertInstanceOf(\App\Tests\Mock\JWTTokenAuthenticator::class, self::$container->get(JWTTokenAuthenticator::class));
    }
}

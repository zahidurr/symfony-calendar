<?php

declare(strict_types=1);

namespace App\Tests\Api;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;
use App\Tests\Traits\FixturesTrait;
use App\Tests\Traits\JwtTrait;
use App\Tests\Traits\OrmTrait;
use App\Tests\Traits\RestTestTrait;
use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use ReflectionClass;
use StirlingBlue\SecurityBundle\Security\JWTTokenAuthenticator;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Class AbstractApiTest.
 */
abstract class AbstractApiTest extends ApiTestCase
{
    use RefreshDatabaseTrait;
    use FixturesTrait;
    use OrmTrait;
    use JwtTrait;
    use RestTestTrait;

    public const FIXTURES_PATH = 'fixtures';
    public const BASE_USER_ALIAS = 'dummy_customer';
    public const BASE_URL = '';

    protected ?Client $client;

    /** @noinspection MethodVisibilityInspection */
    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();
        $this->client->disableReboot();

        $this->callTraitHookMethod('setup');
    }

    /** @noinspection MethodVisibilityInspection */
    protected function tearDown(): void
    {
        $this->callTraitHookMethod('tearDown');

        parent::tearDown();
    }

    private function callTraitHookMethod(string $hook): void
    {
        $rc = new ReflectionClass(self::class);
        foreach ($rc->getTraitNames() as $trait) {
            $traitStruct = explode('\\', $trait);
            $trait = end($traitStruct);
            $method = "{$hook}{$trait}";
            if (method_exists($this, $method)) {
                $this->$method();
            }
        }
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function request(string $method, string $url = '', array $options = [], bool $mockAuth = true): ResponseInterface
    {
        if ($mockAuth) {
            /** @var \App\Tests\Mock\JWTTokenAuthenticator $tokenAuthenticator */
            $tokenAuthenticator = self::$container->get(JWTTokenAuthenticator::class);
            $tokenAuthenticator->doMock();
        }
        $response = $this->client->request($method, $url, $options);
        if ($mockAuth) {
            $tokenAuthenticator->doMock(false);
        }

        return $response;
    }
}

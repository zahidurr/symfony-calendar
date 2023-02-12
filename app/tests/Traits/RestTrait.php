<?php

declare(strict_types=1);

namespace App\Tests\Traits;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Trait RestTrait.
 */
trait RestTrait
{
    public function get(string $url = '', string $jwt = null, array $query = [], bool $mockAuth = true): ResponseInterface
    {
        $options = [];
        if (null !== $jwt) {
            $options['auth_bearer'] = $jwt;
        }
        $url .= '?'.http_build_query($query);

        return self::request(Request::METHOD_GET, $this::BASE_URL.$url, $options, $mockAuth);
    }

    public function post(string $url = '', string $jwt = null, array $parameters = [], array $query = [], bool $mockAuth = true): ResponseInterface
    {
        $options = [];
        if (null !== $jwt) {
            $options['auth_bearer'] = $jwt;
        }
        if (count($parameters)) {
            $options['json'] = $parameters;
        }
        $url .= http_build_query($query);

        return self::request(Request::METHOD_POST, $this::BASE_URL.$url, $options, $mockAuth);
    }

    public function patch(string $url = '', string $jwt = null, array $parameters = [], array $query = [], bool $mockAuth = true): ResponseInterface
    {
        $options = [
            'headers' => ['Content-Type' => 'application/merge-patch+json'],
        ];
        if (null !== $jwt) {
            $options['auth_bearer'] = $jwt;
        }
        if (count($parameters)) {
            $options['json'] = $parameters;
        }
        $url .= http_build_query($query);

        return self::request(Request::METHOD_PATCH, $this::BASE_URL.$url, $options, $mockAuth);
    }

    public function delete(string $url = '', string $jwt = null, array $query = [], bool $mockAuth = true): ResponseInterface
    {
        $options = [];
        if (null !== $jwt) {
            $options['auth_bearer'] = $jwt;
        }
        $url .= '?'.http_build_query($query);

        return self::request(Request::METHOD_DELETE, $this::BASE_URL.$url, $options, $mockAuth);
    }
}

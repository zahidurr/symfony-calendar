<?php

namespace App\Tests\Api;

use App\Entity\PaymentProvider;
use App\Tests\DTO\PreparedObject;
use Ramsey\Uuid\Uuid;
use StirlingBlue\SecurityBundle\Security\JWTUser;
use Symfony\Component\HttpFoundation\Response;

class PaymentProviderTest extends AbstractApiTest
{
    public const BASE_URL = '/api-checkout/payment-providers';

    /**
     * @param ?string $jwt
     * @dataProvider listWithAuthDataProvider
     */
    public function testListWithAuth(?string $jwt, int $expectedCode): void
    {
        $this->get('', $jwt, [], false);
        self::assertResponseStatusCodeSame($expectedCode);
    }

    public function listWithAuthDataProvider(): array
    {
        return [
            'No Jwt' => [
                'jwt' => null,
                'expectedCode' => Response::HTTP_FORBIDDEN,
            ],
            'expired jwt @see \StirlingBlue\SecurityBundle\Tests\Security\JWTTokenAuthenticatorTest::testGetUser' => [
                'jwt' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE2MjA4MDM2NTUsImV4cCI6MTYyMDgxMDg1NSwicm9sZXMiOnsiNTlmMTMyZGYtYWRmNS00OTk4LWJmZjItNmQwMzBiMWNlN2EzIjoid2l6YXJkXC9yZWFkIiwiODJlYTdjMzAtZDNmYy00ZjAwLWFlMDAtZDU5ZmVhYzAzOTM3Ijoid2l6YXJkXC93cml0ZSIsImVjN2M0ZDhiLWU3NzQtNGMyMi04YTQ1LTc5YTJiOWNlOTllMCI6Im15LW9yZ2FuaXphdGlvblwvcmVhZCIsImQyNGMyYjQ4LTJmMGUtNDRmYi05OTI1LWRkZWY4ZDcwNDJiMyI6Im15LWFncmVlbWVudHNcL3JlYWQiLCJmYzZjMzM3Yy05YWRlLTQyYmEtODUwNS1hNDRlZTI4ZGNkZTEiOiJteS1hZ3JlZW1lbnRzXC93cml0ZSIsIjBhYjNmOGJhLWJlYjAtNDVkOC04ZjMwLTM1NTI5Y2UyOTYyNyI6InVzZXItbWFuYWdlbWVudFwvcmVhZCIsIjdmYjAwNWUyLTViYzgtNDUwZi1hNDQ2LTJkNjAzN2Y2NjQ1ZSI6InVzZXItbWFuYWdlbWVudFwvd3JpdGUiLCI5ZjBkOWJkMS1lMzhhLTQxYzktYjZjYi04M2NkNjQ1NmZkMzkiOiJteS1vcmdhbml6YXRpb25cL3dyaXRlIiwiNzA2ZDk3OWUtZmQ1Zi00OTA3LWI0MGYtM2E2MjllYjdjOTc0IjoiY3JtXC9yZWFkIiwiNzAzN2EzNmMtMDQ3MS00NzkzLTgyNjktNzc0ODY4YzAyYTdhIjoiY3JtXC93cml0ZSJ9LCJ1c2VybmFtZSI6InRsZXhAcmF3ci5leGFtcGxlIiwidXNlcklkIjoiNWM2YWMxMmItM2I3My00ZGY4LTg5N2YtOTM1Y2I4YmI4M2YwIiwiZmlyc3ROYW1lIjoiVHlyYW5ub3NhdXJ1cyIsImxhc3ROYW1lIjoiTGV4Iiwib3JnYW5pemF0aW9uSWQiOiJkYzIzNGNlOC1hY2ZkLTQwNGMtODQzNy0zNGZiMjZiNmE0YTAiLCJvcmdhbml6YXRpb25UeXBlIjoibGF3ZmlybSIsIm9yZ2FuaXphdGlvblRlbmFudElkIjoiODFjZjY0YWUtNThhMS00OGY3LTkyMWQtNTJiNTkxY2MxNTZiIiwib3JnYW5pemF0aW9uVGVuYW50T3JnYW5pemF0aW9uSWQiOiIyNWU3MGVjNi04MjEyLTQyNmEtOGJmMy1lOGU5ZDlhMGQ2MzMifQ.J88RdG1fjkIluadse_53jqdZaJ_3ppfcbN8aVKt-WWM7md83ZpT6TNI8Y0lQOnT5Yc6dIN8pTRkTAPV0m05RdPs0qjjQQHX4V2638Q3qbxv2UiTu9m3raIjcml2EKGVHIsDb-QFGbDTubb7HhRV8GrkWhRi_vfbmQgvVDMK7V2Z0feujKUceplP924MJKt69imoL5lz3HzGWnoG-S9JW7KQccbuggtdtCTrKgjZdE_zZAVS1ULHwswA4FceMUn9LFC8DiGZ5yKO2B5SQhzqpkSjHdw41FNa0hFsCYUqGY2kx0lgrh63LkmvjgCFiOsy3TWk872u26cCBMf8Ul_RfWcMq1vGP6tD7TB3qnuepjPW6e6QTkE515lPHw7P0_EKNt368eC6mcbQuyo7P_bV1Q6hC2RDk8Br_cp04Qw7kpw0G8czXUk6zWLSl6RL8kfIwkLShzZR7bGrEy86FZEQxOeBD3ox_oMNVpT4NQ8-2lUE_tZZScb2oVh--eK9ZHElPypGDGIpdWRQ8MPnqPH7ON89vE9OSm-yzDTrSK5C1wl8nBnhwl6jbGgrHz7-24GW7SGlbaOrO9nXwlPvA-7McCUow4QTlLkV2GBCIyJXF7jEpZgO1ZRB7PUm7K6zptAGTXQd4FazoRuMRxxxGfDkWEXhEwtUiNZW1Q8iicSK_3QM',
                'expectedCode' => Response::HTTP_FORBIDDEN,
            ],
        ];
    }

    /**
     * @dataProvider listDataProvider
     */
    public function testList(string|array|JWTUser $user, int $expectedCode, array $expectedContains, array $query = []): void
    {
        $this->getAndAssert('', $user, $query, $expectedCode, $expectedContains);
    }

    public function listDataProvider(): array
    {
        return [
            'Ok' => [
                'user' => 'other_customer',
                'expectedCode' => Response::HTTP_OK,
                'expectedContains' => [['hydra:totalItems' => 2]],
            ],
        ];
    }

    /**
     * @dataProvider fetchDataProvider
     * @noinspection MissingReturnTypeInspection
     */
    public function testFetch(
        string|PreparedObject $paymentProvider,
        JWTUser|array|string $user,
        int $expectedCode,
        array $expectedContains,
        array $expectedKeys
    ) {
        $response = $this->getAndAssert($paymentProvider, $user, [], $expectedCode, $expectedContains);
        foreach ($expectedKeys as $key => $value) {
            self::assertResponseHasValue($response, $key, $value);
        }
    }

    public function fetchDataProvider(): array
    {
        $baseFieldList = [
            '[@context]' => true,
            '[@id]' => true,
            '[@type]' => true,
            '[id]' => true,
            '[name]' => true,
            '[createdBy]' => true,
            '[paymentMethodTypes]' => true,
        ];
        $baseExpectedValues = [
            ['@context' => '/api-checkout/contexts/PaymentProvider'],
            ['@type' => 'PaymentProvider'],
        ];

        return [
            'random paymentProvider uuid' => [
                'paymentProvider' => Uuid::uuid4()->toString(),
                'user' => 'customer',
                'expectedCode' => Response::HTTP_NOT_FOUND,
                'expectedContains' => [],
                'expectedKeys' => [
                    '[@context]' => true,
                    '[@type]' => true,
                    '[hydra:title]' => true,
                    '[hydra:description]' => true,
                    '[trace]' => true,
                ],
            ],
            'existed another-test-payment-provider' => [
                'paymentProvider' => new PreparedObject(PaymentProvider::class, ['name' => 'another-test-payment-provider']),
                'user' => 'customer',
                'expectedCode' => Response::HTTP_OK,
                'expectedContains' => array_merge(
                    [
                        ['paymentMethodTypes' => []],
                    ],
                    $baseExpectedValues
                ),
                'expectedKeys' => array_merge(
                    [
                        '[paymentMethodTypes][0]' => false,
                    ],
                    $baseFieldList
                ),
            ],
            'existed test-payment-provider' => [
        'paymentProvider' => new PreparedObject(PaymentProvider::class, ['name' => 'test-payment-provider']),
        'user' => 'customer',
        'expectedCode' => Response::HTTP_OK,
        'expectedContains' => [],
        'expectedKeys' => array_merge(
            [
                '[paymentMethodTypes][0]' => true,
                '[paymentMethodTypes][0][@type]' => true,
                '[paymentMethodTypes][0][@id]' => true,
                '[paymentMethodTypes][0][id]' => true,
                '[paymentMethodTypes][0][name]' => true,
                '[paymentMethodTypes][0][tenantId]' => true,
                '[paymentMethodTypes][0][paymentConfig]' => true,
                '[paymentMethodTypes][0][isActive]' => true,
                '[paymentMethodTypes][0][createdBy]' => true,
                '[paymentMethodTypes][1]' => true,
                '[paymentMethodTypes][1][@type]' => true,
                '[paymentMethodTypes][1][@id]' => true,
                '[paymentMethodTypes][1][id]' => true,
                '[paymentMethodTypes][1][name]' => true,
                '[paymentMethodTypes][1][tenantId]' => true,
                '[paymentMethodTypes][1][paymentConfig]' => true,
                '[paymentMethodTypes][1][isActive]' => true,
                '[paymentMethodTypes][1][createdBy]' => true,
                '[paymentMethodTypes][2]' => false,
            ],
            $baseFieldList),
    ],
        ];
    }

    /**
     * @dataProvider activeDataProvider
     */
    public function testActive(JWTUser|array|string $user, int $expectedCode, array $expectedContains, array $query = [], array $expectedKeys = []): void
    {
        $response = $this->getAndAssert('active', $user, $query, $expectedCode, $expectedContains);
        foreach ($expectedKeys as $key => $value) {
            self::assertResponseHasValue($response, $key, $value);
        }
    }

    public function activeDataProvider(): array
    {
        return [
            'Ok' => [
                'user' => 'other_customer',
                'expectedCode' => Response::HTTP_OK,
                'expectedContains' => [],
                'query' => [],
                'expectedKeys' => [
                    '[@context]' => false,
                    '[test-payment-provider]' => true,
                    '[another-test-payment-provider]' => true,
                    //@todo: this part works incorrect
                    '[test-payment-provider][test-payment-method-type-1]' => true,
                    '[another-test-payment-provider][test-payment-method-type-1]' => true,
                ],
            ],
        ];
    }
}

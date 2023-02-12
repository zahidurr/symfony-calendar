<?php

namespace App\Tests\Api;

use StirlingBlue\SecurityBundle\Security\JWTUser;
use Symfony\Component\HttpFoundation\Response;

class NfeWebhookTest extends AbstractApiTest
{
    public const BASE_URL = '/api-checkout/nfe-invoices/webhook';

    /**
     * @param $user
     * @param $parameters
     * @param $query
     * @param $expectedCode
     * @param $expectedContains
     * @dataProvider webhookDataProvider
     */
    public function testWebhook(
        string|array|JWTUser $user = [],
        array $parameters = [],
        array $query = [],
        int $expectedCode = Response::HTTP_OK,
        array $expectedContains = []
    ): void {
        $this->postAndAssert('', $user, $parameters, $query, $expectedCode, $expectedContains);
    }

    public function webhookDataProvider()
    {
        return [
            'bad invoice Id' => [
                'user' => '',
                'parameters' => [
                    'id' => '61b8b00c003cad45c4b28e93',
                    'environment' => 'Development',
                    'flowStatus' => 'Issued',
                    'provider' => [
                        'tradeName' => 'ODILON COMERCIO E SERVICO',
                        'taxRegime' => 0,
                        'specialTaxRegime' => 'Nenhum',
                        'legalNature' => 'Empresario',
                        'issRate' => 0,
                        'parentId' => '60ba1bdf200cda86188095a3',
                        'id' => '60ba1ca45daa5e0fb00de46e',
                        'name' => 'ODILON BICUDO DA ROCHA',
                        'federalTaxNumber' => 3817194000100,
                        'address' => [
                            'postalCode' => '05411-000',
                            'street' => 'Rua Cristiano Viana',
                            'number' => '517',
                            'district' => 'Cerqueira César',
                            'city' => [
                                'code' => '3550308',
                                'name' => 'São Paulo',
                            ],
                            'state' => 'SP',
                        ],
                        'status' => 'Active',
                        'type' => 'LegalPerson, Company',
                        'createdOn' => '2021-06-04T12:29:24.6179228Z',
                        'modifiedOn' => '2021-06-04T12:29:24.6179228Z',
                    ],
                    'borrower' => [
                        'taxRegime' => 0,
                        'legalNature' => 0,
                        'economicActivities' => [],
                        'issRate' => 0,
                        'id' => '60ba1db55daa5e4e2c27b71e',
                        'name' => 'BANCO DO BRASIL lA',
                        'federalTaxNumber' => 191,
                        'email' => 'anton.kovtun@requestum.com',
                        'address' => [
                            'country' => 'BRA',
                            'postalCode' => '70073901',
                            'street' => 'Outros Quadra 1 Bloco G Lote 32',
                            'number' => 'S/N',
                            'district' => 'centro',
                            'city' => [
                                'code' => '5300108',
                                'name' => 'Brasilia',
                            ],
                            'state' => 'DF',
                        ],
                        'status' => 'Active',
                        'type' => 'LegalPerson',
                        'createdOn' => '2021-12-14T14:54:04.8116768Z',
                    ],
                    'createdOn' => '2021-12-14T14:54:04.7426904Z',
                    'modifiedOn' => '2021-12-14T14:55:26.17007Z',
                    'issuedOn' => '2021-12-10T19:27:27.901Z',
                    'batchNumber' => 25993137786042,
                    'batchCheckNumber' => '20025993137786042',
                    'number' => 200,
                    'checkCode' => '20025993137786042',
                    'status' => 'Issued',
                    'rpsType' => 'Rps',
                    'rpsStatus' => 'Normal',
                    'taxationType' => 'WithinCity',
                    'rpsSerialNumber' => 'IO',
                    'rpsNumber' => 200,
                    'cityServiceCode' => '2800',
                    'federalServiceCode' => '01.05',
                    'description' => "Uso da Plataforma de criação de documentos online lexly.com.br\n\n\nCONFORME LEI 12.741/2012 o valor aproximado dos tributos é R$ 0,84 (15,56%), FONTE: IBPT/empresometro.com.br (21.1.F)",
                    'servicesAmount' => 5.4,
                    'deductionsAmount' => 0,
                    'discountUnconditionedAmount' => 0,
                    'discountConditionedAmount' => 0,
                    'baseTaxAmount' => 5.4,
                    'issRate' => 0.029,
                    'issTaxAmount' => 0.1566,
                    'irAmountWithheld' => 0,
                    'pisAmountWithheld' => 0,
                    'cofinsAmountWithheld' => 0,
                    'csllAmountWithheld' => 0,
                    'inssAmountWithheld' => 0,
                    'issAmountWithheld' => 0,
                    'othersAmountWithheld' => 0,
                    'amountWithheld' => 0,
                    'amountNet' => 5.4,
                    'approximateTax' => [
                        'source' => 'IBPT/empresometro.com.br',
                        'version' => '21.1.F',
                        'totalRate' => 0.1556,
                        'totalAmount' => 0,
                    ],
                    'additionalInformation' => 'additionalInformation777',
                ],
                'query' => [],
                'expectedCode' => Response::HTTP_BAD_REQUEST,
                'expectedContains' => [['Can`t found an invoice with Id 61b8b00c003cad45c4b28e93']],
            ],
            'good invoice Id' => [
                'user' => '',
                'parameters' => [
                    'id' => '61b8b00c003cad45c4b28e92',
                    'environment' => 'Development',
                    'flowStatus' => 'Issued',
                    'provider' => [
                        'tradeName' => 'ODILON COMERCIO E SERVICO',
                        'taxRegime' => 0,
                        'specialTaxRegime' => 'Nenhum',
                        'legalNature' => 'Empresario',
                        'issRate' => 0,
                        'parentId' => '60ba1bdf200cda86188095a3',
                        'id' => '60ba1ca45daa5e0fb00de46e',
                        'name' => 'ODILON BICUDO DA ROCHA',
                        'federalTaxNumber' => 3817194000100,
                        'address' => [
                            'postalCode' => '05411-000',
                            'street' => 'Rua Cristiano Viana',
                            'number' => '517',
                            'district' => 'Cerqueira César',
                            'city' => [
                                'code' => '3550308',
                                'name' => 'São Paulo',
                            ],
                            'state' => 'SP',
                        ],
                        'status' => 'Active',
                        'type' => 'LegalPerson, Company',
                        'createdOn' => '2021-06-04T12:29:24.6179228Z',
                        'modifiedOn' => '2021-06-04T12:29:24.6179228Z',
                    ],
                    'borrower' => [
                        'taxRegime' => 0,
                        'legalNature' => 0,
                        'economicActivities' => [],
                        'issRate' => 0,
                        'id' => '60ba1db55daa5e4e2c27b71e',
                        'name' => 'BANCO DO BRASIL lA',
                        'federalTaxNumber' => 191,
                        'email' => 'anton.kovtun@requestum.com',
                        'address' => [
                            'country' => 'BRA',
                            'postalCode' => '70073901',
                            'street' => 'Outros Quadra 1 Bloco G Lote 32',
                            'number' => 'S/N',
                            'district' => 'centro',
                            'city' => [
                                'code' => '5300108',
                                'name' => 'Brasilia',
                            ],
                            'state' => 'DF',
                        ],
                        'status' => 'Active',
                        'type' => 'LegalPerson',
                        'createdOn' => '2021-12-14T14:54:04.8116768Z',
                    ],
                    'createdOn' => '2021-12-14T14:54:04.7426904Z',
                    'modifiedOn' => '2021-12-14T14:55:26.17007Z',
                    'issuedOn' => '2021-12-10T19:27:27.901Z',
                    'batchNumber' => 25993137786042,
                    'batchCheckNumber' => '20025993137786042',
                    'number' => 200,
                    'checkCode' => '20025993137786042',
                    'status' => 'Issued',
                    'rpsType' => 'Rps',
                    'rpsStatus' => 'Normal',
                    'taxationType' => 'WithinCity',
                    'rpsSerialNumber' => 'IO',
                    'rpsNumber' => 200,
                    'cityServiceCode' => '2800',
                    'federalServiceCode' => '01.05',
                    'description' => "Uso da Plataforma de criação de documentos online lexly.com.br\n\n\nCONFORME LEI 12.741/2012 o valor aproximado dos tributos é R$ 0,84 (15,56%), FONTE: IBPT/empresometro.com.br (21.1.F)",
                    'servicesAmount' => 5.4,
                    'deductionsAmount' => 0,
                    'discountUnconditionedAmount' => 0,
                    'discountConditionedAmount' => 0,
                    'baseTaxAmount' => 5.4,
                    'issRate' => 0.029,
                    'issTaxAmount' => 0.1566,
                    'irAmountWithheld' => 0,
                    'pisAmountWithheld' => 0,
                    'cofinsAmountWithheld' => 0,
                    'csllAmountWithheld' => 0,
                    'inssAmountWithheld' => 0,
                    'issAmountWithheld' => 0,
                    'othersAmountWithheld' => 0,
                    'amountWithheld' => 0,
                    'amountNet' => 5.4,
                    'approximateTax' => [
                        'source' => 'IBPT/empresometro.com.br',
                        'version' => '21.1.F',
                        'totalRate' => 0.1556,
                        'totalAmount' => 0,
                    ],
                    'additionalInformation' => 'additionalInformation777',
                ],
                'query' => [],
                'expectedCode' => Response::HTTP_OK,
                'expectedContains' => [],
            ],
        ];
    }
}

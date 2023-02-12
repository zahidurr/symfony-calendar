<?php

declare(strict_types=1);

namespace App\Messenger;

use Exception;
use RuntimeException;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Serialization\Serializer as Base;

class Serializer extends Base
{
    public const TYPE_CLASS_MAPPING = [
        'RetrieveProductData' => RetrieveProductData::class,
        'RetrieveVoucherData' => RetrieveVoucherData::class,
        'SuccessfulPaymentFeedbackToAgreement' => SuccessfulPaymentFeedbackToAgreement::class,
        'CheckoutMail' => SendEmail::class,
        'EndCustomerUpdateAddress' => EndCustomerUpdateAddressMessage::class
    ];

    /**
     * {@inheritdoc}
     * @throws Exception
     */
    public function encode(Envelope $envelope): array
    {
        $encodedMessage = parent::encode($envelope);
        $class = $encodedMessage['headers']['type'];
        $type = $this->getTypeByClass($class);

        //TODO: verify after symfony & messenger upgrade

        $encodedMessage['headers']['type'] = $type;
        //$encodedMessage['headers'] = ['Headers' => \json_encode([['type' => $type], ['type' => $type]])];

        return $encodedMessage;
    }

    /**
     * @throws Exception
     */
    private function getTypeByClass(string $class): string
    {
        return $this->getMapping($class, array_flip(self::TYPE_CLASS_MAPPING));
    }

    /**
     * @throws Exception
     */
    private function getMapping(string $key, array $mapping): string
    {
        if (!isset($mapping[$key])) {
            throw new RuntimeException(sprintf('Unknown key %s', $key));
        }

        return $mapping[$key];
    }

    /**
     * {@inheritdoc}
     * @throws Exception
     */
    public function decode(array $encodedEnvelope): Envelope
    {
        $type = $encodedEnvelope['headers']['type'];
        $class = $this->getClassByType($type);
        $encodedEnvelope['headers']['type'] = $class;

        return parent::decode($encodedEnvelope);
    }

    /**
     * @throws Exception
     */
    private function getClassByType(string $type): string
    {
        return $this->getMapping($type, self::TYPE_CLASS_MAPPING);
    }
}

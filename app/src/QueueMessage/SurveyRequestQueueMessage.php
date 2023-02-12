<?php

declare(strict_types=1);


namespace App\QueueMessage;

use JsonSerializable;

class SurveyRequestQueueMessage implements QueueMessageInterface, JsonSerializable
{
    const QUEUE_KEY = 'surveyRequest';

    /**
     * SmsQueueMessage constructor.
     */
    public function __construct(
        public ?string $name,
        public string $email,
        public ?string $phone,
        public ?string $action,
        public ?string $customerID,
        public ?string $lawyerID,
        public ?string $tenantID,
        public ?string $cartID,
        public ?string $tag,
        public string $source,
        public string $type
    ) {}

    public function getQueueKey(): string
    {
        return self::QUEUE_KEY;
    }

    /**
     * Specify data which should be serialized to JSON.
     *
     * @see https://php.net/manual/en/jsonserializable.jsonserialize.php
     *
     * @return mixed data which can be serialized by <b>json_encode</b>,
     *               which is a value of any type other than a resource
     *
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'action' => $this->action,
            'customerID' => $this->customerID,
            'lawyerID' => $this->lawyerID,
            'cartID' => $this->cartID,
            'tenantID' => $this->tenantID,
            'tag' => $this->tag,
            'source' => $this->source,
            'type' => $this->type,
        ];
    }
}


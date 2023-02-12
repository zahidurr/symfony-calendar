<?php

namespace App\Tests\Mock;

use App\Message\CheckoutResults;
use App\MessageHandler\CheckoutResultsHandler as Base;

class CheckoutResultsHandler extends Base
{
    public function __invoke(CheckoutResults $message): void
    {
        dump($message);
        exit();
    }
}

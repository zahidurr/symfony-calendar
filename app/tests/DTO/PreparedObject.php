<?php

declare(strict_types=1);

namespace App\Tests\DTO;

class PreparedObject
{
    public function __construct(public string $class, public array $criteria)
    {
    }
}

<?php

declare(strict_types=1);

namespace App\Tests\Mock;

/**
 * Trait MockTrait.
 */
trait MockTrait
{
    private bool $mock = false;

    public function doMock(bool $mock = true): static
    {
        $this->mock = $mock;

        return $this;
    }
}

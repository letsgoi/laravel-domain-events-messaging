<?php

namespace Letsgoi\DomainEventsMessaging\Tests\Stubs;

use Illuminate\Foundation\Bus\Dispatchable;

class FakeJob
{
    use Dispatchable;

    /** @var string */
    public $payload;

    public function __construct(string $payload)
    {
        $this->payload = $payload;
    }

    public function handle(): void
    {
    }
}

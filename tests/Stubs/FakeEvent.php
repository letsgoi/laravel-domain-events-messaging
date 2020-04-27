<?php

namespace Letsgoi\DomainEventsMessaging\Tests\Stubs;

use Illuminate\Foundation\Events\Dispatchable;

class FakeEvent
{
    use Dispatchable;

    /** @var string */
    public $payload;

    public function __construct(string $payload)
    {
        $this->payload = $payload;
    }
}

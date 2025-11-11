<?php

namespace App\Domain\User\Events;

use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class UserCreated extends ShouldBeStored
{
    /**
     * @param array $attributes The main attributes of the user.
     *                          Keys: 'userId', 'email'.
     */
    public function __construct(
        public array $attributes,
    ) {}
}

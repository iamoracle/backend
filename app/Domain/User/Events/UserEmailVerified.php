<?php

namespace App\Domain\User\Events;

use DateTime;
use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class UserEmailVerified extends ShouldBeStored
{

    /**
     * @param string|null $userId The unique ID of the user.
     */
    public function __construct(
        public ?string $userId,
    ) {}
}

<?php

namespace App\Domain\User\Events;

use DateTime;
use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class UserDeleted extends ShouldBeStored
{
    /**
     * @param string $userId 
     * @param Dateime $createdAt The timestamp when the event was created.
     */
    public function __construct(
        public string $userId,
    ) {}
}

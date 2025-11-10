<?php

namespace App\Domain\User\Events;

use App\Enums\RoleKey;
use DateTime;
use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class UserDeleted extends ShouldBeStored
{
    /**
     * @param string $userId 
     * @param array $metadata Optional metadata about the event.
     */
    public function __construct(
        public string $userId,
        public DateTime $createdAt,
        public ?array $metadata = []
    ) {}
}

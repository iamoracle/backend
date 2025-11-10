<?php

namespace App\Domain\User\Events;

use DateTime;
use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class UserEmailVerified extends ShouldBeStored
{

    /**
     * @param string|null $userId The unique ID of the user.
     * @param DateTime $createdAt The timestamp when the event was created.
     * @param array $metadata Optional metadata about the event.
     */
    public function __construct(
        public ?string $userId,
        public DateTime $createdAt,
        public array $metadata = [] // optional metadata
    ) {}
}

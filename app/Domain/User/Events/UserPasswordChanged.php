<?php

namespace App\Domain\User\Events;

use DateTime;
use Spatie\EventSourcing\StoredEvents\ShouldBeStored;


class UserPasswordChanged extends ShouldBeStored
{
    /**
     * @param int $userId The unique ID of the newly created user (from the 'users' table).
     * @param string $password The password of the registered user.
     * @param DateTime $createdAt The timestamp when the event was created.
     * @param array $metadata Optional metadata about the event.
     */
    public function __construct(
        public string $userId,
        public string $password,
        public DateTime $createdAt,
        public array $metadata = [] // optional metadata
    ) {}
}

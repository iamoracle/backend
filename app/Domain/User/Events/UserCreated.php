<?php

namespace App\Domain\User\Events;

use App\Enums\RoleKey;
use DateTime;
use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class UserCreated extends ShouldBeStored
{
    /**
     * @param array $attributes The main attributes of the user.
     *                          Keys: 'userId', 'email'.
     * @param array $metadata Optional metadata about the event.
     */
    public function __construct(
        public array $attributes,
        public DateTime $createdAt,
        public ?array $metadata = []
    ) {}
}

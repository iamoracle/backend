<?php

namespace App\Domain\User\Events;

use DateTime;
use Spatie\EventSourcing\StoredEvents\ShouldBeStored;


class UserPasswordChanged extends ShouldBeStored
{
    /**
     * @param int $userId The unique ID of the newly created user (from the 'users' table).
     * @param string $password The password of the registered user.
     */
    public function __construct(
        public string $userId,
        public string $password,
    ) {}
}

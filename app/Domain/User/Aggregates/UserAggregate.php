<?php

namespace App\Aggregates;

use App\Domain\User\Events\UserCreated;
use App\Domain\User\Events\UserDeleted;
use App\Domain\User\Events\UserEmailVerified;
use App\Domain\User\Events\UserPasswordChanged;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;

class UserAggregate extends AggregateRoot
{
    public function createUser(string $id, string $email, string $password)
    {
        $this->recordThat(new UserCreated(
            attributes: [
                'email' => $email,
                'password' => $password,
                'id' => $id,
            ],
        ));

        return $this;
    }

    public function changePassword(string $userId, string $password)
    {
        $this->recordThat(new UserPasswordChanged(
            userId: $userId,
            password: $password,
        ));

        return $this;
    }

    public function verifyEmail(string $userId)
    {
        $this->recordThat(new UserEmailVerified(
            userId: $userId,
        ));

        return $this;
    }


    public function deleteUser(string $userId)
    {
        $this->recordThat(new UserDeleted(
            userId: $userId,
        ));

        return $this;
    }
}

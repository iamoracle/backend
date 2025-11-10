<?php

namespace App\Domain\User\Projectors;

use App\Domain\User\Events\UserCreated;
use App\Domain\User\Events\UserEmailVerified;
use App\Domain\User\Projections\UserProjection;
use Spatie\EventSourcing\EventHandlers\Projectors\Projector;

class UserProjector extends Projector
{
    public function onUserCreated(UserCreated $event)
    {
        (new UserProjection($event->attributes))->writeable()->save();
    }

    public function onUserEmailVerified(UserEmailVerified $event)
    {
        $user = UserProjection::uuid($event->userId);
        if ($user) {
            $user->email_verified_at = $event->createdAt;
            $user->writeable()->save();
        }
    }

    public function onUserPasswordChanged($event)
    {
        $user = UserProjection::uuid($event->userId);
        if ($user) {
            $user->password = $event->password;
            $user->writeable()->save();
        }
    }

    public function onUserDeleted($event)
    {
        $user = UserProjection::uuid($event->userId);
        if ($user) {
            $user->delete();
        }
    }
}

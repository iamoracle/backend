<?php

namespace App\Domain\User\Projections;

use App\Domain\User\Events\UserCreated;
use App\Domain\User\Events\UserDeleted;
use App\Domain\User\Events\UserEmailVerified;
use App\Domain\User\Events\UserPasswordChanged;
use DateTime;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;
use Spatie\EventSourcing\Projections\Projection;

class UserProjection extends Projection
{
    protected $guarded = [];
    protected $table = 'users';

    public static function createUser(array $attributes)
    {
        $attributes["id"] = Uuid::uuid4()->toString();

        event(new UserCreated($attributes, new DateTime()));

        return static::uuid($attributes["id"]);
    }

    public static function changePassword(string $userId, string $password)
    {
        event(new UserPasswordChanged($userId, Hash::make($password), new DateTime()));
    }

    public static function verifyEmail(string $userId)
    {
        event(new UserEmailVerified($userId, new DateTime()));
    }

    public static function deleteUser(string $userId)
    {
        event(new UserDeleted($userId, new DateTime()));
    }

    public static function uuid(string $userId): ?UserProjection
    {
        return static::where('id', $userId)->first();
    }
}

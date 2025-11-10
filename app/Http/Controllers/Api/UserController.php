<?php

namespace App\Http\Controllers\Api;

use App\Aggregates\UserAggregate;
use App\Enums\RoleKey;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\UserResource;
use Ramsey\Uuid\Uuid;

use function Laravel\Prompts\password;

class UserController extends Controller
{
    // User registration
    public function register(RegisterUserRequest $request)
    {
        // Generate the aggregate ID (UUID)
        $userId = Uuid::uuid4()->toString();

        // Apply domain events
        UserAggregate::retrieve($userId)
            ->createUser(
                id: $userId,
                email: $request->email,
                password: Hash::make($request->password),
            )
            ->persist();

        // Retrieve the read model after projection
        $user = User::findOrFail($userId);

        return new UserResource($user);
    }
}

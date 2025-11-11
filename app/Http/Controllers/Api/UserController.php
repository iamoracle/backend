<?php

namespace App\Http\Controllers\Api;

use App\Aggregates\UserAggregate;
use App\Http\Controllers\Controller;
use App\Http\Dtos\User\RegisterUserDto;
use App\Http\Dtos\User\LoginUserDto;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Ramsey\Uuid\Uuid;

class UserController extends Controller
{

    /**
     * User registration through the event-sourced aggregate
     */
    public function register(RegisterUserDto $request)
    {
        $data = $request->validated();

        $userId = Uuid::uuid4()->toString();

        UserAggregate::retrieve($userId)
            ->createUser(
                id: $userId,
                email: $data['email'],
                password: Hash::make($data['password']),
            )
            ->persist();

        $user = User::findOrFail($userId);

        return new UserResource($user);
    }

    /**
     * Login using JWT
     */
    public function login(LoginUserDto $request)
    {
        $credentials = $request->only("email", "password");


        if (! $token = auth()->attempt($credentials)) {
            return response()->json([
                'error' => 'Unauthorized',
            ], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Authenticated user profile
     */
    public function me()
    {
        return new UserResource(auth()->user());
    }

    /**
     * Invalidate (logout) current JWT
     */
    public function logout()
    {
        auth()->logout();

        return response()->json([
            'message' => 'Successfully logged out',
        ]);
    }

    /**
     * Refresh token
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Standard JWT token response structure
     */
    protected function respondWithToken(string $token)
    {
        return response()->json([
            'data' => [
                'accessToken' => $token,
                'tokenType'   => 'bearer',
                'expiresIn'   => auth()->factory()->getTTL() * 60,
            ]
        ]);
    }
}

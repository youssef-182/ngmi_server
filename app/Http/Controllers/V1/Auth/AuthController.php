<?php

namespace App\Http\Controllers\V1\Auth;

use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Auth\LoginUserRequest;
use App\Http\Requests\V1\Auth\RegisterUserRequest;
use App\Http\Resources\V1\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(RegisterUserRequest $request)
    {
        $req = $request->validated();
        $user = User::create([
            'first_name' => ucfirst($req['firstName']),
            'last_name' => ucfirst($req['lastName']),
            'username' => $req['username'],
            'email' => $req['email'],
            // 'type' => $req['type'],
            'gender' => $req['gender'],
            'birth_date' => $req['birthDate'],
            'password' => Hash::make($req['password']),
        ]);

        $token = $user->createToken('ngmi' . $req['username'], ['read', 'create', 'update', 'delete'])->plainTextToken;

        $data = [
            'tokenDetails' => [
                'token' => $token,
                'type' => 'Bearer',
            ],
        ];
        return response()->json($data, 201);
    }

    public function login(LoginUserRequest $request)
    {
        $req = $request->validated();
        $user = User::where('username', $req['username'])->first();

        if(!$user || !Hash::check($req['password'], $user->password)) {
            throw ValidationException::withMessages([
                'username' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('ngmi-' . $user->username, ['read', 'create', 'update', 'delete'])->plainTextToken;

        $data = [
            'tokenDetails' => [
                'token' => $token,
                'type' => 'Bearer',
            ],
        ];
        return response()->json($data, 200);
    }
}

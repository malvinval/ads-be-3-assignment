<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    use ApiResponse;

    public function login(LoginRequest $request) {
        // Get the validated data from the request.
        $request->validated($request->all());

        // check if credentials exist in db
        if (!Auth::attempt([
            "email" => $request->email,
            "password" => $request->password
        ])) {
            return $this->error("Wrong email/password!", null, 403);
        }

        // if credential exist, generate new token.
        $user = User::where("email", $request->email)->first();

        return $this->success("Logged in", [
            "token" => $user->createToken($user->name, [$user->role])->plainTextToken
        ]);
    }

    public function register(RegisterRequest $request) {
        $request->validated($request->all());

        $user = User::create([
            "name" => $request->name,
            "email" => $request->email,
            "role" => $request->role,
            "password" => Hash::make($request->password) // bcrypt
        ]);

        return $this->success("You are registered!", [
            "detail" => $user,
            "token" => $user->createToken($user->name, [$user->role])->plainTextToken
        ]);
    }

    public function logout() {
        $user = Auth::user();
        $user->tokens()->delete(); // Hapus semua token akses personal pengguna

        return $this->success("Logged out", null);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repositories\Contracts\AuthRepositoryInterface;
use App\Repositories\Contracts\WalletRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Creates a user
     * .
     *
     * @param Request $request
     * @return array
     */
    public function store(Request $request, AuthRepositoryInterface $authRepository)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'cpf' => 'required|string|size:11',
            'cnpj' => 'required|string|size:14',
            'type' => 'required|string|in:common,seller',
        ]);
        $data['password'] = Hash::make($data['password']);
        $user = $authRepository->store($data);
        $token = $user->createToken('auth_token')->plainTextToken;
        return [
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user_id' => $user->id
        ];
    }

    public function login(Request $request, AuthRepositoryInterface $authRepository)
    {
        $data = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid login details'
            ], 401);
        }

        $user = $authRepository->findByEmail($data['email']);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function show(Request $request){
        return $request->user('api');
    }
}

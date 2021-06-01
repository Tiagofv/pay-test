<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\AuthRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/register",
     *      operationId="storeUser",
     *      tags={"Auth"},
     *      summary="Creates a user.",
     *      description="Creates a user.",
     *      @OA\Response(
     *          response=200,
     *          @OA\MediaType(mediaType="application/json"),
     *          description="successful operation",
     *       ),
     *      @OA\Response(
     *          response=422,
     *          @OA\MediaType(mediaType="application/json"),
     *          description="Invalid data supplied",
     *       ),
     *     @OA\RequestBody(
     *         description="Input data format",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="name",
     *                     description="Name",
     *                     type="string",
     *                 ),
     *                 @OA\Property(
     *                     property="email",
     *                     description="Email",
     *                     type="string"
     *                 ),
     *                  @OA\Property(
     *                     property="password",
     *                     description="Password",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="cpf",
     *                     description="CPF",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="cnpj",
     *                     description="CNPJ",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="type",
     *                     description="Type",
     *                     type="string"
     *                 ),
     *             )
     *         )
     *     )
     *     )
     * Creates a user
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
            'cnpj' => 'nullable|string|size:14',
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

    /**
     * @OA\Post(
     *      path="/api/login",
     *      operationId="loginUser",
     *      tags={"Auth"},
     *      summary="Login.",
     *      description="Login user.",
     *      @OA\Response(
     *          response=200,
     *          @OA\MediaType(mediaType="application/json"),
     *          description="successful operation",
     *       ),
     *      @OA\Response(
     *          response=422,
     *          @OA\MediaType(mediaType="application/json"),
     *          description="Invalid data supplied",
     *       ),
     *     @OA\RequestBody(
     *         description="Input data format",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",

     *                 @OA\Property(
     *                     property="email",
     *                     description="Email",
     *                     type="string"
     *                 ),
     *                  @OA\Property(
     *                     property="password",
     *                     description="Password",
     *                     type="string"
     *                 ),
     *             )
     *         )
     *     )
     *     )
     * @param Request $request
     * @param AuthRepositoryInterface $authRepository
     * @return \Illuminate\Http\JsonResponse
     */
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

        /**
         * @OA\Get(
         *      path="/api/detail",
         *      operationId="getUser",
         *      tags={"Auth"},
         *      summary="Gets the authenticated user",
         *      description="Gets the authenticated user",
         *      @OA\Response(
         *          response=200,
         *          @OA\MediaType(mediaType="application/json"),
         *          description="successful operation",
         *       ),
         *      @OA\Response(
         *          response=401,
         *          @OA\MediaType(mediaType="application/json"),
         *          description="Unauthenticated.",
         *       ),
         *     )
         * **/
    public function show(Request $request)
    {
        return $request->user('api')->load('wallet');
    }
}

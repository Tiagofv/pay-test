<?php

namespace App\Http\Controllers;


use App\Repositories\Contracts\WalletRepositoryInterface;


class WalletController extends Controller
{

    /**
     * @OA\Info(
     *      version="1.0.0",
     *      title="Picpay test Open api",
     *      description="Tiago braga test for picpay.",
     *      @OA\Contact(
     *          email="tiagofvx@gmail.com"
     *      ),
     *     @OA\License(
     *         name="None",
     *     )
     * )
     *
     */
    /**
     * @OAS\SecurityScheme(
     * securityScheme="bearerAuth",
     * type="http",
     * scheme="bearer"
     * )
     **/
    /**
     *
     * @OA\Get(
     *      path="/api/wallets",
     *      operationId="getWalletsIndex",
     *      tags={"Wallets"},
     *      summary="Get list of all Wallets. Must be authenticated.",
     *      description="Returns list of wallets",
     *     @OA\SecurityScheme(
     *     type="http",
     *     scheme="bearer",
     *     bearerFormat="JWT"
     * ),
     *      @OA\Response(
     *          response=200,
     *          @OA\MediaType(mediaType="application/json"),
     *          description="successful operation",
     *       ),
     *       @OA\Response(
     *          response=401,
     *          @OA\MediaType(mediaType="application/json"),
     *          description="Unauthenticated.",
     *       ),
     *     security={
     *         {"bearerToken": {}}
     *     }
     *     )
     *
     * Returns list of projects
     *
     * List of the resources
     * @param WalletRepositoryInterface $walletRepository
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(WalletRepositoryInterface $walletRepository)
    {
        $data = $walletRepository->all();
        return response()->json($data);
    }

    /**
     * @OA\Get(
     *      path="/api/wallets/{id}",
     *      operationId="getWallet",
     *      tags={"Wallets"},
     *      summary="Get detail about a wallet. Must be authenticated.",
     *      description="Returns list of wallets",
     *      @OA\Parameter(
     *          name="id",
     *          description="Wallet id",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *     @OA\SecurityScheme(
     *     type="http",
     *     scheme="bearer",
     *     bearerFormat="JWT"
     * ),
     *      @OA\Response(
     *          response=200,
     *          @OA\MediaType(mediaType="application/json"),
     *          description="successful operation",
     *       ),
     *       @OA\Response(
     *          response=401,
     *          @OA\MediaType(mediaType="application/json"),
     *          description="Unauthenticated.",
     *       ),
     *     security={
     *         {"bearerToken": {}}
     *     }
     *     )
     * /**
     * @param int $id
     * @param WalletRepositoryInterface $walletRepository
     * @return mixed
     */
    public function show(int $id, WalletRepositoryInterface $walletRepository)
    {
        $wallet = $walletRepository->show($id);
        return response()->json($wallet);
    }

}

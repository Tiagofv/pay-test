<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransferStoreRequest;
use App\Repositories\Contracts\TransferRepositoryInterface;
use Illuminate\Validation\ValidationException;

class TransferController extends Controller
{

    /**
     * @OA\Get(
     *      path="/api/transfers",
     *      operationId="getTransfersIndex",
     *      tags={"Transfers"},
     *      summary="Get all transfers. Must be authenticated.",
     *      description="Returns list of transfers for the authenticated user.",
     *      security={{ "bearerAuth" : {} }},
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
     *     )
     * /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(TransferRepositoryInterface $transferRepository)
    {
        $authenticatable = auth()->user();
        $allPerCommonUser = $transferRepository->allPerUser(
            $authenticatable
        );
        return response()->json($allPerCommonUser);
    }

    /**
     * @OA\Post(
     *      path="/api/transfers",
     *      operationId="storeTransfer",
     *      tags={"Transfers"},
     *      summary="Stores a transfer. Must be authenticated.",
     *      description="Stores a transfer",
     *      security={{ "bearerAuth" : {} }},
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
     *     },
     *     @OA\RequestBody(
     *         description="Input data format",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(
     *                     property="payee_id",
     *                     description="The id of the receiver",
     *                     type="integer",
     *                 ),
     *                 @OA\Property(
     *                     property="amount",
     *                     description="The amount that the user wants to transfer",
     *                     type="number"
     *                 )
     *             )
     *         )
     *     )
     *
     *     )
     *
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(TransferStoreRequest $request, TransferRepositoryInterface $transferRepository)
    {
        $data = $request->validated();
        $authenticatable = auth()->user();
        $amount = $data['amount'];
        if ($authenticatable->type === 'seller') {
            throw ValidationException::withMessages([
                "type" => "You cannot make transfers, only receive."
            ]);
        }
        if (!$transferRepository->verifiesUserHasBalance($authenticatable, $amount)) {
            ;
            throw ValidationException::withMessages([
                "balance" => "You're out of balance. Please cash in some money."
            ]);
        }
        $transfer = $transferRepository->store([
            'payer_id' => $authenticatable->id,
            'payee_id' => $data['payee_id'],
            'amount' => $amount
        ]);
        return response()->json($transfer);
    }


    /**
     * @OA\Get(
     *      path="/api/transfers/{id}",
     *      operationId="getTransfer",
     *      tags={"Transfers"},
     *      summary="Get detail about a transfer. Must be authenticated.",
     *      description="Returns a transfer",
     *      security={{ "bearerAuth" : {} }},
     *      @OA\Parameter(
     *          name="id",
     *          description="Transfer uuid",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
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
     *      @OA\Response(
     *          response=404,
     *          @OA\MediaType(mediaType="application/json"),
     *          description="Transfer not found",
     *       ),
     *     )
     * Display the specified resource.
     *
     * @param \App\Models\Transfer $transfer
     * @return \Illuminate\Http\Response
     */
    public function show(TransferRepositoryInterface $repository,  $transfer)
    {
        return response()->json($repository->showByUuid(auth()->user(), $transfer));
    }

}

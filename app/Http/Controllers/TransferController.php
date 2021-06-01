<?php

namespace App\Http\Controllers;

use App\Events\TransferReceived;
use App\Exceptions\OutOfMoneyException;
use App\Http\Requests\TransferStoreRequest;
use App\Models\Transfer;
use App\Repositories\Contracts\TransferRepositoryInterface;
use http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TransferController extends Controller
{
    /**
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
        if($authenticatable->type === 'seller') {
            throw ValidationException::withMessages([
                "type" => "You cannot make transfers, only receive."
            ]);
        }
        if (!$transferRepository->verifiesUserHasBalance($authenticatable, $amount)){;
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
     * Display the specified resource.
     *
     * @param \App\Models\Transfer $transfer
     * @return \Illuminate\Http\Response
     */
    public function show(TransferRepositoryInterface $repository, int $transfer)
    {
        return response()->json($repository->show($transfer));
    }

}

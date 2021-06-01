<?php

namespace App\Http\Controllers;

use App\Http\Requests\WalletRequest;
use App\Repositories\Contracts\WalletRepositoryInterface;
use Illuminate\Http\Request;

class WalletController extends Controller
{
    /**
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
     * Stores the wallet
     * @param WalletRequest $walletRequest
     * @param WalletRepositoryInterface $walletRepository
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(WalletRequest $walletRequest, WalletRepositoryInterface $walletRepository)
    {
        $newWallet = $walletRepository->store($walletRequest->validated());
        return response()->json($newWallet);
    }

    /**
     * @param int $id
     * @param WalletRepositoryInterface $walletRepository
     * @return mixed
     */
    public function show(int $id, WalletRepositoryInterface $walletRepository){
        $wallet = $walletRepository->show($id);
        return $wallet;
    }

    /**
     * @param int $id
     * @param WalletRequest $walletRequest
     * @param WalletRepositoryInterface $walletRepository
     */
    public function update(int $id, WalletRequest $walletRequest, WalletRepositoryInterface $walletRepository){
        $updateWallet = $walletRepository->update($id, $walletRequest->validated());
        return response()->json($updateWallet);
    }

    public function delete(int $id, WalletRepositoryInterface $walletRepository){
        $deletedWallet = $walletRepository->delete($id);
        return response()->json($deletedWallet);
    }
}

<?php


namespace App\Repositories\Eloquent;


use App\Models\Transfer;
use App\Repositories\Contracts\TransferRepositoryInterface;
use App\Services\MockAuthorizeService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class TransferRepository extends BaseRepository implements TransferRepositoryInterface
{

    protected $model = Transfer::class;

    /**
     * @param mixed $user
     * @param string $type
     * @return Collection
     */
    public function allPerUser($user, int $paginate = 15): LengthAwarePaginator
    {
        $userId = $user->id;
        $type = $user->type;
        return Transfer::where('payee_id', $userId)
            ->when($type === 'common', function ($query) use ($userId) {
                // When the user is common, we fetch all records even when he was the payer
                return $query->orWhere('payer_id', $userId);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($paginate);
    }


    /**
     * @param $user
     * @param float $amount
     * @return bool
     */
    public function verifiesUserHasBalance($user, float $amount): bool
    {
        $wallet = $user->wallet;
        if (!$wallet) return false;
        return $wallet->value >= $amount;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function store(array $data=[]): Transfer
    {
        $transfer = DB::transaction(function () use ($data) {
            $createTransfer = $this->model->create($data);
            (new MockAuthorizeService())->authorizeTransaction();
            return $createTransfer;
        });
        return $transfer;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function showByUuid($user, string $uuid): Transfer
    {
        $transfer = Transfer::findOrFail($uuid);
        if($transfer->payer_id !== $user->id && $transfer->payee_id !== $user->id){
            abort(404, 'Transfer not found.');
        }
        return $transfer;
    }


}

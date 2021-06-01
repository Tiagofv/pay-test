<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transfer extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id = (string)\Illuminate\Support\Str::uuid();
        });
    }


    /**
     * @return BelongsTo
     */
    public function payer() : BelongsTo
    {
        return $this->belongsTo(User::class, 'payer_id');
    }

    /**
     * @return BelongsTo
     */
    public function payee() : BelongsTo
    {
        return $this->belongsTo(User::class, 'payee_id');
    }
}

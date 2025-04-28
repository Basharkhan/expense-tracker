<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model {
    protected $fillable = [
        'amount',
        'category',
        'description',
        'date',
        'user_id',
        'budget_id',
    ];

    public function user(): BelongsTo {
        return $this->belongsTo( User::class );
    }

    public function budget(): BelongsTo {
        return $this->belongsTo( Budget::class );
    }
}

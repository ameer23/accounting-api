<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Entry extends Model
{
 
 
 
 public function transaction(): BelongsTo {
    return $this->belongsTo(Transaction::class);
}

public function account(): BelongsTo {
    return $this->belongsTo(Account::class);
}
}

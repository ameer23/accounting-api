<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    //




    public function entries(): HasMany {
    return $this->hasMany(Entry::class);
}

public function user(): BelongsTo {
    return $this->belongsTo(User::class, 'created_by');
}
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Enums\TransactionStatus;

class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'reference',   
        'description', 
        'status',
    ];

    /**
     * Get the user that created the transaction.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the entries for the transaction.
     */
    public function entries(): HasMany
    {
        return $this->hasMany(Entry::class);
    }

    protected function casts(): array
    {
        return [
            'status' => TransactionStatus::class,
        ];}


         protected $appends = ['amount'];

  
    protected function amount(): Attribute
    {
        return Attribute::make(
        
            get: fn () => $this->entries->first()?->amount
        );
    }
}
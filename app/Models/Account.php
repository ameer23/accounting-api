<?php

namespace App\Models;

use App\Enums\AccountType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Account extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 
        'type', 
        'is_active',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'type' => AccountType::class,
        ];
    }

     protected $appends = ['balance'];

     protected function balance(): Attribute 
    {
        return Attribute::make(
            get: function () {
                $debits = $this->entries()->where('type', 'debit')->sum('amount');
                $credits = $this->entries()->where('type', 'credit')->sum('amount');

                $balance = 0;

                switch ($this->type) {
                    case AccountType::Asset:
                    case AccountType::Expense:
                        $balance = $debits - $credits;
                        break;
                    case AccountType::Liability:
                    case AccountType::Equity:
                    case AccountType::Income:
                        $balance = $credits - $debits;
                        break;
                }
                
                return number_format($balance, 2, '.', '');
            }
        );
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExpenseBudget extends Model
{
    use HasFactory;

    protected $table = 'expense_budgets';

    protected $fillable = [
        'user_id',
        'label',
        'max_amount',
    ];

    protected $casts = [
        'max_amount' => 'int',
    ];
}

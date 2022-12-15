<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCard extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'last4_digits',
        'brand',
        'exp_month',
        'exp_year'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}

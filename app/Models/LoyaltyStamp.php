<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoyaltyStamp extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'stamps',
    ];

    protected $casts = [
        'stamps' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Palielina zīmogu skaitu
    public function addStamp()
    {
        if ($this->stamps < 10) {
            $this->increment('stamps');
        }
        return $this;
    }

    public function resetStamps()
    {
        $this->update(['stamps' => 0]);
    }
}
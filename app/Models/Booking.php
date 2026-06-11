<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'procedure_id',
        'date',
        'time',
        'details',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
        'time' => 'datetime:H:i',
    ];

    // Relācijas
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function procedure()
    {
        return $this->belongsTo(Procedure::class);
    }

    // Palīgfunkcijas
    public function getStatusColor()
    {
        return match($this->status) {
            'pending' => 'yellow',
            'confirmed' => 'blue',
            'arrived' => 'green',
            'no_show' => 'red',
            default => 'gray',
        };
    }
}
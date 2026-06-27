<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'client_name',
        'procedure_id',
        'schedule_procedure_id',
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

    public function scheduleProcedure()
    {
        return $this->belongsTo(ScheduleProcedure::class);
    }

    public function getProcedureName(): string
    {
        if ($this->scheduleProcedure) {
            return $this->scheduleProcedure->getName();
        }

        return $this->procedure?->getName() ?? '-';
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
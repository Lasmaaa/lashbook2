<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'client_name',
        'procedure_id',
        'schedule_procedure_id',
        'schedule_subtopic_id',
        'procedure_subtopic_id',
        'date',
        'time',
        'details',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
        'time' => 'datetime:H:i',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function procedure(): BelongsTo
    {
        return $this->belongsTo(Procedure::class);
    }

    public function scheduleProcedure(): BelongsTo
    {
        return $this->belongsTo(ScheduleProcedure::class);
    }

    public function scheduleSubtopic(): BelongsTo
    {
        return $this->belongsTo(ScheduleProcedureSubtopic::class, 'schedule_subtopic_id');
    }

    public function procedureSubtopic(): BelongsTo
    {
        return $this->belongsTo(ProcedureSubtopic::class, 'procedure_subtopic_id');
    }

    public function getProcedureName(): string
    {
        if ($this->scheduleSubtopic) {
            return $this->scheduleProcedure
                ? $this->scheduleProcedure->getName() . ' — ' . $this->scheduleSubtopic->getName()
                : $this->scheduleSubtopic->getName();
        }

        if ($this->procedureSubtopic) {
            return $this->procedure
                ? $this->procedure->getName() . ' — ' . $this->procedureSubtopic->getName()
                : $this->procedureSubtopic->getName();
        }

        if ($this->scheduleProcedure) {
            return $this->scheduleProcedure->getName();
        }

        return $this->procedure?->getName() ?? '-';
    }

    public function getStatusColor(): string
    {
        return match ($this->status) {
            'pending' => 'yellow',
            'confirmed' => 'blue',
            'arrived' => 'green',
            'no_show' => 'red',
            default => 'gray',
        };
    }
}

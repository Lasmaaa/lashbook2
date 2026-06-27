<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScheduleProcedureSubtopic extends Model
{
    protected $fillable = [
        'schedule_procedure_id',
        'name_lv',
        'name_en',
        'name_ru',
        'price',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function scheduleProcedure(): BelongsTo
    {
        return $this->belongsTo(ScheduleProcedure::class);
    }

    public function getName(?string $lang = null): string
    {
        $lang = $lang ?? app()->getLocale();

        return match ($lang) {
            'en' => $this->name_en,
            'ru' => $this->name_ru,
            default => $this->name_lv,
        };
    }
}

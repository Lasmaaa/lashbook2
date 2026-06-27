<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProcedureSubtopic extends Model
{
    protected $fillable = [
        'procedure_id',
        'name_lv',
        'name_en',
        'name_ru',
        'price',
        'sort_order',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function procedure(): BelongsTo
    {
        return $this->belongsTo(Procedure::class);
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

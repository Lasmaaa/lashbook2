<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduleProcedure extends Model
{
    protected $fillable = [
        'date',
        'name_lv',
        'name_en',
        'name_ru',
        'price',
        'sort_order',
    ];

    protected $casts = [
        'date' => 'date',
        'price' => 'decimal:2',
    ];

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

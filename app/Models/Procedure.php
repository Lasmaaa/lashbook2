<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Procedure extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_lv',
        'name_en',
        'name_ru',
        'duration',
        'price',
        'code',
    ];

    public function subtopics(): HasMany
    {
        return $this->hasMany(ProcedureSubtopic::class)->orderBy('sort_order')->orderBy('id');
    }

    // Palīdzība valodu izvēlei
    public function getName($lang = null)
    {
        $lang = $lang ?? app()->getLocale();
        
        return match($lang) {
            'en' => $this->name_en,
            'ru' => $this->name_ru,
            default => $this->name_lv,
        };
    }
}
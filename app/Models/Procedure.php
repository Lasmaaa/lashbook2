<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Procedure extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_lv',
        'name_en',
        'name_ru',
        'duration',
        'price',
    ];

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
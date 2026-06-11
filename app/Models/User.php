<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'surname',
        'email',
        'phone',
        'password',
        'loyalty_code',
        'usertype',
        'preferred_language',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relācijas
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function loyaltyStamp()
    {
        return $this->hasOne(LoyaltyStamp::class);
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }

    // Palīgfunkcijas
    public function fullName()
    {
        return $this->name . ' ' . $this->surname;
    }

    public function isAdmin()
    {
        return $this->usertype === 'admin';
    }

    public function getLoyaltyStampsCount()
    {
        return $this->loyaltyStamp?->stamps ?? 0;
    }

    public function ensureLoyaltyCode(): string
    {
        if (!empty($this->loyalty_code)) {
            return $this->loyalty_code;
        }

        do {
            $code = strtoupper(Str::random(8));
        } while (self::where('loyalty_code', $code)->exists());

        $this->forceFill(['loyalty_code' => $code])->save();

        return $code;
    }

//     $code = strtoupper(Str::random(8));

// $user->loyalty_code = $code;
// $user->save();

// QrCode::size(400)
//     ->format('png')
//     ->generate($code, storage_path('app/public/qrcodes/' . $user->id . '.png'));
}
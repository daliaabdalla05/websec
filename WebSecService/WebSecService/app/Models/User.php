<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    use HasRoles;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function purchases()
{
    return $this->hasMany(Purchase::class);
}
public function boughtProducts()
{
    return $this->belongsToMany(Product::class, 'user_bought_products')
                ->withPivot('bought_at')
                ->orderByDesc('user_bought_products.bought_at');
}
// app/Models/User.php
public function getBoughtProducts()
{
    return $this->boughtProducts()
        ->withPivot(['bought_at', 'price_at_purchase', 'status'])
        ->orderByDesc('user_bought_products.bought_at')
        ->get();
}

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property string $role
 */

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Kādus laukus drīkst aizpildīt masveidā
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    // Kādus laukus slēpt (nerādīt)
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Kādus laukus konvertēt uz citu tipu
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // lomas

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isEmployee()
    {
        return $this->role === 'employee';
    }

    public function isRegularUser()
    {
        return $this->role === 'user';
    }

    // relaciijas
    public function recipes()
    {
        return $this->hasMany(Recipe::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Panel;
use App\Enums\UserRoleEnum;
use Illuminate\Support\Str;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements FilamentUser
{
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
        'role'
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

    /**
     * Get the user's initials
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->take(2)
            ->map(fn ($word) => Str::substr($word, 0, 1))
            ->implode('');
    }

    public function schools(): HasMany
    {
        return $this->hasMany(School::class);
    }

    public function school(): HasOne
    {
        return $this->hasOne(School::class);
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === UserRoleEnum::SuperAdmin->value;
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRoleEnum::Admin->value;
    }

    public function isUser(): bool
    {
        return $this->role === UserRoleEnum::User->value;
    }

    public function canAccessPanel(Panel $panel): bool
    {
      //   return str_ends_with($this->email, '@yourdomain.com') && $this->hasVerifiedEmail();
      return in_array($this->role, [
            UserRoleEnum::Admin->value,
            UserRoleEnum::SuperAdmin->value,
      ]);
   }
}

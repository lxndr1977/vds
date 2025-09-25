<?php

namespace App\Models;

use Mary\Traits\Toast;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class School extends Model
{
    use HasFactory, Toast;

    protected $fillable = [
        'user_id',
        'name', 
        'street',
        'number',
        'complement',
        'district',
        'city',
        'state',
        'zip_code',
        'responsible_name',
        'responsible_email',
        'responsible_phone',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function dancers(): HasMany
    {
        return $this->hasMany(Dancer::class);
    }

    public function choreographers(): HasMany
    {
        return $this->hasMany(Choreographer::class);
    }
    
    public function members(): HasMany
    {
        return $this->hasMany(Member::class);
    }
    
    public function choreographies(): HasMany
    {
        return $this->hasMany(Choreography::class);
    }

    public function getTotalRegistrationFee(): float
    {
        return $this->choreographies->sum(function ($choreography) {
            return $choreography->getRegistrationFee();
        });
    }

    public function isOwnedBy(User $user): bool
    {
        return $this->user_id === $user->id;
    }

    public function registration(): HasOne
    {
        return $this->hasOne(Registration::class);
    }

       public function getMembersCountByType()
   {
      return $this->members()
         ->join('member_types', 'members.member_type_id', '=', 'member_types.id')
         ->groupBy('member_types.id', 'member_types.name')
         ->selectRaw('member_types.name, COUNT(*) as total')
         ->pluck('total', 'name');
   }
}

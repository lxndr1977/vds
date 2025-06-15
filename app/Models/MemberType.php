<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MemberType extends Model
{
    protected $fillable = ['name', 'description', 'max_limit'];

    public function members() 
    {
        return $this->hasMany(Member::class);
    }
    
    public function fees(): HasMany
    {
        return $this->hasMany(MemberFee::class);
    }

    public function getCurrentFee(): ?MemberFee
    {
        return $this->fees()
            ->where('valid_until', '>=', now())
            ->orderBy('created_at', 'desc')
            ->first();
    }
}

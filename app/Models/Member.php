<?php

namespace App\Models;

use App\Models\User;
use App\Models\School;
use App\Models\MemberType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Member extends Model
{
    protected $fillable = ['name', 'member_type_id', 'school_id', 'phone', 'email'];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function memberType()
    {
        return $this->belongsTo(MemberType::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function members(): HasMany
    {
        return $this->hasMany(Member::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DanceStyle extends Model
{
    protected $fillable = ['name'];

    public function choreographies()
    {
        return $this->hasMany(Choreography::class);
    }
}

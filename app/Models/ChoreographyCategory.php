<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChoreographyCategory extends Model
{
    public function choreographies()
    {
        return $this->hasMany(Choreography::class);
    }
}

<?php

namespace App\Models;

use App\Models\Choreography;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Choreographer extends Model
{
    protected $fillable = ['name', 'school_id'];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($choreographer) {
            if ($choreographer->choreographies()->exists()) {
                throw new \Exception('Não é possível excluir este coreógrafo, pois ele está vinculado a uma ou mais coreografias.');
            }
        });
    }

    public function choreographies()
    {
        return $this->belongsToMany(Choreography::class, 'choreography_choreographer', 'choreographer_id', 'choreography_id');
    }
}

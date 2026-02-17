<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Dancer extends Model
{
    protected $fillable = ['name', 'birth_date', 'school_id'];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($dancer) {
            if ($dancer->choreographies()->exists()) {
                throw new \Exception('Não é possível excluir este dançarino, pois ele está vinculado a uma ou mais coreografias.');
            }
        });
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function choreographies()
    {
        return $this->belongsToMany(Choreography::class, 'choreography_dancers', 'dancer_id', 'choreography_id');
    }

    // public function choreographies()
    // {
    //     return $this->belongsToMany(Choreography::class, 'choreography_dancers');
    // }

    // protected function birthDate(): Attribute
    // {
    //     return Attribute::make(
    //         // Accessor - para exibir (dd/mm/yyyy)
    //         get: fn ($value) => $value ? \Carbon\Carbon::parse($value)->format('d/m/Y') : null,

    //         // Mutator - para salvar (yyyy-mm-dd)
    //         set: fn ($value) => $value ? \Carbon\Carbon::createFromFormat('d/m/Y', $value)->format('m/d/Y') : null,
    //     );
    // }

    /**
     * Accessor para birth_date - retorna no formato brasileiro para exibição
     */
    public function getBirthDateBrAttribute()
    {
        if (! $this->birth_date) {
            return null;
        }

        return $this->birth_date->format('d/m/Y');
    }

    /**
     * Accessor para exibir birth_date formatado por padrão
     */
    public function getBirthDateFormattedAttribute()
    {
        return $this->birth_date_br;
    }

    public function getAgeAttribute()
    {
        if (! $this->birth_date) {
            return null;
        }

        return Carbon::createFromFormat('d/m/Y', $this->birth_date)->age.' anos';
    }
}

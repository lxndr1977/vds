<?php

namespace App\Models;

use App\Enums\RegistrationStatusEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Registration extends Model
{
   use HasFactory;

   /**
    * The attributes that are mass assignable.
    *
    * @var array<int, string>
    */

   protected $fillable = [
      'school_id',
      'status_registration',
      'registration_data', // Adicione esta linha
   ];

   protected $casts = [
      'registration_data' => 'array',
      'status_registration' => RegistrationStatusEnum::class,
   ];


   /**
    * Get the school that owns the registration.
    */
   public function school(): BelongsTo
   {
      return $this->belongsTo(School::class);
   }

   public function choreographies()
   {
      return $this->hasManyThrough(
         Choreography::class,
         School::class,
         'id',           // chave local na tabela School
         'school_id',    // chave estrangeira na tabela Choreography
         'school_id',    // chave local na tabela Registration
         'id'            // chave local na tabela School
      );
   }

   public function members()
   {
      return $this->hasManyThrough(
         Member::class,
         School::class,
         'id',         // chave primária da school na tabela School
         'school_id',  // chave estrangeira na tabela Member
         'school_id',  // chave estrangeira na tabela Registration
         'id'          // chave primária da school
      );
   }

   public function choreographers()
   {
      return $this->hasManyThrough(
         Choreographer::class,
         School::class,
         'id',         // chave primária da school na tabela School
         'school_id',  // chave estrangeira na tabela Member
         'school_id',  // chave estrangeira na tabela Registration
         'id'          // chave primária da school
      );
   }

   public function dancers()
   {
      return $this->hasManyThrough(
         Dancer::class,
         School::class,
         'id',         // chave primária da school na tabela School
         'school_id',  // chave estrangeira na tabela Member
         'school_id',  // chave estrangeira na tabela Registration
         'id'          // chave primária da school
      );
   }

   public function getUpdatedAtBrazilianAttribute()
   {
      return $this->updated_at->format('d/m/Y H:i');
   }
}

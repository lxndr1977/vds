<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemConfiguration extends Model
{
    /** @use HasFactory<\Database\Factories\SystemConfigurationFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'festival_name',
        'registration_start_date',
        'registration_end_date',
        'logo_path',
        'primary_color',
        'secondary_color',
        'text_color',
        'allow_edit_after_submit',
        'notification_sender_email',
        'notification_cc_email',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'registration_start_date' => 'datetime',
            'registration_end_date' => 'datetime',
            'allow_edit_after_submit' => 'boolean',
        ];
    }

    /**
     * Determine if registrations are currently open.
     */
    protected function registrationsOpenToPublic(): Attribute
    {
        return Attribute::make(
            get: function (): bool {
                $now = now();

                if ($this->registration_start_date && $now->lt($this->registration_start_date)) {
                    return false;
                }

                if ($this->registration_end_date && $now->gt($this->registration_end_date)) {
                    return false;
                }

                return true;
            },
        );
    }
}

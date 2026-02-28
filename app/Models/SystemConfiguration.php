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
        'notification_whatsapp',
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

                $start = $this->registration_start_date ? $this->registration_start_date->startOfDay() : null;
                $end = $this->registration_end_date ? $this->registration_end_date->endOfDay() : null;

                if ($start && $now->lt($start)) {
                    return false;
                }

                if ($end && $now->gt($end)) {
                    return false;
                }

                return true;
            },
        );
    }

    /**
     * Return the currently stored configuration (first record).
     */
    public static function current(): ?self
    {
        return static::first();
    }

    /**
     * Accessor for full logo URL usable in views.
     */
    protected function logoUrl(): Attribute
    {
        return Attribute::make(
            get: fn (): string => $this->computeLogoUrl(),
        );
    }

    protected function computeLogoUrl(): string
    {
        $path = $this->logo_path;

        if (! $path) {
            return asset('logo-vds-2025-colorido.jpg');
        }

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return \Illuminate\Support\Facades\Storage::disk('public')->url($path);
    }
}

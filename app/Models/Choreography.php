<?php

namespace App\Models;

use App\Services\ChoreographyDancerService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Choreography extends Model
{
    protected $fillable = [
        'school_id',
        'choreography_type_id',
        'dance_style_id',
        'choreography_category_id',
        'is_social_project',
        'is_university_project',
        'name',
        'music',
        'music_composer',
        'duration',
    ];

    protected $casts = [
        'is_social_project' => 'boolean',
        'is_university_project' => 'boolean',
    ];

    public function school(): BelongsTo
    {
        return $this->belongsTo(School::class);
    }

    public function choreographyType(): BelongsTo
    {
        return $this->belongsTo(ChoreographyType::class);
    }

    public function dancers()
    {
        return $this->belongsToMany(Dancer::class, 'choreography_dancers');
    }

    public function choreographers()
    {
        return $this->belongsToMany(Choreographer::class, 'choreography_choreographer', 'choreography_id', 'choreographer_id');
    }

    public function choreographyCategory()
    {
        return $this->belongsTo(ChoreographyCategory::class);
    }

    public function danceStyle()
    {
        return $this->belongsTo(DanceStyle::class);
    }

    public function getRegistrationFee(): float
    {
        $currentFee = $this->choreographyType->getCurrentFee();

        if (! $currentFee) {
            return 0;
        }

        $participantCount = $this->dancers()->count();

        return $currentFee->amount * $participantCount;
    }

    public function getParticipantCount(): int
    {
        return $this->dancers()->count();
    }

    public function attachDancerWithValidation(int $dancerId): bool
    {
        $service = app(ChoreographyDancerService::class);

        // $validation = $service->canAttachDancer($this->id, $dancerId, $this->school_id);

        // if (!$validation['valid']) {
        //     throw new \Exception($validation['message']);
        // }

        $this->dancers()->attach($dancerId);

        return true;
    }

    public function syncDancersWithValidation(array $dancerIds): bool
    {
        $service = app(ChoreographyDancerService::class);

        // $validation = $service->canAssignDancers($this->id, $dancerIds, $this->school_id);

        // if (!$validation['valid']) {
        //     throw new \Exception($validation['message']);
        // }

        $this->dancers()->sync($dancerIds);

        return true;
    }

    /**
     * Verifica se a coreografia pode ser excluída
     */
    public function canBeDeleted(): array
    {
        $issues = [];

        if ($this->dancers()->exists()) {
            $issues[] = 'dançarino(s) associado(s)';
        }

        if ($this->choreographers()->exists()) {
            $issues[] = 'coreógrafo(s) associado(s)';
        }

        return [
            'can_delete' => empty($issues),
            'issues' => $issues,
            'message' => empty($issues)
                ? 'Coreografia pode ser excluída.'
                : 'Não pode ser excluída pois possui '.implode(' e ', $issues).'.',
        ];
    }
}

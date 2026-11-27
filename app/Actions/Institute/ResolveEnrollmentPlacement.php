<?php

namespace App\Actions\Institute;

use App\Models\Academy;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;

class ResolveEnrollmentPlacement
{
    public function __invoke(Academy $academy, array $data): array
    {
        $class = $academy->classes()->find($data['academy_class_id']);

        if ($class === null) {
            throw ValidationException::withMessages([
                'academy_class_id' => 'Select a class from your academy.',
            ]);
        }

        if (! $class->sections()->whereKey($data['section_id'])->exists()) {
            throw ValidationException::withMessages([
                'section_id' => 'Select a section from the chosen class.',
            ]);
        }

        $groupId = $data['subject_group_id'] ?? null;
        $group = $groupId === null ? null : $class->subjectGroups()->find($groupId);

        if ($groupId !== null && $group === null) {
            throw ValidationException::withMessages([
                'subject_group_id' => 'Select a subject group from the chosen class.',
            ]);
        }

        $requested = Arr::get($data, 'subject_ids', []);
        $subjectIds = $requested === []
            ? ($group?->subjects()->pluck('subjects.id')->all() ?? [])
            : $academy->subjects()->whereKey($requested)->pluck('subjects.id')->all();

        if ($requested !== [] && count($subjectIds) !== count($requested)) {
            throw ValidationException::withMessages([
                'subject_ids' => 'Select subjects from your academy.',
            ]);
        }

        return [
            'section_id' => (int) $data['section_id'],
            'subject_group_id' => $groupId === null ? null : (int) $groupId,
            'subject_ids' => $subjectIds,
        ];
    }
}

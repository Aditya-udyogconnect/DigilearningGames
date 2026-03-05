<?php

namespace DigiLearning\Engine;

class ScoringService
{
    /**
     * @param array<string, mixed> $validation
     */
    public function calculate_xp(array $validation): int
    {
        if (empty($validation['passed'])) {
            return 0;
        }

        $score = (int) ($validation['score'] ?? 0);
        $checklist = (int) ($validation['checklist'] ?? 0);

        $base = 100;
        $quiz_bonus = $score >= 90 ? 15 : 0;
        $checklist_bonus = $checklist >= 90 ? 10 : 0;

        return $base + $quiz_bonus + $checklist_bonus;
    }
}

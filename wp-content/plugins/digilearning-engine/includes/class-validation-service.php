<?php

namespace DigiLearning\Engine;

class ValidationService
{
    /**
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     */
    public function validate(array $payload): array
    {
        $score = isset($payload['score']) ? (int) $payload['score'] : 0;
        $checklist = isset($payload['checklist']) ? (int) $payload['checklist'] : 0;
        $module_id = isset($payload['module_id']) ? sanitize_text_field((string) $payload['module_id']) : '';

        $score_ok = $score >= 80;
        $checklist_ok = $checklist >= 70;
        $passed = $score_ok && $checklist_ok;

        return [
            'module_id' => $module_id,
            'passed' => $passed,
            'score' => $score,
            'checklist' => $checklist,
            'score_ok' => $score_ok,
            'checklist_ok' => $checklist_ok,
            'status' => $passed ? 'passed' : 'failed',
        ];
    }
}

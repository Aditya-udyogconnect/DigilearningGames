<?php

namespace DigiLearning\Engine;

class ProgressTracker
{
    /**
     * @return array<string, mixed>
     */
    public function get_summary(int $user_id): array
    {
        $xp = (int) get_user_meta($user_id, '_digilearning_xp', true);
        $completed_modules = get_user_meta($user_id, '_digilearning_completed_modules', true);

        if (!is_array($completed_modules)) {
            $completed_modules = [];
        }

        return [
            'xp' => $xp,
            'completed' => count($completed_modules),
            'completed_modules' => $completed_modules,
            'level' => (int) floor($xp / 200) + 1,
        ];
    }

    public function complete_module(int $user_id, string $module_id, int $xp_awarded): void
    {
        $summary = $this->get_summary($user_id);
        $completed_modules = $summary['completed_modules'];

        if (in_array($module_id, $completed_modules, true)) {
            return;
        }

        $completed_modules[] = $module_id;
        update_user_meta($user_id, '_digilearning_xp', (int) $summary['xp'] + $xp_awarded);
        update_user_meta($user_id, '_digilearning_completed_modules', $completed_modules);
    }

    /**
     * @param array<string, mixed> $attempt
     */
    public function log_attempt(int $user_id, array $attempt): void
    {
        $history = get_user_meta($user_id, '_digilearning_attempt_history', true);
        if (!is_array($history)) {
            $history = [];
        }

        $history[] = $attempt;
        update_user_meta($user_id, '_digilearning_attempt_history', $history);
    }
}

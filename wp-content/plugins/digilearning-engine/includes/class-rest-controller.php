<?php

namespace DigiLearning\Engine;

use WP_REST_Request;
use WP_REST_Server;

class RestController
{
    private ModuleManager $module_manager;
    private ProgressTracker $progress_tracker;
    private ValidationService $validation_service;
    private ScoringService $scoring_service;

    public function __construct(
        ModuleManager $module_manager,
        ProgressTracker $progress_tracker,
        ValidationService $validation_service,
        ScoringService $scoring_service
    ) {
        $this->module_manager = $module_manager;
        $this->progress_tracker = $progress_tracker;
        $this->validation_service = $validation_service;
        $this->scoring_service = $scoring_service;
    }

    public function register_routes(): void
    {
        register_rest_route('digilearning/v1', '/modules', [
            'methods' => WP_REST_Server::READABLE,
            'permission_callback' => fn() => is_user_logged_in(),
            'callback' => function () {
                $user_id = get_current_user_id();
                return [
                    'modules' => $this->module_manager->list_modules(),
                    'summary' => $this->progress_tracker->get_summary($user_id),
                ];
            },
        ]);

        register_rest_route('digilearning/v1', '/validate', [
            'methods' => WP_REST_Server::CREATABLE,
            'permission_callback' => fn() => is_user_logged_in(),
            'callback' => function (WP_REST_Request $request) {
                $user_id = get_current_user_id();
                $payload = $request->get_json_params();
                $validation = $this->validation_service->validate($payload);
                $module_id = (string) ($validation['module_id'] ?? '');
                $xp = $this->scoring_service->calculate_xp($validation);

                if ($module_id === '' || $this->module_manager->index_of($module_id) < 0) {
                    return new \WP_Error('invalid_module', 'Invalid module ID supplied.', ['status' => 400]);
                }

                $summary_before = $this->progress_tracker->get_summary($user_id);
                $completed_modules = $summary_before['completed_modules'];

                $already_completed = in_array($module_id, $completed_modules, true);
                if (!empty($validation['passed']) && !$already_completed) {
                    $this->progress_tracker->complete_module($user_id, $module_id, $xp);
                }

                $this->progress_tracker->log_attempt($user_id, [
                    'module_id' => $module_id,
                    'score' => (int) ($validation['score'] ?? 0),
                    'checklist' => (int) ($validation['checklist'] ?? 0),
                    'passed' => (bool) ($validation['passed'] ?? false),
                    'xp_awarded' => !$already_completed ? $xp : 0,
                    'timestamp' => gmdate('c'),
                ]);

                return [
                    'validation' => $validation,
                    'xp_awarded' => !$already_completed ? $xp : 0,
                    'already_completed' => $already_completed,
                    'summary' => $this->progress_tracker->get_summary($user_id),
                ];
            },
        ]);
    }
}

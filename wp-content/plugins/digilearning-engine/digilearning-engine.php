<?php
/**
 * Plugin Name: DigiLearning Engine
 * Description: Prototype game engine for module progression, validation, and scoring.
 * Version: 0.1.0
 * Author: DigiLearning Team
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once __DIR__ . '/includes/class-module-manager.php';
require_once __DIR__ . '/includes/class-progress-tracker.php';
require_once __DIR__ . '/includes/class-validation-service.php';
require_once __DIR__ . '/includes/class-scoring-service.php';
require_once __DIR__ . '/includes/class-rest-controller.php';

function digilearning_engine_bootstrap(): void
{
    $module_manager = new DigiLearning\Engine\ModuleManager();
    $progress_tracker = new DigiLearning\Engine\ProgressTracker();
    $validation_service = new DigiLearning\Engine\ValidationService();
    $scoring_service = new DigiLearning\Engine\ScoringService();

    $rest_controller = new DigiLearning\Engine\RestController(
        $module_manager,
        $progress_tracker,
        $validation_service,
        $scoring_service
    );

    add_action('rest_api_init', [$rest_controller, 'register_routes']);
}
add_action('plugins_loaded', 'digilearning_engine_bootstrap');

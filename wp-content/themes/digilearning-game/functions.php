<?php

if (!defined('ABSPATH')) {
    exit;
}

function digilearning_game_enqueue_assets(): void
{
    wp_enqueue_style(
        'digilearning-game-prototype',
        get_template_directory_uri() . '/assets/css/prototype.css',
        [],
        '0.2.0'
    );

    wp_enqueue_script(
        'digilearning-game-prototype',
        get_template_directory_uri() . '/assets/js/prototype.js',
        [],
        '0.2.0',
        true
    );

    wp_localize_script('digilearning-game-prototype', 'digilearningPrototype', [
        'xpPerModule' => 100,
        'passScore' => 80,
        'restUrl' => esc_url_raw(rest_url()),
        'nonce' => wp_create_nonce('wp_rest'),
    ]);
}
add_action('wp_enqueue_scripts', 'digilearning_game_enqueue_assets');

function digilearning_game_register_templates(array $templates): array
{
    $templates['page-dashboard.php'] = 'Learner Dashboard';
    $templates['page-mission.php'] = 'Mission Page';
    return $templates;
}
add_filter('theme_page_templates', 'digilearning_game_register_templates');

<?php
/**
 * Template Name: Learner Dashboard
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>
<main class="dg-shell">
    <section class="dg-card dg-hero">
        <h1>Digital Infrastructure Quest</h1>
        <p>Play through each business mission, pass validations, unlock the next stage, and graduate.</p>
        <div class="dg-stats">
            <div><span>Level</span><strong id="dg-level">1</strong></div>
            <div><span>XP</span><strong id="dg-xp">0</strong></div>
            <div><span>Completed</span><strong id="dg-completed">0 / 8</strong></div>
            <div><span>Status</span><strong id="dg-status">In Progress</strong></div>
        </div>
    </section>

    <section class="dg-card">
        <h2>Mission Map</h2>
        <div id="dg-module-map" class="dg-module-map" aria-live="polite"></div>
    </section>

    <section class="dg-card" id="dg-mission-panel" hidden>
        <h2 id="dg-mission-title"></h2>
        <p id="dg-mission-objective"></p>
        <ul id="dg-mission-checklist"></ul>

        <form id="dg-validation-form" class="dg-form">
            <label for="dg-score">Quiz score (0-100)</label>
            <input id="dg-score" name="score" type="number" min="0" max="100" required>

            <label for="dg-checklist">Checklist completion (0-100)</label>
            <input id="dg-checklist" name="checklist" type="number" min="0" max="100" required>

            <button class="dg-button" type="submit">Submit mission</button>
        </form>

        <p id="dg-validation-result" aria-live="polite"></p>
    </section>

    <section class="dg-card" id="dg-certificate" hidden>
        <h2>🎉 Graduation Unlocked</h2>
        <p>You completed all modules in the prototype path.</p>
        <p><strong id="dg-certificate-score"></strong></p>
    </section>
</main>
<?php
get_footer();

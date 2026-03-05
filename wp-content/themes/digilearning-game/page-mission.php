<?php
/**
 * Template Name: Mission Page
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();
?>
<main class="dg-shell">
    <section class="dg-card">
        <h1>Single Mission Validator</h1>
        <p>Use this page to test mission scoring rules quickly.</p>
        <form id="dg-validation-form" class="dg-form">
            <label for="dg-score">Quiz Score (0-100)</label>
            <input id="dg-score" name="score" type="number" min="0" max="100" required>

            <label for="dg-checklist">Checklist completion (0-100)</label>
            <input id="dg-checklist" name="checklist" type="number" min="0" max="100" required>

            <button class="dg-button" type="submit">Validate</button>
        </form>
        <p id="dg-validation-result" aria-live="polite"></p>
    </section>
</main>
<?php
get_footer();

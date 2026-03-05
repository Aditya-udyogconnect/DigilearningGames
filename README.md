# DigiLearning Games Prototype

A playable WordPress-oriented prototype for gamified digital infrastructure training.

## What now works end-to-end
- Mission map with 8 modules in sequence.
- Locked/available/passed state handling.
- Mission validation form (quiz + checklist thresholds).
- XP scoring and level progression.
- Graduation/certificate state after all modules are passed.
- Local persistence in browser for preview mode.
- REST-backed validation and user progress persistence when running in WordPress.

## Prototype structure
- Theme: `wp-content/themes/digilearning-game`
- Engine plugin: `wp-content/plugins/digilearning-engine`
- Docs: `docs/`
- Standalone preview page: `prototype-preview.html`

## WordPress setup
1. Copy theme and plugin into a WordPress installation.
2. Activate **DigiLearning Game Theme** and **DigiLearning Engine**.
3. Create a page with template **Learner Dashboard**.
4. Log in as a learner user and open the dashboard.
5. Complete modules by submitting mission validation values.

## Standalone preview
Run from repo root:
```bash
python3 -m http.server 4173
```
Then open:
`http://127.0.0.1:4173/prototype-preview.html`

The standalone version uses localStorage, while WordPress mode uses REST + user meta persistence.

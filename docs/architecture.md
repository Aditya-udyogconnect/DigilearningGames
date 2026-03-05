# Prototype Architecture

## Components
1. **Theme (`digilearning-game`)**
   - Learner dashboard UI.
   - Mission stepper and quick validation interactions.
   - Progress visualization (XP, level, completion).

2. **Plugin (`digilearning-engine`)**
   - Module state machine.
   - Progress tracker.
   - Validation service.
   - Scoring service.
   - REST API for learner actions.

3. **Audit trail model**
   - Records learner events in a custom table.
   - Emits a structured payload for GitHub sync (future worker/API adapter).

## State machine
- `locked`
- `available`
- `in_progress`
- `submitted`
- `passed`
- `failed`

## Validation modes
- Quiz score threshold.
- Checklist completion score.
- Evidence-required milestone.

## Security baseline
- Nonce verification for form actions.
- Capability checks for admin endpoints.
- Sanitization on all user-submitted text.

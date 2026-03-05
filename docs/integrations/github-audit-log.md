# GitHub Audit Log Plan

## Event schema
Each learner action should produce a structured event:
- `user_id`
- `module_id`
- `action`
- `status`
- `score`
- `checklist`
- `xp_awarded`
- `timestamp`

## Sync options
1. Commit JSON lines file to a private audit repository.
2. Open/update GitHub Issues per learner cohort.
3. Send to a GitHub Action webhook that stores data in artifacts.

## Security notes
- Store GitHub token in WordPress environment configuration.
- Never expose token in browser-side code.
- Use retries and dead-letter log for failed pushes.

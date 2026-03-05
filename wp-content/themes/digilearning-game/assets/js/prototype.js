(() => {
    const MODULES = [
        {
            id: 'google-email',
            title: 'Google Workspace Email Setup',
            objective: 'Configure business Gmail with domain records and signature.',
            checklist: ['Create mailbox', 'Set business signature', 'Validate SPF/DKIM']
        },
        {
            id: 'microsoft-email',
            title: 'Microsoft 365 Email Setup',
            objective: 'Configure Outlook mailbox with MFA and team alias.',
            checklist: ['Enable MFA', 'Set team alias', 'Configure calendar']
        },
        {
            id: 'wordpress-site',
            title: 'WordPress Website Starter',
            objective: 'Publish a basic website with homepage and contact flow.',
            checklist: ['Create homepage', 'Create services page', 'Configure contact form']
        },
        {
            id: 'google-business',
            title: 'Google Business Profile',
            objective: 'Publish a complete local business profile.',
            checklist: ['Add hours', 'Upload logo/photos', 'Add services and category']
        },
        {
            id: 'plugins-security',
            title: 'Plugin Installation & Security',
            objective: 'Install required plugins and basic hardening.',
            checklist: ['Install backup plugin', 'Install security plugin', 'Enable automatic updates']
        },
        {
            id: 'seo',
            title: 'SEO Fundamentals',
            objective: 'Apply on-page SEO structure and metadata.',
            checklist: ['Title and meta', 'Heading hierarchy', 'Internal links']
        },
        {
            id: 'crm',
            title: 'CRM Onboarding',
            objective: 'Connect lead form and pipeline workflow.',
            checklist: ['Import contacts', 'Create pipeline stages', 'Create follow-up automation']
        },
        {
            id: 'marketing',
            title: 'Digital Marketing Launch',
            objective: 'Launch first campaign with KPI goals.',
            checklist: ['Define audience', 'Prepare creative', 'Set KPI and budget']
        }
    ];

    const storageKey = 'digilearning-prototype-state-v2';
    const defaultState = {
        active: 0,
        xp: 0,
        completed: [],
        attempts: [],
        status: 'In Progress'
    };

    function readState() {
        try {
            return { ...defaultState, ...(JSON.parse(localStorage.getItem(storageKey)) || {}) };
        } catch (_err) {
            return { ...defaultState };
        }
    }

    function saveState(state) {
        localStorage.setItem(storageKey, JSON.stringify(state));
    }

    const state = readState();
    const levelEl = document.getElementById('dg-level');
    const xpEl = document.getElementById('dg-xp');
    const completedEl = document.getElementById('dg-completed');
    const statusEl = document.getElementById('dg-status');
    const moduleMapEl = document.getElementById('dg-module-map');

    const missionPanelEl = document.getElementById('dg-mission-panel');
    const missionTitleEl = document.getElementById('dg-mission-title');
    const missionObjectiveEl = document.getElementById('dg-mission-objective');
    const missionChecklistEl = document.getElementById('dg-mission-checklist');

    const formEl = document.getElementById('dg-validation-form');
    const resultEl = document.getElementById('dg-validation-result');

    const certificateEl = document.getElementById('dg-certificate');
    const certificateScoreEl = document.getElementById('dg-certificate-score');

    function moduleState(index) {
        if (state.completed.includes(MODULES[index].id)) return 'passed';
        if (index === state.active) return 'available';
        return 'locked';
    }

    function levelFromXp(xp) {
        return Math.floor(xp / 200) + 1;
    }

    function renderStats() {
        if (levelEl) levelEl.textContent = String(levelFromXp(state.xp));
        if (xpEl) xpEl.textContent = String(state.xp);
        if (completedEl) completedEl.textContent = `${state.completed.length} / ${MODULES.length}`;
        if (statusEl) statusEl.textContent = state.status;
    }

    function renderMap() {
        if (!moduleMapEl) return;

        moduleMapEl.innerHTML = MODULES.map((module, idx) => {
            const s = moduleState(idx);
            const disabled = s !== 'available' ? 'disabled' : '';
            return `
                <article class="dg-module dg-state-${s}">
                    <h3>${idx + 1}. ${module.title}</h3>
                    <p>${module.objective}</p>
                    <small>State: <strong>${s}</strong></small><br>
                    <button class="dg-button" data-module-index="${idx}" ${disabled}>Play Mission</button>
                </article>
            `;
        }).join('');

        moduleMapEl.querySelectorAll('button[data-module-index]').forEach((button) => {
            button.addEventListener('click', () => {
                const idx = Number(button.dataset.moduleIndex);
                if (idx === state.active) {
                    openMission(idx);
                }
            });
        });
    }

    function openMission(index) {
        const module = MODULES[index];
        if (!module || !missionPanelEl) return;

        missionPanelEl.hidden = false;
        if (missionTitleEl) missionTitleEl.textContent = module.title;
        if (missionObjectiveEl) missionObjectiveEl.textContent = module.objective;
        if (missionChecklistEl) {
            missionChecklistEl.innerHTML = module.checklist.map((item) => `<li>${item}</li>`).join('');
        }
        if (resultEl) {
            resultEl.className = '';
            resultEl.textContent = '';
        }
    }

    async function submitMission(payload) {
        if (window.digilearningPrototype?.restUrl && window.digilearningPrototype?.nonce) {
            const response = await fetch(`${window.digilearningPrototype.restUrl}digilearning/v1/validate`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-WP-Nonce': window.digilearningPrototype.nonce
                },
                body: JSON.stringify(payload)
            });

            if (!response.ok) {
                throw new Error('REST validation request failed');
            }

            return response.json();
        }

        const passed = payload.score >= 80 && payload.checklist >= 70;
        return {
            validation: {
                passed,
                status: passed ? 'passed' : 'failed',
                score: payload.score,
                checklist: payload.checklist
            },
            xp_awarded: passed ? (payload.score >= 90 ? 125 : 100) : 0
        };
    }

    function completeActiveMission(xpAwarded, score, checklist) {
        const current = MODULES[state.active];
        state.completed.push(current.id);
        state.xp += xpAwarded;
        state.attempts.push({ module: current.id, score, checklist, passed: true, xpAwarded, time: new Date().toISOString() });
        state.active += 1;

        if (state.active >= MODULES.length) {
            state.status = 'Graduated';
            if (certificateEl) certificateEl.hidden = false;
            if (certificateScoreEl) {
                certificateScoreEl.textContent = `Final XP: ${state.xp} | Completed modules: ${state.completed.length}`;
            }
            if (missionPanelEl) missionPanelEl.hidden = true;
        }
    }

    if (formEl) {
        formEl.addEventListener('submit', async (event) => {
            event.preventDefault();

            const score = Number(document.getElementById('dg-score')?.value || 0);
            const checklist = Number(document.getElementById('dg-checklist')?.value || 0);

            try {
                const response = await submitMission({
                    module_id: MODULES[state.active]?.id,
                    score,
                    checklist
                });

                const passed = Boolean(response?.validation?.passed);

                if (passed) {
                    const xpAwarded = Number(response?.xp_awarded || 100);
                    completeActiveMission(xpAwarded, score, checklist);
                    if (resultEl) {
                        resultEl.className = 'dg-result-pass';
                        resultEl.textContent = `Passed. XP +${xpAwarded}. Next mission unlocked.`;
                    }
                } else {
                    state.attempts.push({
                        module: MODULES[state.active]?.id,
                        score,
                        checklist,
                        passed: false,
                        xpAwarded: 0,
                        time: new Date().toISOString()
                    });
                    if (resultEl) {
                        resultEl.className = 'dg-result-fail';
                        resultEl.textContent = 'Validation failed. Need score >= 80 and checklist >= 70.';
                    }
                }
            } catch (error) {
                if (resultEl) {
                    resultEl.className = 'dg-result-fail';
                    resultEl.textContent = `Submission error: ${error.message}`;
                }
            }

            saveState(state);
            renderStats();
            renderMap();
        });
    }

    renderStats();
    renderMap();

    if (state.active < MODULES.length) {
        openMission(state.active);
    } else {
        if (certificateEl) certificateEl.hidden = false;
        if (certificateScoreEl) certificateScoreEl.textContent = `Final XP: ${state.xp} | Completed modules: ${state.completed.length}`;
    }
})();

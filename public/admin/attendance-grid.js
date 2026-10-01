/**
 * Classroom grid behaviour for the attendance marking page.
 *
 * - the only marks are Attended / Absent / Leave
 * - bulk set / clear every status toggle
 * - a live tally so the admin can see the split before saving
 * - blocks the save while any student in the roster is still unmarked
 * - falls back to classList for browsers without :has() support on the radios
 */
(function () {
    'use strict';

    function ready(fn) {
        if (document.readyState !== 'loading') {
            fn();
        } else {
            document.addEventListener('DOMContentLoaded', fn);
        }
    }

    ready(function () {
        const form = document.getElementById('attendanceForm');
        if (!form) return;

        const LABELS = { 1: 'Attended', 2: 'Absent', 4: 'Leave' };
        // shown in the live counter, in the order an admin reads them
        const ORDER = [1, 2, 4];

        const counter = document.getElementById('statusSummary');
        const warn = document.getElementById('saveWarning');
        const warnList = document.getElementById('saveWarningList');
        const saveBtn = document.getElementById('saveAttendanceBtn');
        const hint = document.getElementById('saveBarHint');

        const radios = function () {
            return Array.prototype.slice.call(form.querySelectorAll('[data-att-status]'));
        };

        /** name of every student that has no status chosen yet */
        function unmarkedNames() {
            const names = [];

            form.querySelectorAll('[data-student-card]').forEach(function (card) {
                const chosen = card.querySelector('[data-att-status]:checked');
                if (!chosen) {
                    const el = card.querySelector('[data-student-name]');
                    names.push(el ? el.getAttribute('data-student-name') : 'a student');
                }
            });

            return names;
        }

        function tally() {
            const counts = { 1: 0, 2: 0, 4: 0 };
            let marked = 0;

            radios().forEach(function (r) {
                if (r.checked && counts[r.value] !== undefined) {
                    counts[r.value]++;
                    marked++;
                }
            });

            const total = form.querySelectorAll('[data-student-card]').length;
            const unmarked = total - marked;

            // paint the card so an unmarked student is obvious at a glance
            form.querySelectorAll('[data-student-card]').forEach(function (card) {
                const picked = card.querySelector('[data-att-status]:checked');
                card.classList.remove(
                    'att-card-unmarked',
                    'att-card-marked-1',
                    'att-card-marked-2',
                    'att-card-marked-4'
                );

                if (picked) {
                    card.classList.add('att-card-marked-' + picked.value);
                } else {
                    card.classList.add('att-card-unmarked');
                }
            });

            if (counter) {
                counter.textContent = '';

                ORDER.forEach(function (v) {
                    const pill = document.createElement('span');
                    pill.className = 'att-counter-pill att-counter-pill-' + v;
                    pill.textContent = LABELS[v] + ': ' + counts[v];
                    counter.appendChild(pill);
                });

                if (unmarked > 0) {
                    const pill = document.createElement('span');
                    pill.className = 'att-counter-pill att-counter-pill-0';
                    pill.textContent = 'Unmarked: ' + unmarked;
                    counter.appendChild(pill);
                }
            }

            if (hint) {
                hint.textContent = unmarked > 0
                    ? marked + ' of ' + total + ' marked'
                    : total + ' student(s) ready to save';
            }

            // the save button stays enabled so the admin always gets the
            // explanation on click, rather than a dead control
            if (saveBtn) {
                saveBtn.classList.toggle('disabled', unmarked > 0);
                saveBtn.setAttribute('aria-disabled', unmarked > 0 ? 'true' : 'false');
            }

            return { unmarked: unmarked, total: total };
        }

        function hideWarning() {
            if (warn) warn.hidden = true;
        }

        form.addEventListener('change', function (e) {
            if (e.target.matches('[data-att-status]')) {
                tally();
                hideWarning();
            }
        });

        Array.prototype.forEach.call(form.querySelectorAll('[data-bulk]'), function (btn) {
            btn.addEventListener('click', function () {
                const want = btn.getAttribute('data-bulk');

                radios().forEach(function (r) {
                    r.checked = want !== '' && r.value === want;
                });

                tally();
                hideWarning();
            });
        });

        /**
         * Refuse to post a half-filled roster. The list of names is capped so
         * a 60-student class cannot push the sticky bar off screen.
         */
        form.addEventListener('submit', function (e) {
            const state = tally();

            if (state.unmarked > 0) {
                e.preventDefault();

                const names = unmarkedNames();
                if (warnList) {
                    warnList.textContent = names.slice(0, 8).join(', ')
                        + (names.length > 8 ? ' and ' + (names.length - 8) + ' more' : '');
                }

                if (warn) {
                    warn.hidden = false;
                    warn.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
                }

                if (window.YhaToast) {
                    window.YhaToast.show(
                        state.unmarked + ' student(s) still have no status selected.',
                        'error'
                    );
                }

                return;
            }

            // valid roster: a double click would post the same class twice
            Array.prototype.forEach.call(form.querySelectorAll('[data-loading]'), function (btn) {
                btn.classList.add('is-loading');
                btn.disabled = true;
            });
        });

        tally();
    });
})();

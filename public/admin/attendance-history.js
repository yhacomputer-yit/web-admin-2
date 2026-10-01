/**
 * Attendance History page behaviour.
 *
 * - collapsible "More filters" (auto-opened when a hidden filter is active)
 * - one shared edit modal instead of a dropdown + input + save button on
 *   every row, which made long tables unusable on a laptop screen
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

    function Modal(el) {
        // Materio's Bootstrap 5 bundle owns the global; the CDN Bootstrap 4
        // copy does not expose a usable constructor here.
        if (window.bootstrap && window.bootstrap.Modal) {
            return window.bootstrap.Modal.getOrCreateInstance(el);
        }

        return {
            // minimal fallback so the page still works without the bundle
            show: function () { el.classList.add('show'); el.style.display = 'block'; el.removeAttribute('aria-hidden'); document.body.classList.add('modal-open'); },
            hide: function () { el.classList.remove('show'); el.style.display = 'none'; el.setAttribute('aria-hidden', 'true'); document.body.classList.remove('modal-open'); }
        };
    }

    ready(function () {
        // ---- more filters --------------------------------------------------
        const toggle = document.getElementById('attMoreToggle');
        const more = document.getElementById('attMoreFilters');

        if (toggle && more) {
            const setOpen = function (open) {
                more.hidden = !open;
                toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
            };

            // reveal on load when a collapsed filter would otherwise be a
            // hidden source of a surprising result
            const active = more.querySelector('[data-active="1"]');
            setOpen(!!active);

            toggle.addEventListener('click', function () {
                setOpen(more.hidden);
            });
        }

        // ---- row edit modal -----------------------------------------------
        const modalEl = document.getElementById('attEditModal');
        if (!modalEl) return;

        const modal = Modal(modalEl);
        const fStudent = document.getElementById('attEditStudent');
        const fMeta = document.getElementById('attEditMeta');
        const fRemark = document.getElementById('attEditRemark');
        const form = document.getElementById('attEditForm');
        // the status is a radio group, not a select, so it is pre-ticked by
        // matching the value rather than assigned to
        const statusRadios = function () {
            return form ? Array.prototype.slice.call(form.querySelectorAll('input[name="status"]')) : [];
        };

        modalEl.addEventListener('show.bs.modal', function (event) {
            const trigger = event.relatedTarget;
            if (!trigger) return;

            if (fStudent) {
                fStudent.textContent = trigger.getAttribute('data-student') || 'Student';
            }

            if (fMeta) {
                fMeta.textContent = trigger.getAttribute('data-meta') || '';
            }

            const current = trigger.getAttribute('data-status') || '';
            statusRadios().forEach(function (r) {
                r.checked = r.value === current;
            });

            if (fRemark) {
                fRemark.value = trigger.getAttribute('data-remark') || '';
            }

            if (form) {
                form.setAttribute('action', trigger.getAttribute('data-action') || '');
            }
        });

        // keep the submit button from double-posting the same correction
        if (form) {
            form.addEventListener('submit', function () {
                const btn = form.querySelector('[data-loading]');
                if (btn) {
                    btn.classList.add('is-loading');
                    btn.disabled = true;
                }
            });
        }
    });
})();

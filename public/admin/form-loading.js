/**
 * Button-level loading states for admin forms.
 *
 * Any element carrying data-loading="<label>" shows a spinner and disables
 * itself on submit, so a form post cannot be double-fired. Works for GET
 * filter forms and POST action forms alike.
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
        document.addEventListener('submit', function (e) {
            var btn = e.submitter || (e.target.querySelector('[data-loading]'));
            if (btn && btn.hasAttribute && btn.hasAttribute('data-loading')) {
                btn.classList.add('is-loading');
                btn.disabled = true;
            }
        });

        // a form whose only submit control is outside it (e.g. an anchor that
        // triggers validation) still needs the guard on plain clicks
        document.addEventListener('click', function (e) {
            var btn = e.target.closest ? e.target.closest('[data-loading]') : null;
            if (btn && btn.type === 'submit' && !btn.form) {
                btn.classList.add('is-loading');
                btn.disabled = true;
            }
        });
    });
})();

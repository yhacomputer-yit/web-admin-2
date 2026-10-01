/**
 * Toasts for the admin pages.
 *
 * Deliberately self-contained rather than built on bootstrap.Toast: the admin
 * layout loads Bootstrap 4.3.1 (CDN) and Materio's Bootstrap 5 bundle at the
 * same time, so which of the two owns the global is not something to depend
 * on for a purely visual notification.
 *
 * Usage:
 *   YhaToast.show('Saved 12 records', 'success');
 *   YhaToast.fromFlashes();   // render session('success'|'error') as toasts
 */
window.YhaToast = (function () {
    'use strict';

    var ICONS = {
        success: 'bx bx-check-circle',
        error: 'bx bx-error-circle',
        info: 'bx bx-info-circle'
    };

    function host() {
        var el = document.querySelector('.yha-toast-host');

        if (!el) {
            el = document.createElement('div');
            el.className = 'yha-toast-host';
            el.setAttribute('role', 'status');
            el.setAttribute('aria-live', 'polite');
            document.body.appendChild(el);
        }

        return el;
    }

    function dismiss(toast) {
        toast.classList.remove('is-in');
        // let the slide-out finish before removing from the DOM
        window.setTimeout(function () {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 220);
    }

    function show(message, type, timeout) {
        if (!message) return null;

        var tone = ICONS[type] ? type : 'info';

        var toast = document.createElement('div');
        toast.className = 'yha-toast yha-toast-' + tone;

        var ico = document.createElement('i');
        ico.className = 'yha-toast-ico ' + ICONS[tone];

        var body = document.createElement('div');
        body.className = 'yha-toast-body';
        // textContent, never innerHTML: the flash text is user data
        body.textContent = message;

        var close = document.createElement('button');
        close.type = 'button';
        close.className = 'yha-toast-close';
        close.setAttribute('aria-label', 'Dismiss');
        close.innerHTML = '&times;';
        close.addEventListener('click', function () { dismiss(toast); });

        toast.appendChild(ico);
        toast.appendChild(body);
        toast.appendChild(close);

        host().appendChild(toast);

        // next frame, so the transition actually runs
        window.requestAnimationFrame(function () {
            toast.classList.add('is-in');
        });

        var life = timeout === 0 ? 0 : (timeout || (tone === 'error' ? 7000 : 4000));
        if (life > 0) {
            window.setTimeout(function () { dismiss(toast); }, life);
        }

        return toast;
    }

    /**
     * Turn a Laravel flash into a toast and drop the marker node, so a refresh
     * does not replay it.
     */
    function fromFlashes() {
        Array.prototype.forEach.call(document.querySelectorAll('[data-flash-toast]'), function (el) {
            show(el.getAttribute('data-flash-toast'), el.getAttribute('data-flash-type') || 'success');
            el.remove();
        });
    }

    function ready(fn) {
        if (document.readyState !== 'loading') {
            fn();
        } else {
            document.addEventListener('DOMContentLoaded', fn);
        }
    }

    ready(fromFlashes);

    return { show: show, dismiss: dismiss, fromFlashes: fromFlashes };
})();

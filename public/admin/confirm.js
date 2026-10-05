/**
 * The delete confirmation dialog for admin pages.
 *
 * Replaces window.confirm() on delete links and on the other "are you sure"
 * prompts. The browser's own box cannot be styled, cannot carry the record's name
 * in anything but a flat string, and offers buttons the admin has to read twice to
 * tell apart; this one names what is about to be removed and keeps Delete and
 * Cancel apart by colour and position, not by label alone.
 *
 * A real modal, unlike the toast beside it: a delete is the one action here that
 * takes something away, so the page behind is locked out until it is answered and
 * focus cannot wander off into a form nobody is looking at any more.
 *
 * Usage, without writing any script:
 *
 *   <a href="..." data-confirm="Delete the paper for Algorithms?">Delete</a>
 *   <button data-confirm="Remove this enrollment?">Remove</button>
 *
 * Optional: data-confirm-title, data-confirm-label, data-confirm-tone
 * ("danger" for a delete, "info" for anything that is not destroying anything).
 *
 * And by hand, for anything that needs the answer rather than a held click:
 *
 *   YhaConfirm.ask({ message: '...', tone: 'info' }).then(function (ok) { ... });
 *
 * Self-contained like toast.js: this file loads no library and asks nothing of the
 * page it is put in.
 */
window.YhaConfirm = (function () {
    'use strict';

    var ICONS = {
        danger: 'bx bx-error-circle',
        info: 'bx bx-help-circle'
    };

    /** the dialog currently on screen, so a second click cannot stack another */
    var open = null;

    /** whatever had focus before the dialog opened, to give it back on close */
    var restoreTo = null;

    var FOCUSABLE = 'a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])';

    /**
     * Close any dropdown the question was asked from.
     *
     * Most of these prompts hang off a dropdown menu, and the click that opens the
     * dialog is swallowed - which means the menu Bootstrap opened never gets the
     * click it was waiting for to close itself. Left open, it sits behind the
     * backdrop looking like part of the dialog.
     */
    function closeDropdowns() {
        Array.prototype.forEach.call(
            document.querySelectorAll('.dropdown-menu.show'),
            function (menu) {
                menu.classList.remove('show');

                var toggle = menu.parentNode && menu.parentNode.querySelector('[data-bs-toggle="dropdown"]');

                if (toggle) {
                    toggle.setAttribute('aria-expanded', 'false');
                    toggle.classList.remove('show');
                }
            }
        );
    }

    /**
     * Ask, and resolve to whether the answer was yes.
     *
     * @param {{message: string, title?: string, confirmLabel?: string,
     *          cancelLabel?: string, tone?: string}} options
     * @returns {Promise<boolean>}
     */
    function ask(options) {
        options = options || {};

        return new Promise(function (resolve) {
            // one question at a time: a second click while this is up does
            // nothing, rather than opening a dialog whose first click's promise
            // would never resolve
            if (open) {
                return;
            }

            var tone = ICONS[options.tone] ? options.tone : 'danger';

            var overlay = document.createElement('div');
            overlay.className = 'yha-modal';
            overlay.setAttribute('role', 'dialog');
            overlay.setAttribute('aria-modal', 'true');

            var backdrop = document.createElement('div');
            backdrop.className = 'yha-modal-backdrop';

            var dialog = document.createElement('div');
            dialog.className = 'yha-modal-dialog' + (tone === 'info' ? ' is-info' : '');

            var titleId = 'yha-confirm-title';

            var head = document.createElement('div');
            head.className = 'yha-modal-head';

            var ico = document.createElement('i');
            ico.className = 'yha-modal-ico ' + ICONS[tone];
            ico.setAttribute('aria-hidden', 'true');

            var title = document.createElement('h2');
            title.className = 'yha-modal-title';
            title.id = titleId;
            // textContent, never innerHTML: the message is built from record data
            title.textContent = options.title || (tone === 'info' ? 'Please confirm' : 'Delete this?');

            head.appendChild(ico);
            head.appendChild(title);

            overlay.setAttribute('aria-labelledby', titleId);

            var message = document.createElement('p');
            message.className = 'yha-modal-message';
            message.textContent = options.message || '';

            var actions = document.createElement('div');
            actions.className = 'yha-modal-actions';

            var cancel = document.createElement('button');
            cancel.type = 'button';
            cancel.className = 'yha-modal-btn yha-modal-btn-cancel';
            cancel.textContent = options.cancelLabel || 'Cancel';

            var ok = document.createElement('button');
            ok.type = 'button';
            ok.className = 'yha-modal-btn yha-modal-btn-ok';
            ok.textContent = options.confirmLabel || (tone === 'info' ? 'Confirm' : 'Delete');

            actions.appendChild(cancel);
            actions.appendChild(ok);

            dialog.appendChild(head);

            if (options.message) {
                dialog.appendChild(message);
            }

            dialog.appendChild(actions);

            overlay.appendChild(backdrop);
            overlay.appendChild(dialog);

            document.body.appendChild(overlay);
            document.body.classList.add('yha-modal-open');

            var settled = false;

            function settle(answer) {
                // one answer only: Escape after clicking Delete must not be able
                // to resolve the same promise a second time
                if (settled) {
                    return;
                }
                settled = true;
                open = null;

                document.removeEventListener('keydown', onKey, true);
                overlay.classList.remove('is-in');
                document.body.classList.remove('yha-modal-open');

                window.setTimeout(function () {
                    if (overlay.parentNode) {
                        overlay.parentNode.removeChild(overlay);
                    }
                }, 160);

                // the dialog took the focus, so give it back to where it came from
                if (restoreTo && document.contains(restoreTo)) {
                    restoreTo.focus();
                }

                resolve(answer);
            }

            function focusables() {
                return Array.prototype.filter.call(
                    dialog.querySelectorAll(FOCUSABLE),
                    function (el) { return el.offsetParent !== null; }
                );
            }

            function onKey(event) {
                if (event.key === 'Escape') {
                    event.preventDefault();
                    settle(false);

                    return;
                }

                if (event.key !== 'Tab') {
                    return;
                }

                // keep Tab inside: a modal the keyboard can tab out of is not
                // modal, it is a box over a page that is still being used
                var items = focusables();

                if (! items.length) {
                    return;
                }

                var first = items[0];
                var last = items[items.length - 1];

                if (event.shiftKey && document.activeElement === first) {
                    event.preventDefault();
                    last.focus();
                } else if (! event.shiftKey && document.activeElement === last) {
                    event.preventDefault();
                    first.focus();
                }
            }

            cancel.addEventListener('click', function () { settle(false); });
            ok.addEventListener('click', function () { settle(true); });

            // clicking the backdrop answers "no": the click that asked for the
            // dialog was swallowed, so nothing behind it can be triggered by it
            backdrop.addEventListener('click', function () { settle(false); });

            // clicking the dialog itself must not count as clicking away
            dialog.addEventListener('click', function (event) {
                event.stopPropagation();
            });

            document.addEventListener('keydown', onKey, true);

            restoreTo = document.activeElement;
            open = overlay;

            closeDropdowns();

            window.requestAnimationFrame(function () {
                overlay.classList.add('is-in');
                // Delete gets the focus so Enter is the answer that is fastest -
                // but it is never the default: Escape and the backdrop both mean
                // no, and neither should need a second thought
                cancel.focus();
            });
        });
    }

    /**
     * Do what the element would have done, now that the answer is yes.
     *
     * requestSubmit rather than submit so the browser's own required-field check
     * still runs; the click that got us here was swallowed, so nothing has been
     * submitted yet.
     */
    function follow(el) {
        if (el.tagName === 'A') {
            if (el.href) {
                window.location.href = el.href;
            }

            return;
        }

        var form = el.form || el.closest('form');

        if (form) {
            if (typeof form.requestSubmit === 'function') {
                form.requestSubmit(el.type === 'submit' ? el : undefined);
            } else {
                form.submit();
            }
        }
    }

    function isSubmitControl(el) {
        if (el.tagName !== 'BUTTON' && el.tagName !== 'INPUT') {
            return false;
        }

        return (el.type || '').toLowerCase() === 'submit';
    }

    /**
     * A form can carry the prompt itself, so a plain form with no special button
     * still asks.
     *
     * form.submit() rather than requestSubmit, because the submit event only
     * fires once the browser's own required-field check has already passed, and
     * going through requestSubmit would fire the event again and ask twice.
     */
    function onSubmit(event) {
        var form = event.target;
        var submitter = event.submitter;
        var message = form.getAttribute('data-confirm')
            || (submitter && submitter.getAttribute('data-confirm'));

        if (!message) {
            return;
        }

        event.preventDefault();

        ask({
            message: message,
            title: form.getAttribute('data-confirm-title'),
            confirmLabel: form.getAttribute('data-confirm-label'),
            tone: form.getAttribute('data-confirm-tone') || 'danger'
        }).then(function (confirmed) {
            if (confirmed) {
                form.submit();
            }
        });
    }

    function onClick(event) {
        var el = event.target.closest('[data-confirm]');

        if (!el) {
            return;
        }

        // a submit control is left to the form, which knows whether the answer
        // still has to be asked for at the point the form is actually sent
        if (isSubmitControl(el) && (el.form || el.closest('form'))) {
            return;
        }

        // the click is held, never forwarded: nothing happens until yes
        event.preventDefault();

        ask({
            message: el.getAttribute('data-confirm'),
            title: el.getAttribute('data-confirm-title'),
            confirmLabel: el.getAttribute('data-confirm-label'),
            tone: el.getAttribute('data-confirm-tone') || 'danger'
        }).then(function (confirmed) {
            if (confirmed) {
                follow(el);
            }
        });
    }

    return { ask: ask };
})();
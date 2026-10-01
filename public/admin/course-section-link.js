/**
 * Dependent dropdown loader.
 *
 * Sections and subjects are both driven by pivot tables (course_sections,
 * subject_detail), so both selects reload when the course changes. The server
 * is the single source of truth: when a course has nothing linked it returns an
 * explicit message, which is shown instead of an empty dropdown.
 *
 * A select opts in with data-dependent plus one or more:
 *   data-sections-url="/admin/attendance/{id}/sections"
 *   data-subjects-url="/admin/attendance/subjects/course/{id}"
 * Each url contains the literal __ID__, replaced with the course id.
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
        const course = document.getElementById('course_id');
        if (!course) return;

        // id -> { select, url, hint, placeholder }
        const targets = [];

        const section = document.getElementById('section_id');
        if (section && course.dataset.sectionsUrl) {
            targets.push({
                select: section,
                url: course.dataset.sectionsUrl,
                hint: document.getElementById('sectionHint'),
                placeholder: 'Select Course first',
            });
        }

        const subject = document.getElementById('subject_id');
        if (subject && course.dataset.subjectsUrl) {
            targets.push({
                select: subject,
                url: course.dataset.subjectsUrl,
                hint: document.getElementById('subjectHint'),
                placeholder: 'Select Course first',
            });
        }

        if (!targets.length) return;

        function showHint(hint, text) {
            if (!hint) return;
            hint.textContent = text || '';
            hint.classList.toggle('d-none', !text);
        }

        function render(target, list, message, keepValue) {
            const sel = target.select;
            sel.innerHTML = '';

            const blank = document.createElement('option');
            blank.value = '';
            blank.textContent = list.length ? sel.dataset.blankLabel || 'Select' : (message || 'None available');
            sel.appendChild(blank);

            list.forEach(function (item) {
                const opt = document.createElement('option');
                opt.value = item.id;
                opt.textContent = item.name;
                if (String(item.id) === String(keepValue)) opt.selected = true;
                sel.appendChild(opt);
            });

            sel.disabled = list.length === 0;
            showHint(target.hint, list.length ? '' : (message || ''));
        }

        function loadOne(target, courseId) {
            const sel = target.select;
            const previous = sel.value;
            sel.disabled = true;
            showHint(target.hint, 'Loading...');

            const url = target.url.replace(/__ID__|__COURSE__/g, encodeURIComponent(courseId));

            return fetch(url, { headers: { Accept: 'application/json' }, credentials: 'same-origin' })
                .then(function (r) {
                    if (!r.ok) throw new Error('request failed');
                    return r.json();
                })
                .then(function (data) {
                    render(target, data.sections || data.subjects || [], data.message, previous);
                })
                .catch(function () {
                    sel.innerHTML = '<option value="">Could not load</option>';
                    sel.disabled = true;
                    showHint(target.hint, 'Could not load. Please try again.');
                });
        }

        course.addEventListener('change', function () {
            const courseId = course.value;

            if (!courseId) {
                targets.forEach(function (t) {
                    t.select.innerHTML = '<option value="">' + t.placeholder + '</option>';
                    t.select.disabled = false;
                    showHint(t.hint, '');
                });
                return;
            }

            Promise.all(targets.map(function (t) { return loadOne(t, courseId); }));
        });
    });
})();

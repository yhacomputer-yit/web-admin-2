{{--
    Searchable student picker: searches name / username / phone / email / NRC / id
    and shows a preview card so the admin can confirm before selecting.

    Expects: $selectedStudent (nullable Student)
--}}
@php($selectedStudent = $selectedStudent ?? null)
@php($initialStudent = $selectedStudent ? ['id' => $selectedStudent->id, 'name' => $selectedStudent->name, 'username' => $selectedStudent->username, 'phone' => $selectedStudent->phone, 'email' => $selectedStudent->email, 'status' => $selectedStudent->status, 'image' => $selectedStudent->image ? \Illuminate\Support\Facades\Storage::url($selectedStudent->image) : null] : null)

<div class="col-12">
    <label for="studentSearch" class="form-label h6 my-2">
        Student <span class="text-danger">*</span>
    </label>

    <div class="position-relative" id="studentPicker" data-selected-id="{{ $selectedStudent?->id }}">
        <input type="search" id="studentSearch" class="form-control" autocomplete="off"
            placeholder="Search by name, username, ID or phone..." aria-label="Search students"
            role="combobox" aria-expanded="false" aria-controls="studentResults" aria-autocomplete="list">
        <input type="hidden" name="student_id" id="student_id"
            value="{{ old('student_id', $selectedStudent?->id) }}">

        <div id="studentResults" class="list-group position-absolute w-100 shadow d-none"
            style="z-index: 1050; max-height: 320px; overflow-y: auto;" role="listbox"></div>

        <div id="studentEmpty" class="small text-muted mt-1 d-none">No student matches that search.</div>
    </div>

    @error('student_id')
        <div class="text-danger small mt-1">{{ $message }}</div>
    @enderror

    {{-- Selected student preview  --}}
    <div id="studentSelected" class="d-none mt-2">
        <div class="d-flex align-items-center gap-3 border rounded p-2 bg-light">
            <img id="spImage" src="/image/no-image.jpg" width="46" height="46"
                class="rounded-circle object-fit-cover border" alt="">
            <div class="flex-grow-1">
                <div class="fw-semibold" id="spName"></div>
                <div class="small text-muted">
                    <code id="spUsername"></code>
                    <span id="spPhone"></span>
                    <span id="spEmail"></span>
                </div>
            </div>
            <span class="badge" id="spStatus"></span>
            <button type="button" class="btn btn-sm btn-outline-danger" id="spClear"
                title="Clear selection">
                <i class="bx bx-x"></i>
            </button>
        </div>
    </div>
</div>

<script>
(function () {
    const root = document.getElementById('studentPicker');
    if (!root) return;

    const search = document.getElementById('studentSearch');
    const hidden = document.getElementById('student_id');
    const results = document.getElementById('studentResults');
    const empty = document.getElementById('studentEmpty');
    const selected = document.getElementById('studentSelected');
    const spImage = document.getElementById('spImage');
    const spName = document.getElementById('spName');
    const spUsername = document.getElementById('spUsername');
    const spPhone = document.getElementById('spPhone');
    const spEmail = document.getElementById('spEmail');
    const spStatus = document.getElementById('spStatus');

    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        || document.querySelector('input[name="_token"]')?.value
        || '';
    const endpoint = '{{ route('enrollment.searchStudents') }}';

    let timer = null;
    let rows = [];
    let active = -1;

    function esc(v) {
        const d = document.createElement('div');
        d.textContent = v == null ? '' : v;
        return d.innerHTML;
    }

    function showSelected(s) {
        spImage.src = s.image || '/image/no-image.jpg';
        spName.textContent = s.name;
        spUsername.textContent = s.username ? '@' + s.username : '';
        spPhone.textContent = s.phone ? ' · ' + s.phone : '';
        spEmail.textContent = s.email ? ' · ' + s.email : '';
        spStatus.textContent = s.status ? s.status.charAt(0).toUpperCase() + s.status.slice(1) : '';
        spStatus.className = 'badge ' + (s.status === 'active' ? 'text-bg-success' : 'text-bg-secondary');
        selected.classList.remove('d-none');
    }

    function clearSelection() {
        hidden.value = '';
        selected.classList.add('d-none');
        search.value = '';
        search.focus();
    }

    document.getElementById('spClear').addEventListener('click', clearSelection);

    function closeList() {
        results.classList.add('d-none');
        empty.classList.add('d-none');
        results.innerHTML = '';
        rows = [];
        active = -1;
        search.setAttribute('aria-expanded', 'false');
    }

    function highlight(i) {
        Array.from(results.children).forEach((el, idx) => {
            el.classList.toggle('active', idx === i);
        });
        active = i;
    }

    function choose(s) {
        hidden.value = s.id;
        showSelected(s);
        search.value = s.name;
        closeList();
    }

    async function run(term) {
        try {
            const res = await fetch(endpoint, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ q: term }),
            });
            if (!res.ok) throw new Error('search failed');

            const data = await res.json();
            results.innerHTML = '';
            rows = data;

            if (!data.length) {
                empty.classList.remove('d-none');
                results.classList.add('d-none');
                search.setAttribute('aria-expanded', 'false');
                return;
            }

            empty.classList.add('d-none');
            results.classList.remove('d-none');
            search.setAttribute('aria-expanded', 'true');

            data.forEach((s) => {
                const a = document.createElement('a');
                a.href = '#';
                a.className = 'list-group-item list-group-item-action d-flex align-items-center gap-2';
                a.setAttribute('role', 'option');
                a.innerHTML =
                    '<img src="' + (s.image || '/image/no-image.jpg') + '" width="32" height="32"' +
                    ' class="rounded-circle object-fit-cover border" alt="">' +
                    '<span class="flex-grow-1">' +
                    '<span class="fw-semibold d-block">' + esc(s.name) + '</span>' +
                    '<span class="small text-muted">' +
                    (s.username ? '@' + esc(s.username) : '') +
                    (s.phone ? ' · ' + esc(s.phone) : '') +
                    (s.email ? ' · ' + esc(s.email) : '') +
                    '</span></span>' +
                    '<span class="badge ' + (s.status === 'active' ? 'text-bg-success' : 'text-bg-secondary') + '">' +
                    esc(s.status) + '</span>';
                a.addEventListener('click', (e) => { e.preventDefault(); choose(s); });
                results.appendChild(a);
            });
        } catch (e) {
            closeList();
        }
    }

    search.addEventListener('input', function () {
        const term = search.value.trim();
        clearTimeout(timer);
        if (term === '') { closeList(); return; }
        timer = setTimeout(() => run(term), 200);
    });

    search.addEventListener('keydown', function (e) {
        if (results.classList.contains('d-none') || !rows.length) return;

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            highlight((active + 1) % rows.length);
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            highlight(active <= 0 ? rows.length - 1 : active - 1);
        } else if (e.key === 'Enter' && active >= 0) {
            e.preventDefault();
            choose(rows[active]);
        } else if (e.key === 'Escape') {
            closeList();
        }
    });

    document.addEventListener('click', (e) => {
        if (!root.contains(e.target)) closeList();
    });

    // restore the previous selection (rendered server-side, no fetch needed)
    const initial = @json($initialStudent);

    if (initial) {
        showSelected(initial);
        search.value = initial.name;
    }
})();
</script>

{{-- Credential generation for the student create/edit forms  --}}
<script>
(function () {
    const generateBtn = document.getElementById('generateBtn');
    const nameInput = document.getElementById('name');
    const usernameInput = document.getElementById('username');
    const passwordInput = document.getElementById('password');

    if (!generateBtn) return;

    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
        || document.querySelector('input[name="_token"]')?.value
        || '';

    generateBtn.addEventListener('click', async function () {
        const name = nameInput ? nameInput.value : '';

        generateBtn.disabled = true;
        try {
            const response = await fetch('{{ route('student.generateCredentials') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ name: name }),
            });

            if (!response.ok) throw new Error('generate failed');
            const data = await response.json();

            if (usernameInput) usernameInput.value = data.username;
            if (passwordInput) passwordInput.value = data.password;
        } catch (e) {
            alert('Could not generate credentials. Please try again.');
        } finally {
            generateBtn.disabled = false;
        }
    });
})();
</script>

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('grades-form');

    document.querySelectorAll('.grade-entry').forEach(entry => {
        const editCheckbox = entry.querySelector('.edit-toggle');
        const deleteCheckbox = entry.querySelector('.delete-toggle');
        const input = entry.querySelector('.grade-input');
        const content = entry.querySelector('.grade-content');

        input.dataset.original = input.value;

        editCheckbox.addEventListener('change', function () {
            if (editCheckbox.checked) {
                input.removeAttribute('readonly');
                input.classList.add('editable');
                entry.style.backgroundColor = '#e0f0ff'; // Light blue
            } else {
                input.setAttribute('readonly', true);
                input.value = input.dataset.original;
                input.classList.remove('editable');
                // Only reset color if not marked for delete
                if (!deleteCheckbox.checked) {
                    entry.style.backgroundColor = '';
                }
            }
        });

        deleteCheckbox.addEventListener('change', function () {
            if (deleteCheckbox.checked) {
                // Disable editing
                editCheckbox.checked = false;
                editCheckbox.disabled = true;
                input.setAttribute('readonly', true);
                input.classList.remove('editable');

                // Delete style
                entry.style.backgroundColor = '#fdd'; // Light red
                content.querySelectorAll('p').forEach(p => p.style.textDecoration = 'line-through');
            } else {
                editCheckbox.disabled = false;
                content.querySelectorAll('p').forEach(p => p.style.textDecoration = '');

                // Reapply edit color if still editing
                if (editCheckbox.checked) {
                    entry.style.backgroundColor = '#e0f0ff'; // blue
                } else {
                    entry.style.backgroundColor = '';
                }
            }
        });
    });

    form.addEventListener('submit', function (e) {
        const edited = document.querySelectorAll('.edit-toggle:checked').length;
        const deleted = document.querySelectorAll('.delete-toggle:checked').length;

        if (edited || deleted) {
            const confirmMessage = `You are about to:\n${edited ? '- Edit some grades\n' : ''}${deleted ? '- Delete some grades\n' : ''}\nAre you sure you want to continue?`;
            if (!confirm(confirmMessage)) {
                e.preventDefault();
            }
        }
    });
});
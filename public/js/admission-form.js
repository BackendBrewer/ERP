'use strict';

document.querySelectorAll('[data-admission-form]').forEach((form) => {
    const classSelect = form.querySelector('[data-admission-class]');
    const group = form.querySelector('[data-admission-group]');
    const preview = form.querySelector('[data-group-preview]');
    const dependentSelects = form.querySelectorAll('[data-admission-dependent]');

    function updatePreview() {
        if (!preview || !group) return;

        const description = group.selectedOptions[0]?.dataset.groupSubjects;
        preview.hidden = !description;
        preview.textContent = description ? 'Group subjects: ' + description : '';
    }

    function updateOptions() {
        dependentSelects.forEach((select) => {
            Array.from(select.options).forEach((option) => {
                if (!option.dataset.classId) return;

                const matchesClass = option.dataset.classId === classSelect.value;
                option.hidden = !matchesClass;
                option.disabled = !matchesClass;

                if (!matchesClass && option.selected) {
                    select.value = '';
                }
            });
        });
        updatePreview();
    }

    group?.addEventListener('change', updatePreview);
    classSelect.addEventListener('change', updateOptions);
    updateOptions();
});

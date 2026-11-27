'use strict';

document.querySelectorAll('[data-admission-form]').forEach((form) => {
    const classSelect = form.querySelector('[data-admission-class]');
    const dependentSelects = form.querySelectorAll('[data-admission-dependent]');

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
    }

    classSelect.addEventListener('change', updateOptions);
    updateOptions();
});
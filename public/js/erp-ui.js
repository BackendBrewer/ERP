'use strict';

document.querySelectorAll('[data-password-toggle]').forEach((button) => {
    const input = document.getElementById(button.dataset.passwordToggle);

    if (!(input instanceof HTMLInputElement)) {
        return;
    }

    button.hidden = false;
    button.parentElement.classList.add('has-toggle');

    button.addEventListener('click', () => {
        const showing = input.type === 'password';
        input.type = showing ? 'text' : 'password';
        button.textContent = showing ? 'Hide password' : 'Show password';
    });
});

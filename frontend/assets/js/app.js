import 'bootstrap';
import '@fortawesome/fontawesome-free/css/all.min.css';

document.addEventListener('DOMContentLoaded', () => {
    const forms = document.querySelectorAll('[data-validate-form]');

    forms.forEach((form) => {
        const passwordField = form.querySelector('input[name="password"]');

        const validateField = (field) => {
            if (!field) return true;

            let valid = true;
            const value = field.value?.trim() ?? '';

            if (field.hasAttribute('required') && value === '') {
                valid = false;
            }

            if (field.name === 'password' && value.length > 0 && value.length < 8) {
                valid = false;
            }

            field.classList.toggle('is-invalid', !valid);
            return valid;
        };

        form.querySelectorAll('input, textarea, select').forEach((field) => {
            field.addEventListener('input', () => validateField(field));
            field.addEventListener('blur', () => validateField(field));
        });

        if (passwordField) {
            passwordField.addEventListener('input', () => validateField(passwordField));
        }

        form.addEventListener('submit', (event) => {
            let isFormValid = true;

            form.querySelectorAll('input, textarea, select').forEach((field) => {
                if (!validateField(field)) {
                    isFormValid = false;
                }
            });

            if (!isFormValid) {
                event.preventDefault();
                event.stopPropagation();
            }
        });
    });
});

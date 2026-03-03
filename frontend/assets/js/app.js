import 'bootstrap';
import '@fortawesome/fontawesome-free/css/all.min.css';

document.addEventListener('DOMContentLoaded', () => {
    const forms = document.querySelectorAll('[data-validate-form]');

    const isValidEmail = (value) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);

    const validateField = (field) => {
        if (!field || field.disabled) {
            return true;
        }

        const value = field.value?.trim() ?? '';
        let isValid = true;

        if (field.hasAttribute('required') && value === '') {
            isValid = false;
        }

        if (field.type === 'email' && value !== '' && !isValidEmail(value)) {
            isValid = false;
        }

        if (field.hasAttribute('maxlength')) {
            const maxLength = Number.parseInt(field.getAttribute('maxlength') ?? '0', 10);
            if (Number.isFinite(maxLength) && maxLength > 0 && value.length > maxLength) {
                isValid = false;
            }
        }

        field.classList.toggle('is-invalid', !isValid);
        return isValid;
    };

    forms.forEach((form) => {
        const fields = form.querySelectorAll('input, select, textarea');

        fields.forEach((field) => {
            field.addEventListener('input', () => validateField(field));
            field.addEventListener('blur', () => validateField(field));
        });

        form.addEventListener('submit', (event) => {
            let formValid = true;

            fields.forEach((field) => {
                if (!validateField(field)) {
                    formValid = false;
                }
            });

            if (!formValid) {
                event.preventDefault();
                event.stopPropagation();
            }
        });
    });
});

import 'bootstrap';
import '@fortawesome/fontawesome-free/css/all.min.css';

document.addEventListener('DOMContentLoaded', () => {
    const forms = document.querySelectorAll('[data-validate-form], [data-register-form]');

    const setInvalidState = (field, invalid) => {
        if (!field) {
            return;
        }

        field.classList.toggle('is-invalid', invalid);
    };

    const validateField = (field) => {
        if (!field) {
            return true;
        }

        const value = (field.value ?? '').trim();
        let valid = true;

        if (field.hasAttribute('required') && value === '') {
            valid = false;
        }

        const minLengthAttr = field.getAttribute('minlength');
        if (minLengthAttr) {
            const minLength = Number.parseInt(minLengthAttr, 10);
            if (value !== '' && value.length < minLength) {
                valid = false;
            }
        }

        const maxLengthAttr = field.getAttribute('maxlength');
        if (maxLengthAttr) {
            const maxLength = Number.parseInt(maxLengthAttr, 10);
            if (value.length > maxLength) {
                valid = false;
            }
        }

        if (field.type === 'email' && value !== '') {
            valid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
        }

        if (field.name === 'password' && value !== '' && value.length < 8) {
            valid = false;
        }

        setInvalidState(field, !valid);
        return valid;
    };

    const validateMatch = (primaryField, confirmField) => {
        if (!primaryField || !confirmField) {
            return true;
        }

        const valid = primaryField.value !== ''
            && confirmField.value !== ''
            && primaryField.value === confirmField.value;

        setInvalidState(confirmField, !valid);
        return valid;
    };

    const updateStrength = (passwordField, strengthBar) => {
        if (!passwordField || !strengthBar) {
            return;
        }

        const value = passwordField.value ?? '';
        let score = 0;

        if (value.length >= 8) score += 1;
        if (/[A-Z]/.test(value)) score += 1;
        if (/[0-9]/.test(value)) score += 1;
        if (/[^A-Za-z0-9]/.test(value)) score += 1;

        const widths = ['0%', '25%', '50%', '75%', '100%'];
        const classes = ['bg-danger', 'bg-warning', 'bg-info', 'bg-primary', 'bg-success'];

        strengthBar.style.width = widths[score];
        strengthBar.classList.remove(...classes);
        strengthBar.classList.add(classes[score]);
    };

    forms.forEach((form) => {
        const fields = form.querySelectorAll('input, select, textarea');
        const email = form.querySelector('input[name="email"]');
        const emailConfirmation = form.querySelector('input[name="email_confirmation"]');
        const password = form.querySelector('input[name="password"]');
        const passwordConfirmation = form.querySelector('input[name="password_confirmation"]');
        const strengthBar = form.querySelector('[data-password-strength-bar]');

        fields.forEach((field) => {
            field.addEventListener('input', () => {
                validateField(field);
                validateMatch(email, emailConfirmation);
                validateMatch(password, passwordConfirmation);
                updateStrength(password, strengthBar);
            });

            field.addEventListener('blur', () => {
                validateField(field);
                validateMatch(email, emailConfirmation);
                validateMatch(password, passwordConfirmation);
            });
        });

        form.addEventListener('submit', (event) => {
            let valid = true;

            fields.forEach((field) => {
                if (!validateField(field)) {
                    valid = false;
                }
            });

            if (!validateMatch(email, emailConfirmation)) {
                valid = false;
            }

            if (!validateMatch(password, passwordConfirmation)) {
                valid = false;
            }

            if (!valid) {
                event.preventDefault();
                event.stopPropagation();
            }
        });
    });
});
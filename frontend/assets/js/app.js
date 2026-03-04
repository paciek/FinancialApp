import 'bootstrap';
import '@fortawesome/fontawesome-free/css/all.min.css';

document.addEventListener('DOMContentLoaded', () => {
    const forms = document.querySelectorAll('[data-validate-form], [data-register-form]');

    const setInvalidState = (field, invalid, extraClass = null) => {
        if (!field) {
            return;
        }

        field.classList.toggle('is-invalid', invalid);
        if (extraClass) {
            field.classList.toggle(extraClass, !invalid && field.value !== '');
        }
    };

    const validateField = (field) => {
        if (!field) {
            return true;
        }

        const value = field.value.trim();
        let valid = true;

        if (field.hasAttribute('required') && value === '') {
            valid = false;
        }

        if (field.hasAttribute('minlength')) {
            const minLength = Number.parseInt(field.getAttribute('minlength') ?? '0', 10);
            if (value !== '' && value.length < minLength) {
                valid = false;
            }
        }

        if (field.hasAttribute('maxlength')) {
            const maxLength = Number.parseInt(field.getAttribute('maxlength') ?? '0', 10);
            if (value.length > maxLength) {
                valid = false;
            }
        }

        if (field.type === 'email' && value !== '') {
            valid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
        }

        setInvalidState(field, !valid, 'is-valid');
        return valid;
    };

    const validatePasswordMatch = (password, passwordConfirmation) => {
        if (!password || !passwordConfirmation) {
            return true;
        }

        const valid = password.value !== '' && passwordConfirmation.value !== '' && password.value === passwordConfirmation.value;
        setInvalidState(passwordConfirmation, !valid, 'is-valid');
        return valid;
    };

    const updateStrength = (password, strengthBar) => {
        if (!password || !strengthBar) {
            return;
        }

        const value = password.value;
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
        const password = form.querySelector('input[name="password"]');
        const passwordConfirmation = form.querySelector('input[name="password_confirmation"]');
        const strengthBar = form.querySelector('[data-password-strength-bar]');
        const fields = form.querySelectorAll('input, select, textarea');

        fields.forEach((field) => {
            field.addEventListener('input', () => {
                validateField(field);
                validatePasswordMatch(password, passwordConfirmation);
                updateStrength(password, strengthBar);
            });
        });

        form.addEventListener('submit', (event) => {
            let valid = true;

            fields.forEach((field) => {
                if (!validateField(field)) {
                    valid = false;
                }
            });

            if (!validatePasswordMatch(password, passwordConfirmation)) {
                valid = false;
            }

            if (!valid) {
                event.preventDefault();
                event.stopPropagation();
            }
        });
    });

    const deleteForms = document.querySelectorAll('[data-delete-form]');

    deleteForms.forEach((form) => {
        form.addEventListener('submit', (event) => {
            if (!window.confirm('Czy na pewno chcesz usunac ten rekord?')) {
                event.preventDefault();
                event.stopPropagation();
            }
        });
    });

    const dateFrom = document.getElementById('date_from');
    const dateTo = document.getElementById('date_to');

    if (dateFrom && dateTo && dateFrom.form === dateTo.form) {
        dateTo.form.addEventListener('submit', (event) => {
            const fromValue = dateFrom.value;
            const toValue = dateTo.value;

            if (fromValue !== '' && toValue !== '' && fromValue > toValue) {
                event.preventDefault();
                event.stopPropagation();
                dateTo.classList.add('is-invalid');
            } else {
                dateTo.classList.remove('is-invalid');
            }
        });
    }
});

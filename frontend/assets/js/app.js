import 'bootstrap';
import '@fortawesome/fontawesome-free/css/all.min.css';

document.addEventListener('DOMContentLoaded', () => {
    const registerForm = document.querySelector('[data-register-form]');
    const filterForm = document.querySelector('[data-transaction-filter-form]');

    const setInvalidState = (field, invalid) => {
        if (!field) {
            return;
        }

        field.classList.toggle('is-invalid', invalid);
        field.classList.toggle('is-valid', !invalid && field.value !== '');
    };

    if (registerForm) {
        const email = registerForm.querySelector('#email');
        const emailConfirmation = registerForm.querySelector('#email_confirmation');
        const password = registerForm.querySelector('#password');
        const passwordConfirmation = registerForm.querySelector('#password_confirmation');
        const strengthBar = registerForm.querySelector('[data-password-strength-bar]');

        const validateEmails = () => {
            const valid = email.value !== '' && emailConfirmation.value !== '' && email.value === emailConfirmation.value;
            setInvalidState(emailConfirmation, !valid);
            return valid;
        };

        const validatePasswords = () => {
            const valid = password.value !== '' && passwordConfirmation.value !== '' && password.value === passwordConfirmation.value;
            setInvalidState(passwordConfirmation, !valid);
            return valid;
        };

        const updateStrength = () => {
            if (!strengthBar) {
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

        emailConfirmation.addEventListener('input', validateEmails);
        email.addEventListener('input', validateEmails);
        passwordConfirmation.addEventListener('input', validatePasswords);
        password.addEventListener('input', () => {
            validatePasswords();
            updateStrength();
        });

        registerForm.addEventListener('submit', (event) => {
            const emailsValid = validateEmails();
            const passwordsValid = validatePasswords();

            if (!emailsValid || !passwordsValid || !registerForm.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
                registerForm.classList.add('was-validated');
            }
        });
    }

    if (filterForm) {
        const dateFrom = filterForm.querySelector('#date_from');
        const dateTo = filterForm.querySelector('#date_to');

        filterForm.addEventListener('submit', (event) => {
            if (!dateFrom || !dateTo || dateFrom.value === '' || dateTo.value === '') {
                return;
            }

            if (dateFrom.value > dateTo.value) {
                event.preventDefault();
                event.stopPropagation();
                setInvalidState(dateTo, true);
            }
        });

        dateFrom?.addEventListener('change', () => {
            if (dateTo && dateFrom.value !== '' && dateTo.value !== '' && dateFrom.value <= dateTo.value) {
                setInvalidState(dateTo, false);
            }
        });

        dateTo?.addEventListener('change', () => {
            if (dateFrom && dateFrom.value !== '' && dateTo.value !== '' && dateFrom.value <= dateTo.value) {
                setInvalidState(dateTo, false);
            }
        });
    }
});


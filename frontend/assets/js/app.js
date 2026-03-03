import 'bootstrap';
import '@fortawesome/fontawesome-free/css/all.min.css';

document.addEventListener('DOMContentLoaded', () => {
    const form = document.querySelector('[data-register-form]');

    if (!form) {
        return;
    }

    const email = form.querySelector('#email');
    const emailConfirmation = form.querySelector('#email_confirmation');
    const password = form.querySelector('#password');
    const passwordConfirmation = form.querySelector('#password_confirmation');
    const strengthBar = form.querySelector('[data-password-strength-bar]');

    const setInvalidState = (field, invalid) => {
        if (!field) {
            return;
        }

        field.classList.toggle('is-invalid', invalid);
        if (!invalid && field.value !== '') {
            field.classList.add('is-valid');
        } else {
            field.classList.remove('is-valid');
        }
    };

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

    form.addEventListener('submit', (event) => {
        const emailsValid = validateEmails();
        const passwordsValid = validatePasswords();

        if (!emailsValid || !passwordsValid || !form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
            form.classList.add('was-validated');
        }
    });
});

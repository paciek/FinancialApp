import 'bootstrap';
import '@fortawesome/fontawesome-free/css/all.min.css';

document.addEventListener('DOMContentLoaded', () => {
    const forms = document.querySelectorAll('[data-validate-form], [data-register-form]');
    const deleteForms = document.querySelectorAll('[data-confirm-delete]');
    const transactionForms = document.querySelectorAll('[data-transaction-form]');

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
        if (!field || field.disabled) {
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

            field.addEventListener('change', () => {
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

    deleteForms.forEach((form) => {
        form.addEventListener('submit', (event) => {
            const confirmed = window.confirm('Czy na pewno chcesz usunac ten rekord?');

            if (!confirmed) {
                event.preventDefault();
                event.stopPropagation();
            }
        });
    });

    transactionForms.forEach((form) => {
        const typeField = form.querySelector('[data-transaction-type]');
        const categorySelect = form.querySelector('[data-category-select]');
        const amountField = form.querySelector('[data-amount-field]');

        const updateAmountColor = () => {
            if (!typeField || !amountField) {
                return;
            }

            amountField.classList.remove('text-success', 'text-danger');

            if (typeField.value === 'income') {
                amountField.classList.add('text-success');
            }

            if (typeField.value === 'expense') {
                amountField.classList.add('text-danger');
            }
        };

        const filterCategories = () => {
            if (!typeField || !categorySelect) {
                return;
            }

            const selectedType = typeField.value;
            const options = categorySelect.querySelectorAll('option[data-type]');

            options.forEach((option) => {
                const shouldShow = selectedType === '' || option.dataset.type === selectedType;
                option.hidden = !shouldShow;
            });

            const selectedOption = categorySelect.options[categorySelect.selectedIndex];
            if (selectedOption && selectedOption.dataset.type && selectedOption.dataset.type !== selectedType && selectedType !== '') {
                categorySelect.value = '';
            }
        };

        if (typeField) {
            typeField.addEventListener('change', () => {
                filterCategories();
                updateAmountColor();
                validateField(typeField);

                if (categorySelect) {
                    validateField(categorySelect);
                }
            });
        }

        filterCategories();
        updateAmountColor();
    });
});


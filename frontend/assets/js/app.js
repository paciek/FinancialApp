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

    const validateRadioGroup = (field) => {
        const group = document.querySelectorAll(`input[type="radio"][name="${field.name}"]`);
        const checked = Array.from(group).some((radio) => radio.checked);

        group.forEach((radio) => {
            setInvalidState(radio, !checked, 'is-valid');
        });

        return checked;
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

        if (field.tagName === 'SELECT' && field.hasAttribute('required') && value === '') {
            valid = false;
        }

        if (field.type === 'radio' && field.hasAttribute('required')) {
            return validateRadioGroup(field);
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

    document.querySelectorAll('[data-confirm="delete"]').forEach((button) => {
        button.addEventListener('click', (event) => {
            if (!confirm('Czy na pewno chcesz usunac ten element?')) {
                event.preventDefault();
            }
        });
    });

    const transactionForm = document.querySelector('[data-transaction-form]');
    if (transactionForm) {
        const typeOptions = transactionForm.querySelectorAll('[data-type-option]');
        const categorySelect = transactionForm.querySelector('[data-category-select]');
        const amountInput = transactionForm.querySelector('[data-amount-input]');

        const updateAmountStyle = (type) => {
            if (!amountInput) {
                return;
            }

            amountInput.classList.remove('text-success', 'text-danger');
            if (type === 'income') {
                amountInput.classList.add('text-success');
            }
            if (type === 'expense') {
                amountInput.classList.add('text-danger');
            }
        };

        const updateCategoryOptions = (type) => {
            if (!categorySelect) {
                return;
            }

            const options = categorySelect.querySelectorAll('option[data-type]');
            options.forEach((option) => {
                const matches = option.getAttribute('data-type') === type || !type;
                option.hidden = !matches;
                option.disabled = !matches;
            });

            if (categorySelect.value && categorySelect.selectedOptions.length > 0) {
                const selectedOption = categorySelect.selectedOptions[0];
                if (selectedOption.disabled) {
                    categorySelect.value = '';
                }
            }
        };

        const getSelectedType = () => {
            const selected = Array.from(typeOptions).find((option) => option.checked);
            return selected ? selected.value : '';
        };

        typeOptions.forEach((option) => {
            option.addEventListener('change', () => {
                const type = getSelectedType();
                updateCategoryOptions(type);
                updateAmountStyle(type);
            });
        });

        const initialType = getSelectedType();
        updateCategoryOptions(initialType);
        updateAmountStyle(initialType);
    }
});

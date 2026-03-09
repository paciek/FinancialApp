import 'bootstrap';
import '@fortawesome/fontawesome-free/js/all.js';
import Chart from 'chart.js/auto';

document.addEventListener('DOMContentLoaded', () => {
    const themeToggle = document.getElementById('themeToggle');

    const getPreferredTheme = () => {
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'light' || savedTheme === 'dark') {
            return savedTheme;
        }

        return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    };

    const applyTheme = (theme) => {
        document.documentElement.setAttribute('data-bs-theme', theme);

    };

    applyTheme(document.documentElement.getAttribute('data-bs-theme') ?? getPreferredTheme());

    themeToggle?.addEventListener('click', () => {
        const current = document.documentElement.getAttribute('data-bs-theme') ?? 'light';
        const next = current === 'dark' ? 'light' : 'dark';

        applyTheme(next);
        localStorage.setItem('theme', next);
    });

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

        if (field.hasAttribute('pattern') && value !== '') {
            const pattern = field.getAttribute('pattern');
            if (pattern) {
                valid = new RegExp(pattern).test(value);
            }
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

    const expenseChartEl = document.getElementById('expenseChart');
    if (expenseChartEl) {
        const labels = JSON.parse(expenseChartEl.dataset.labels ?? '[]');
        const values = JSON.parse(expenseChartEl.dataset.values ?? '[]');

        new Chart(expenseChartEl, {
            type: 'doughnut',
            data: {
                labels,
                datasets: [
                    {
                        label: 'Wydatki',
                        data: values,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
            },
        });
    }

    const balanceChartEl = document.getElementById('balanceChart');
    if (balanceChartEl) {
        const labels = JSON.parse(balanceChartEl.dataset.labels ?? '[]');
        const values = JSON.parse(balanceChartEl.dataset.values ?? '[]');

        new Chart(balanceChartEl, {
            type: 'line',
            data: {
                labels,
                datasets: [
                    {
                        label: 'Saldo',
                        data: values,
                        tension: 0.3,
                        borderColor: '#0d6efd',
                        backgroundColor: 'rgba(13, 110, 253, 0.15)',
                        fill: true,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
            },
        });
    }
});

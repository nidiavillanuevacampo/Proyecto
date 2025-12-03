// ============================================
// LOGIN FORM VALIDATION & INTERACTIONS
// ============================================

document.addEventListener('DOMContentLoaded', function () {
    // Get form elements
    const loginForm = document.getElementById('loginForm');
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const rememberCheckbox = document.getElementById('remember');

    // Load remembered email if exists
    loadRememberedUser();

    // Form submission handler
    loginForm.addEventListener('submit', handleFormSubmit);

    // Input animations
    addInputAnimations();

    // Secondary button handler
    const secondaryBtn = document.querySelector('.btn-secondary');
    if (secondaryBtn) {
        secondaryBtn.addEventListener('click', handleEmailLogin);
    }
});

/**
 * Handle form submission
 */
function handleFormSubmit(e) {
    e.preventDefault();

    const email = emailInput.value.trim();
    const password = passwordInput.value;
    const remember = rememberCheckbox.checked;

    // Basic validation
    if (!email || !password) {
        showError('Por favor complete todos los campos');
        return;
    }

    // Email validation
    if (!isValidEmail(email) && !isValidUsername(email)) {
        showError('Por favor ingrese un email o usuario válido');
        return;
    }

    // Remember user if checkbox is checked
    if (remember) {
        localStorage.setItem('rememberedUser', email);
    } else {
        localStorage.removeItem('rememberedUser');
    }

    // Show loading state
    showLoading();

    // Simulate login (in production, this would be an actual API call)
    setTimeout(() => {
        // For demo purposes, accept any credentials
        // In production, validate against backend
        console.log('Login attempt:', { email, remember });

        // Redirect to ventas.php
        window.location.href = 'ventas.php';
    }, 800);
}

/**
 * Handle email login button
 */
function handleEmailLogin() {
    // This could open a modal or redirect to email-based login
    console.log('Email login clicked');
    // For now, just focus the email input
    emailInput.focus();
}

/**
 * Add input animations and interactions
 */
function addInputAnimations() {
    const inputs = document.querySelectorAll('.form-input');

    inputs.forEach(input => {
        // Add focus animation
        input.addEventListener('focus', function () {
            this.parentElement.classList.add('focused');
        });

        // Remove focus animation
        input.addEventListener('blur', function () {
            this.parentElement.classList.remove('focused');
            if (this.value) {
                this.parentElement.classList.add('filled');
            } else {
                this.parentElement.classList.remove('filled');
            }
        });

        // Real-time validation feedback
        input.addEventListener('input', function () {
            clearError();
        });
    });
}

/**
 * Load remembered user from localStorage
 */
function loadRememberedUser() {
    const rememberedUser = localStorage.getItem('rememberedUser');
    if (rememberedUser) {
        emailInput.value = rememberedUser;
        rememberCheckbox.checked = true;
        emailInput.parentElement.classList.add('filled');
    }
}

/**
 * Validate email format
 */
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

/**
 * Validate username format (alphanumeric, underscore, dash)
 */
function isValidUsername(username) {
    const usernameRegex = /^[a-zA-Z0-9_-]{3,20}$/;
    return usernameRegex.test(username);
}

/**
 * Show error message
 */
function showError(message) {
    // Remove existing error if any
    clearError();

    // Create error element
    const errorDiv = document.createElement('div');
    errorDiv.className = 'error-message';
    errorDiv.textContent = message;
    errorDiv.style.cssText = `
        color: #EA4335;
        font-size: 0.875rem;
        margin-top: 0.5rem;
        padding: 0.75rem;
        background-color: #FEE;
        border-radius: 8px;
        animation: shake 0.3s ease-in-out;
    `;

    // Add shake animation
    const style = document.createElement('style');
    style.textContent = `
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-10px); }
            75% { transform: translateX(10px); }
        }
    `;
    document.head.appendChild(style);

    // Insert error message
    loginForm.insertBefore(errorDiv, loginForm.firstChild);

    // Auto-remove after 5 seconds
    setTimeout(clearError, 5000);
}

/**
 * Clear error message
 */
function clearError() {
    const errorMessage = document.querySelector('.error-message');
    if (errorMessage) {
        errorMessage.remove();
    }
}

/**
 * Show loading state on submit button
 */
function showLoading() {
    const submitBtn = document.querySelector('.btn-primary');
    const originalText = submitBtn.textContent;

    submitBtn.disabled = true;
    submitBtn.textContent = 'Iniciando sesión...';
    submitBtn.style.opacity = '0.7';
    submitBtn.style.cursor = 'not-allowed';

    // Add loading spinner
    const spinner = document.createElement('span');
    spinner.className = 'spinner';
    spinner.style.cssText = `
        display: inline-block;
        width: 16px;
        height: 16px;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-top-color: white;
        border-radius: 50%;
        animation: spin 0.6s linear infinite;
        margin-left: 8px;
    `;

    const spinStyle = document.createElement('style');
    spinStyle.textContent = `
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    `;
    document.head.appendChild(spinStyle);

    submitBtn.appendChild(spinner);
}

/**
 * Password visibility toggle (optional enhancement)
 */
function addPasswordToggle() {
    const passwordInput = document.getElementById('password');
    const toggleBtn = document.createElement('button');
    toggleBtn.type = 'button';
    toggleBtn.className = 'password-toggle';
    toggleBtn.innerHTML = '👁️';
    toggleBtn.style.cssText = `
        position: absolute;
        right: 0;
        top: 50%;
        transform: translateY(-50%);
        background: none;
        border: none;
        cursor: pointer;
        font-size: 1.2rem;
        opacity: 0.6;
        transition: opacity 0.2s;
    `;

    toggleBtn.addEventListener('click', function () {
        const type = passwordInput.type === 'password' ? 'text' : 'password';
        passwordInput.type = type;
        this.innerHTML = type === 'password' ? '👁️' : '🙈';
    });

    toggleBtn.addEventListener('mouseenter', function () {
        this.style.opacity = '1';
    });

    toggleBtn.addEventListener('mouseleave', function () {
        this.style.opacity = '0.6';
    });

    const passwordGroup = passwordInput.parentElement;
    passwordGroup.style.position = 'relative';
    passwordGroup.appendChild(toggleBtn);
}

// Initialize password toggle
// Uncomment the line below if you want password visibility toggle
// addPasswordToggle();

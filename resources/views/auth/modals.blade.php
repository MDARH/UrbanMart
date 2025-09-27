{{-- resources/views/auth/modals.blade.php --}}

<!-- Google OAuth Script -->
<script src="https://accounts.google.com/gsi/client" async defer></script>
<style>
    /* ==========================
           Modal CSS (same as your original)
           ========================== */
    .auth-modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(5px);
        align-items: center;
        justify-content: center;
    }

    .auth-modal-content {
        background: white;
        border-radius: 12px;
        padding: 2rem;
        width: 90%;
        max-width: 500px;
        max-height: 90vh;
        overflow-y: auto;
        position: relative;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        animation: modalSlideIn 0.3s ease-out;
    }

    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-50px) scale(0.9);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .auth-close-button {
        position: absolute;
        top: 1rem;
        right: 1rem;
        font-size: 1.5rem;
        cursor: pointer;
        color: #999;
        transition: color 0.2s;
    }

    .auth-close-button:hover {
        color: #333;
    }

    .auth-header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .auth-header h2 {
        margin: 0 0 0.5rem 0;
        color: #333;
        font-size: 1.5rem;
    }

    .auth-subtitle {
        color: #666;
        margin: 0;
        font-size: 0.9rem;
    }

    .auth-step {
        display: none;
    }

    .auth-step.active {
        display: block;
    }

    .auth-form-group {
        margin-bottom: 1.5rem;
    }

    .auth-form-group label {
        display: block;
        margin-bottom: 0.5rem;
        color: #333;
        font-weight: 500;
    }

    .required {
        color: #e74c3c;
    }

    .auth-form-group input,
    .auth-form-group textarea {
        width: 100%;
        padding: 0.75rem;
        border: 2px solid #e1e5e9;
        border-radius: 8px;
        font-size: 1rem;
        transition: border-color 0.2s;
    }

    .auth-form-group input:focus,
    .auth-form-group textarea:focus {
        outline: none;
        border-color: #3498db;
    }

    .auth-submit-btn {
        width: 100%;
        padding: 0.875rem;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .auth-submit-btn.primary {
        background: linear-gradient(135deg, #3498db, #2980b9);
        color: white;
    }

    .auth-submit-btn.primary:hover {
        background: linear-gradient(135deg, #2980b9, #21618c);
        transform: translateY(-1px);
    }

    .auth-divider {
        display: flex;
        align-items: center;
        margin: 1.5rem 0;
        color: #999;
    }

    .auth-divider::before,
    .auth-divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #e1e5e9;
    }

    .auth-divider span {
        margin: 0 1rem;
        font-size: 0.875rem;
    }

    .google-signin-btn {
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        padding: 0.875rem;
        border: 2px solid #e1e5e9;
        border-radius: 8px;
        background: white;
        color: #333;
        cursor: pointer;
        font-size: 1rem;
        font-weight: 500;
        transition: all 0.2s;
        text-decoration: none;
    }

    .google-signin-btn:hover {
        border-color: #4285f4;
        background: #f8f9fa;
    }

    .google-signin-btn img {
        width: 20px;
        height: 20px;
    }

    .verification-info {
        text-align: center;
        margin-bottom: 2rem;
    }

    .verification-icon {
        font-size: 3rem;
        margin-bottom: 1rem;
    }

    #userEmailDisplay {
        color: #3498db;
        font-weight: 600;
    }

    .auth-secondary-actions {
        display: flex;
        justify-content: space-between;
        margin-top: 1rem;
    }

    .link-btn {
        background: none;
        border: none;
        color: #3498db;
        cursor: pointer;
        text-decoration: underline;
        font-size: 0.875rem;
    }

    .auth-modal-tabs {
        display: flex;
        margin-bottom: 2rem;
        border-bottom: 2px solid #e1e5e9;
    }

    .auth-tab-button {
        flex: 1;
        padding: 1rem;
        border: none;
        background: none;
        cursor: pointer;
        font-size: 1rem;
        color: #666;
        border-bottom: 3px solid transparent;
        transition: all 0.2s;
    }

    .auth-tab-button.active {
        color: #3498db;
        border-bottom-color: #3498db;
    }

    .auth-section {
        display: none;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .auth-forgot-password {
        text-align: right;
        margin-bottom: 1rem;
    }

    .terms-checkbox {
        display: flex;
        align-items: flex-start;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
    }

    @media (max-width:768px) {
        .auth-modal-content {
            margin: 1rem;
            max-width: none;
            padding: 1.5rem;
        }

        .form-grid {
            grid-template-columns: 1fr;
        }

        .auth-secondary-actions {
            flex-direction: column;
            gap: 0.5rem;
            align-items: center;
        }
    }

    // Mohammad Hassan - Removed custom toast notification styles, using AIZ notification system instead
</style>

{{-- ===========================
    USER AUTH MODAL
=========================== --}}
<div id="userAuthModal" class="auth-modal">
    <div class="auth-modal-content">
        <span class="auth-close-button" onclick="closeUserModal()">&times;</span>
        <div class="auth-header">
            <h2 id="userModalTitle">User Login</h2>
            <p class="auth-subtitle">Enter your email to continue</p>
        </div>

        {{-- Email Step --}}
        <div id="userEmailStep" class="auth-step active">
            <form onsubmit="handleUserEmailSubmit(event)">
                @csrf
                <div class="auth-form-group">
                    <label for="userEmail">Email Address:</label>
                    <input type="email" id="userEmail" name="email" placeholder="Enter your email" required>
                </div>
                <button type="submit" class="auth-submit-btn primary">Continue</button>
            </form>
            <div class="auth-divider"><span>OR</span></div>
            <button class="google-signin-btn" onclick="handleGoogleSignIn()">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/c/c1/Google_%22G%22_logo.svg/2048px-Google_%22G%22_logo.svg.png"
                    alt="Google">
                Continue with Google
            </button>
        </div>

        {{-- Verification Step --}}
        <div id="userVerificationStep" class="auth-step">
            <div class="verification-info">
                <div class="verification-icon">✉️</div>
                <h3>Check Your Email</h3>
                <p>We've sent a verification code to <span id="userEmailDisplay"></span></p>
            </div>
            <form onsubmit="handleUserVerification(event)">
                @csrf
                <div class="auth-form-group">
                    <label for="verificationCode">Verification Code:</label>
                    <input type="text" id="verificationCode" name="code" placeholder="Enter 6-digit code"
                        maxlength="6" required>
                </div>
                <button type="submit" class="auth-submit-btn primary">Verify & Continue</button>
            </form>
            <div class="auth-secondary-actions">
                <button class="link-btn" onclick="resendVerificationCode()">Resend Code</button>
                <button class="link-btn" onclick="goBackToEmail()">Change Email</button>
            </div>
        </div>
    </div>
</div>

{{-- ===========================
    WHOLESALER AUTH MODAL
=========================== --}}
<div id="wholesalerAuthModal" class="auth-modal">
    <div class="auth-modal-content">
        <span class="auth-close-button" onclick="closeWholesalerModal()">&times;</span>
        <h2 id="wholesalerModalTitle">Wholesaler Access</h2>
        <div class="auth-modal-tabs">
            <button class="auth-tab-button active" id="wholesalerLoginBtn"
                onclick="switchWholesalerTab('login')">Login</button>
            <button class="auth-tab-button" id="wholesalerRegisterBtn"
                onclick="switchWholesalerTab('register')">Register</button>
        </div>
        {{-- Login Section --}}
        <div id="wholesalerLoginSection" class="auth-section active">
            <form onsubmit="handleWholesalerLogin(event)">
                @csrf
                <div class="auth-form-group">
                    <label for="wholesalerLoginEmail">Email:</label>
                    <input type="email" id="wholesalerLoginEmail" name="email" required>
                </div>
                <div class="auth-form-group">
                    <label for="wholesalerLoginPassword">Password:</label>
                    <input type="password" id="wholesalerLoginPassword" name="password" required>
                </div>
                <div class="auth-forgot-password"><a href="#" onclick="showForgotPassword()">Forgot Password?</a>
                </div>
                <button type="submit" class="auth-submit-btn primary">Login as Wholesaler</button>
            </form>
            <p class="auth-secondary-link">Don't have an account? <a href="#"
                    onclick="switchWholesalerTab('register')">Register here</a></p>
        </div>
        {{-- Registration Section --}}
        <div id="wholesalerRegisterSection" class="auth-section">
            <form onsubmit="handleWholesalerRegistration(event)">
                @csrf
                <div class="form-grid">
                    <div class="auth-form-group">
                        <label for="businessName">Business Name: <span class="required">*</span></label>
                        <input type="text" id="businessName" name="businessName" required>
                    </div>
                    <div class="auth-form-group">
                        <label for="wholesalerPhone">Phone Number: <span class="required">*</span></label>
                        <input type="tel" id="wholesalerPhone" name="phone" pattern="[0-9]{10,15}" required>
                    </div>
                </div>
                <div class="auth-form-group">
                    <label for="wholesalerEmail">Email Address: <span class="required">*</span></label>
                    <input type="email" id="wholesalerEmail" name="email" required>
                </div>
                <div class="form-grid">
                    <div class="auth-form-group">
                        <label for="facebookLink">Facebook Link:</label>
                        <input type="url" id="facebookLink" name="facebookLink"
                            placeholder="https://facebook.com/yourpage">
                    </div>
                    <div class="auth-form-group">
                        <label for="websiteLink">Website Link:</label>
                        <input type="url" id="websiteLink" name="websiteLink"
                            placeholder="https://yourwebsite.com">
                    </div>
                </div>
                <div class="auth-form-group">
                    <label for="businessAddress">Business Address: <span class="required">*</span></label>
                    <textarea id="businessAddress" name="address" rows="3" required></textarea>
                </div>
                <div class="auth-form-group">
                    <label for="tradeLicense">Trade License Number:</label>
                    <input type="text" id="tradeLicense" name="tradeLicense">
                </div>
                <div class="form-grid">
                    <div class="auth-form-group">
                        <label for="wholesalerRegPassword">Password: <span class="required">*</span></label>
                        <input type="password" id="wholesalerRegPassword" name="password" minlength="8" required>
                    </div>
                    <div class="auth-form-group">
                        <label for="wholesalerConfirmPassword">Confirm Password: <span
                                class="required">*</span></label>
                        <input type="password" id="wholesalerConfirmPassword" name="confirmPassword" required>
                    </div>
                </div>
                <div class="terms-checkbox">
                    <input type="checkbox" id="agreeTerms" required>
                    <label for="agreeTerms">I agree to the <a href="#" target="_blank">Terms &
                            Conditions</a></label>
                </div>
                <button type="submit" class="auth-submit-btn primary">Register as Wholesaler</button>
            </form>
            <p class="auth-secondary-link">Already have an account? <a href="#"
                    onclick="switchWholesalerTab('login')">Login here</a></p>
        </div>
    </div>
</div>

<!-- Mohammad Hassan - Removed toast container, using AIZ notification system instead -->

<script>
    // =========================
    // CSRF Setup
    // =========================
    if (typeof csrfToken === 'undefined') {
        var csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    }

    function csrfFetch(url, options = {}) {
        options.headers = {
            ...options.headers,
            'X-CSRF-TOKEN': csrfToken,
            'System-Key': '1'
        };
        return fetch(url, options);
    }

    // =========================
    // Mohammad Hassan - Removed custom toast notification system, using AIZ notification system instead

    // =========================
    // USER MODAL JS
    // =========================
    function openUserLogin() {
        document.getElementById('userAuthModal').style.display = 'flex';
        resetUserModal();
    }

    function closeUserModal() {
        document.getElementById('userAuthModal').style.display = 'none';
        resetUserModal();
    }

    function resetUserModal() {
        document.getElementById('userEmailStep').classList.add('active');
        document.getElementById('userVerificationStep').classList.remove('active');
        document.getElementById('userEmail').value = '';
        document.getElementById('verificationCode').value = '';
    }

    /**
     * Handle user email submission for OTP - Only for customers
     * @param {Event} event - Form submit event
     */
    function handleUserEmailSubmit(event) {
        event.preventDefault();
        const email = document.getElementById('userEmail').value;
        const submitBtn = event.target.querySelector('button');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Sending...';
        submitBtn.disabled = true;

        // Check if email belongs to admin or seller before sending OTP
        csrfFetch('/api/v2/auth/check-user-type', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                email: email
            })
        }).then(res => res.json()).then(data => {
            if (data.user_type && (data.user_type === 'admin' || data.user_type === 'seller')) {
                // Mohammad Hassan
                AIZ.plugins.notify('error',
                    'This login method is only available for customers. Please use the appropriate login for your account type.');
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
                return;
            }

            // Proceed with OTP for customers only
            csrfFetch('/api/v2/auth/user-email-submit', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    email: email
                })
            }).then(res => res.json()).then(data => {
                if (data.result) {
                    document.getElementById('userEmailStep').classList.remove('active');
                    document.getElementById('userVerificationStep').classList.add('active');
                    document.getElementById('userEmailDisplay').textContent = email;
                    // Mohammad Hassan
                    AIZ.plugins.notify('success', 'Verification code sent to your email!');
                } else {
                    // Mohammad Hassan
                    AIZ.plugins.notify('error', Array.isArray(data.message) ? data.message[0] : data.message ||
                        'Error sending verification code');
                }
            }).catch(err => {
                console.error(err);
                // Mohammad Hassan
                AIZ.plugins.notify('error', 'Network error. Please check your connection.');
            }).finally(() => {
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
        }).catch(err => {
            // If user type check fails, proceed with OTP (for new users)
            csrfFetch('/api/v2/auth/user-email-submit', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    email: email
                })
            }).then(res => res.json()).then(data => {
                if (data.result) {
                    document.getElementById('userEmailStep').classList.remove('active');
                    document.getElementById('userVerificationStep').classList.add('active');
                    document.getElementById('userEmailDisplay').textContent = email;
                    // Mohammad Hassan
                    AIZ.plugins.notify('success', 'Verification code sent to your email!');
                } else {
                    // Mohammad Hassan
                    AIZ.plugins.notify('error', Array.isArray(data.message) ? data.message[0] : data.message ||
                        'Error sending verification code');
                }
            }).catch(err => {
                console.error(err);
                // Mohammad Hassan
                AIZ.plugins.notify('error', 'Network error. Please check your connection.');
            }).finally(() => {
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
        });
    }

    function handleUserVerification(event) {
        event.preventDefault();
        const email = document.getElementById('userEmail').value;
        const code = document.getElementById('verificationCode').value;
        const submitBtn = event.target.querySelector('button');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Verifying...';
        submitBtn.disabled = true;

        csrfFetch('/api/v2/auth/user-verify-code', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                email: email,
                code: code
            })
        }).then(res => res.json()).then(data => {
            if (data.result) {
                // Store authentication data in localStorage for API calls
                localStorage.setItem('auth_token', data.access_token);
                localStorage.setItem('user', JSON.stringify(data.user));

                // Set authentication cookie for Laravel session
                document.cookie = `auth_token=${data.access_token}; path=/; max-age=86400; SameSite=Lax`;

                // Mohammad Hassan
                AIZ.plugins.notify('success', 'Email verified successfully! Welcome to Urban Mart!');
                closeUserModal();

                // Redirect with proper Laravel session authentication
                // The server now logs the user in, so we can redirect immediately
                setTimeout(() => {
                    // Reload current page to reflect authentication state for all user types
                    window.location.reload();
                }, 1000); // Reduced timeout since session is established server-side
            } else {
                // Mohammad Hassan
                AIZ.plugins.notify('error', Array.isArray(data.message) ? data.message[0] : data.message ||
                    'Invalid or expired verification code');
            }
        }).catch(err => {
            console.error(err);
            // Mohammad Hassan
            AIZ.plugins.notify('error', 'Network error. Please check your connection and try again.');
        }).finally(() => {
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        });
    }

    function resendVerificationCode() {
        const email = document.getElementById('userEmail').value;
        csrfFetch('/api/v2/auth/user-resend-code', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                email: email
            })
        }).then(res => res.json()).then(data => {
            // Mohammad Hassan
            if (data.result) AIZ.plugins.notify('success', 'Verification code resent successfully!');
            else AIZ.plugins.notify('error', Array.isArray(data.message) ? data.message[0] : data.message ||
                'Failed to resend code');
        }).catch(err => {
            console.error(err);
            // Mohammad Hassan
            AIZ.plugins.notify('error', 'Network error. Please try again.');
        });
    }

    function goBackToEmail() {
        document.getElementById('userVerificationStep').classList.remove('active');
        document.getElementById('userEmailStep').classList.add('active');
    }

    function handleGoogleSignIn() {
        // Initialize Google OAuth
        if (typeof google !== 'undefined' && google.accounts) {
            google.accounts.id.initialize({
                client_id: '{{ env('GOOGLE_CLIENT_ID') }}',
                callback: handleGoogleResponse
            });

            google.accounts.id.prompt((notification) => {
                if (notification.isNotDisplayed() || notification.isSkippedMoment()) {
                    // Fallback to popup
                    google.accounts.oauth2.initTokenClient({
                        client_id: '{{ env('GOOGLE_CLIENT_ID') }}',
                        scope: 'profile email',
                        callback: handleGoogleTokenResponse
                    }).requestAccessToken();
                }
            });
        } else {
            // Mohammad Hassan
            AIZ.plugins.notify('error', 'Google OAuth is not properly configured. Please contact support.');
        }
    }

    function handleGoogleResponse(response) {
        // Handle ID token response
        const credential = response.credential;

        csrfFetch('/api/v2/auth/google-login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    id_token: credential
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.result) {
                    // Store authentication data in localStorage for API calls
                    localStorage.setItem('auth_token', data.access_token);
                    localStorage.setItem('user', JSON.stringify(data.user));

                    // Set authentication cookie for Laravel session
                    document.cookie = `auth_token=${data.access_token}; path=/; max-age=86400; SameSite=Lax`;

                    // Mohammad Hassan
                    AIZ.plugins.notify('success', 'Google login successful! Welcome to Urban Mart!');
                    closeUserModal();
                    setTimeout(() => {
                        // Reload current page to reflect authentication state
                        window.location.reload();
                    }, 1000);
                } else {
                    // Mohammad Hassan
                    AIZ.plugins.notify('error', data.message || 'Google login failed');
                }
            })
            .catch(err => {
                console.error(err);
                // Mohammad Hassan
                AIZ.plugins.notify('error', 'Google login failed. Please try again.');
            });
    }

    function handleGoogleTokenResponse(response) {
        // Handle access token response
        if (response.access_token) {
            csrfFetch('/api/v2/auth/google-login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        access_token: response.access_token
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.result) {
                        // Store authentication data in localStorage for API calls
                        localStorage.setItem('auth_token', data.access_token);
                        localStorage.setItem('user', JSON.stringify(data.user));

                        // Set authentication cookie for Laravel session
                        document.cookie = `auth_token=${data.access_token}; path=/; max-age=86400; SameSite=Lax`;

                        // Mohammad Hassan
                        AIZ.plugins.notify('success', 'Google login successful! Welcome to Urban Mart!');
                        closeUserModal();
                        setTimeout(() => {
                            // Reload current page to reflect authentication state
                            window.location.reload();
                        }, 1000);
                    } else {
                        // Mohammad Hassan
                        AIZ.plugins.notify('error', data.message || 'Google login failed');
                    }
                })
                .catch(err => {
                    console.error(err);
                    // Mohammad Hassan
                    AIZ.plugins.notify('error', 'Google login failed. Please try again.');
                });
        }
    }

    // =========================
    // WHOLESALER MODAL JS
    // =========================
    function openWholesalerLogin() {
        document.getElementById('wholesalerAuthModal').style.display = 'flex';
        switchWholesalerTab('login');
    }

    function closeWholesalerModal() {
        document.getElementById('wholesalerAuthModal').style.display = 'none';
    }

    function switchWholesalerTab(tab) {
        const loginBtn = document.getElementById('wholesalerLoginBtn');
        const registerBtn = document.getElementById('wholesalerRegisterBtn');
        const loginSection = document.getElementById('wholesalerLoginSection');
        const registerSection = document.getElementById('wholesalerRegisterSection');
        if (tab === 'login') {
            loginBtn.classList.add('active');
            registerBtn.classList.remove('active');
            loginSection.style.display = 'block';
            registerSection.style.display = 'none';
            document.getElementById('wholesalerModalTitle').textContent = 'Wholesaler Login';
        } else {
            registerBtn.classList.add('active');
            loginBtn.classList.remove('active');
            registerSection.style.display = 'block';
            loginSection.style.display = 'none';
            document.getElementById('wholesalerModalTitle').textContent = 'Wholesaler Registration';
        }
    }

  function handleWholesalerLogin(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    const data = Object.fromEntries(formData);
    const submitBtn = event.target.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;

    submitBtn.textContent = 'Logging in...';
    submitBtn.disabled = true;

    csrfFetch('/api/v2/auth/wholesaler-login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(data => {
            if (data.result) {
                // Mohammad Hassan
                // Store auth token and user data
                localStorage.setItem('auth_token', data.access_token);
                localStorage.setItem('user', JSON.stringify(data.user));

                // Show success message with proper type
                AIZ.plugins.notify(data.message_type || 'success', data.message || 'Login successful! Redirecting to dashboard...');
                closeWholesalerModal();

                // Redirect to dashboard using the provided URL
                setTimeout(() => {
                    window.location.href = data.redirect_url || '/dashboard';
                }, 1000);
            } else {
                // Show error message with proper type
                // Mohammad Hassan
                AIZ.plugins.notify(data.message_type || 'error', data.message || 'Login failed');
            }
        })
        .catch(err => {
            console.error(err);
            // Mohammad Hassan
            AIZ.plugins.notify('error', 'Login failed. Please try again.');
        })
        .finally(() => {
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        });
}


    function handleWholesalerRegistration(event) {
        event.preventDefault();
        const formData = new FormData(event.target);
        const data = Object.fromEntries(formData);

        // Validate password confirmation
        if (data.password !== data.confirmPassword) {
            // Mohammad Hassan
            AIZ.plugins.notify('error', 'Passwords do not match!');
            return;
        }

        const submitBtn = event.target.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;

        submitBtn.textContent = 'Registering...';
        submitBtn.disabled = true;

        csrfFetch('/api/v2/auth/wholesaler-register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(data => {
                // START: এই অংশটি পরিবর্তন করুন
                if (data.result) {
                    // রেজিস্ট্রেশন সফল হলে এই বার্তাটি দেখানো হবে এবং এটি ১০ সেকেন্ড থাকবে।
                    // Mohammad Hassan
                    AIZ.plugins.notify('success', 
                        'Registration successful! Your account is pending approval. You will be notified once approved.');

                    // ফর্মটি রিসেট করে দেওয়া হচ্ছে
                    event.target.reset();

                    // মডালটি বন্ধ করার আগে কিছুক্ষণ অপেক্ষা করা হচ্ছে যাতে ইউজার মেসেজটি পড়তে পারে।
                    setTimeout(() => {
                        closeWholesalerModal();
                        // লগইন ট্যাবে সুইচ করার প্রয়োজন নেই, কারণ ইউজারকে অনুমোদনের জন্য অপেক্ষা করতে হবে।
                    }, 3000); // ৩ সেকেন্ড পর মডাল বন্ধ হবে

                } else {
                    // রেজিস্ট্রেশন ফেইল হলে এরর মেসেজ দেখানো হবে, যা ৮ সেকেন্ড থাকবে।
                    // data.message থেকে মূল এরর মেসেজটি নেওয়া হচ্ছে।
                    const errorMessage = data.message || 'Registration failed';
                    const finalMessage = typeof errorMessage === 'object' ? Object.values(errorMessage).join(' ') :
                        errorMessage;
                    // Mohammad Hassan
                    AIZ.plugins.notify('error', finalMessage);
                }
                // END: এই অংশটি পরিবর্তন করুন
            })
            .catch(err => {
                console.error(err);
                // Mohammad Hassan
                AIZ.plugins.notify('error', 'Registration failed. Please try again.');
            })
            .finally(() => {
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
            });
    }

    // ... বাকি কোড ...
    window.onclick = function(event) {
        if (event.target === document.getElementById('userAuthModal')) closeUserModal();
        if (event.target === document.getElementById('wholesalerAuthModal')) closeWholesalerModal();
    }
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeUserModal();
            closeWholesalerModal();
        }
    });
</script>

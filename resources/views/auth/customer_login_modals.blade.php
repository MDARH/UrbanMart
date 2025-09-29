<!-- Mohammad Hassan -->
<div class="modal fade" id="customerAuthModal" tabindex="-1" role="dialog" aria-labelledby="customerAuthModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <!-- Mohammad Hassan: CSS for Google sign-in button -->
            <style>
                .separator {
                    display: flex;
                    align-items: center;
                    text-align: center;
                    color: #ccc;
                }
                
                .separator::before,
                .separator::after {
                    content: '';
                    flex: 1;
                    border-bottom: 1px solid #eee;
                }
                
                /* Mohammad Hassan: Google sign-in button styling */
                .google-signin {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    gap: 8px;
                    background-color: #17a2b8;
                    border-color: #17a2b8;
                    color: white;
                    transition: all 0.3s ease;
                }
                
                .google-signin:hover {
                    background-color: #138496;
                    border-color: #117a8b;
                    box-shadow: 0 0 0 0.2rem rgba(23, 162, 184, 0.25);
                }
                
                .google-icon {
                    background-color: white;
                    border-radius: 2px;
                    padding: 2px;
                    height: 22px;
                    width: 22px;
                }
            </style>
            <div class="modal-header">
                <h5 class="modal-title" id="customerAuthModalLabel">{{ translate('Customer Login') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Mohammad Hassan: Email step (send verification code) -->
                <div id="customerEmailStep" class="active">
                    <form class="form-default" role="form" onsubmit="handleCustomerEmailSubmit(event)">
                        <input type="hidden" name="user_type" value="customer">
                        <div class="form-group">
                            <input type="email" class="form-control" placeholder="{{ translate('Email') }}" name="email" id="customerEmail" autocomplete="off" required>
                        </div>
                        <div class="mb-3">
                            <small class="text-muted">{{ translate('We will send a 6-digit verification code to your email.') }}</small>
                        </div>
                        <div class="mb-4">
                            <button type="submit" class="btn btn-primary btn-block fw-600">{{ translate('Send Verification Code') }}</button>
                        </div>
                        
                        <!-- Mohammad Hassan: Google Login Button -->
                        <div class="mb-3 text-center">
                            <div class="separator mb-3">
                                <span class="bg-white px-3">{{ translate('OR') }}</span>
                            </div>
                            <a href="{{ route('social.login', ['provider' => 'google']) }}" class="btn btn-info btn-block google-signin">
                                <svg class="google-icon mr-2" width="18" height="18" viewBox="0 0 48 48">
                                    <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                                    <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                                    <path fill="#FBBC05" d="M10.53 28.59c-.48-1.45-.76-2.99-.76-4.59s.27-3.14.76-4.59l-7.98-6.19C.92 16.46 0 20.12 0 24c0 3.88.92 7.54 2.56 10.78l7.97-6.19z"/>
                                    <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 6.19C6.51 42.62 14.62 48 24 48z"/>
                                </svg>
                                {{ translate('Continue with Google') }}
                            </a>
                        </div>
                    </form>
                </div>

                <!-- Mohammad Hassan: Verification step (enter code and login) -->
                <div id="customerVerificationStep" style="display: none;">
                    <form class="form-default" role="form" onsubmit="handleCustomerVerification(event)">
                        <div class="form-group">
                            <label class="opacity-70">{{ translate('Email') }}</label>
                            <div class="d-flex align-items-center">
                                <span id="customerEmailDisplay" class="fw-600"></span>
                                <a href="javascript:void(0)" class="ml-auto text-primary" onclick="goBackToCustomerEmail()">{{ translate('Change') }}</a>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" placeholder="{{ translate('Enter 6-digit code') }}" id="customerVerificationCode" maxlength="6" autocomplete="one-time-code" required>
                        </div>
                        <div class="d-flex justify-content-between mb-4">
                            <!-- Mohammad Hassan -->
                            <div class="d-flex align-items-center">
                                <button type="button" id="customerResendBtn" class="btn btn-link p-0 text-reset opacity-70" onclick="resendCustomerVerificationCode()" disabled>{{ translate('Resend Code') }}</button>
                                <span id="customerResendTimer" class="ml-2 small text-muted"></span>
                            </div>
                            <button type="submit" class="btn btn-primary fw-600">{{ translate('Verify & Login') }}</button>
                        </div>
                    </form>
                </div>

                {{-- Mohammad Hassan --}}
            </div>
        </div>
    </div>
</div>

<script>
// Mohammad Hassan
function openCustomerLogin() {
    // Ensure initial state
    document.getElementById('customerEmailStep').style.display = 'block';
    document.getElementById('customerVerificationStep').style.display = 'none';
    const emailInput = document.getElementById('customerEmail');
    const codeInput = document.getElementById('customerVerificationCode');
    if (emailInput) emailInput.value = '';
    if (codeInput) codeInput.value = '';
    // Mohammad Hassan
    resetCustomerResendState();
    $('#customerAuthModal').modal('show');
}

// Mohammad Hassan
function handleCustomerEmailSubmit(event) {
    event.preventDefault();
    const email = document.getElementById('customerEmail').value.trim();
    if (!email) return;

    const submitBtn = event.target.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = '{{ translate('Sending...') }}';
    submitBtn.disabled = true;

    fetch('/api/v2/auth/user-email-submit', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            // Mohammad Hassan
            'System-Key': '{{ config('app.system_key') }}',
        },
        body: JSON.stringify({ email: email })
    })
    .then(response => response.json())
    .then(data => {
        if (data.result) {
            document.getElementById('customerEmailStep').style.display = 'none';
            document.getElementById('customerVerificationStep').style.display = 'block';
            document.getElementById('customerEmailDisplay').textContent = email;
            // Mohammad Hassan
            startCustomerResendCountdown();
        } else {
            const message = Array.isArray(data.message) ? data.message[0] : data.message;
            alert(message || '{{ translate('Error sending verification code') }}');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('{{ translate('Network error. Please try again.') }}');
    })
    .finally(() => {
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    });
}

// Mohammad Hassan
function handleCustomerVerification(event) {
    event.preventDefault();
    const email = document.getElementById('customerEmail').value.trim();
    const code = document.getElementById('customerVerificationCode').value.trim();
    if (!email || !code) return;

    const submitBtn = event.target.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = '{{ translate('Verifying...') }}';
    submitBtn.disabled = true;

    fetch('/api/v2/auth/user-verify-code', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            // Mohammad Hassan
            'System-Key': '{{ config('app.system_key') }}',
        },
        body: JSON.stringify({ email: email, code: code })
    })
    .then(response => response.json())
    .then(data => {
        if (data.result) {
            // Store token and user data if provided
            if (data.access_token) {
                localStorage.setItem('auth_token', data.access_token);
            }
            if (data.user) {
                localStorage.setItem('user', JSON.stringify(data.user));
            }
            alert('{{ translate('Email verified successfully! You are now logged in.') }}');
            $('#customerAuthModal').modal('hide');
            window.location.reload();
        } else {
            const message = Array.isArray(data.message) ? data.message[0] : data.message;
            alert(message || '{{ translate('Invalid verification code') }}');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('{{ translate('Network error. Please try again.') }}');
    })
    .finally(() => {
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
    });
}

// Mohammad Hassan
function resendCustomerVerificationCode() {
    const btn = document.getElementById('customerResendBtn');
    if (btn && btn.disabled) return; // Mohammad Hassan
    const email = document.getElementById('customerEmail').value.trim();
    if (!email) return;

    // Mohammad Hassan
    btn.disabled = true;

    fetch('/api/v2/auth/user-resend-code', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            // Mohammad Hassan
            'System-Key': '{{ config('app.system_key') }}',
        },
        body: JSON.stringify({ email: email })
    })
    .then(response => response.json())
    .then(data => {
        if (data.result) {
            alert('{{ translate('Verification code resent successfully!') }}');
            // Mohammad Hassan
            startCustomerResendCountdown();
        } else {
            const message = Array.isArray(data.message) ? data.message[0] : data.message;
            alert(message || '{{ translate('Error resending code') }}');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('{{ translate('Network error. Please try again.') }}');
    });
}

// Mohammad Hassan
function goBackToCustomerEmail() {
    document.getElementById('customerVerificationStep').style.display = 'none';
    document.getElementById('customerEmailStep').style.display = 'block';
    // Mohammad Hassan
    resetCustomerResendState();
}

// Mohammad Hassan
let customerResendInterval = null;
const CUSTOMER_RESEND_SECONDS = 60; // Mohammad Hassan
function startCustomerResendCountdown(seconds = CUSTOMER_RESEND_SECONDS) {
    const btn = document.getElementById('customerResendBtn');
    const timerEl = document.getElementById('customerResendTimer');
    if (!btn || !timerEl) return;
    btn.disabled = true; // Mohammad Hassan
    let remaining = seconds;
    timerEl.textContent = `${remaining}s`;
    clearInterval(customerResendInterval);
    customerResendInterval = setInterval(() => {
        remaining -= 1;
        timerEl.textContent = `${remaining}s`;
        if (remaining <= 0) {
            clearInterval(customerResendInterval);
            customerResendInterval = null;
            btn.disabled = false; // Mohammad Hassan
            timerEl.textContent = '';
        }
    }, 1000);
}
// Mohammad Hassan
function resetCustomerResendState() {
    const btn = document.getElementById('customerResendBtn');
    const timerEl = document.getElementById('customerResendTimer');
    if (btn) btn.disabled = true;
    if (timerEl) timerEl.textContent = '';
    if (customerResendInterval) {
        clearInterval(customerResendInterval);
        customerResendInterval = null;
    }
}
</script>

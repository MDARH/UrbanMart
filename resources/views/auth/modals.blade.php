{{-- resources/views/auth/modals.blade.php --}}

<!-- Google OAuth Script -->
<script src="https://accounts.google.com/gsi/client" async defer></script>
<style>
        /* ==========================
           Modal CSS (same as your original)
           ========================== */
        .auth-modal { display: none; position: fixed; z-index: 1000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5); backdrop-filter: blur(5px); align-items: center; justify-content: center; }
        .auth-modal-content { background: white; border-radius: 12px; padding: 2rem; width: 90%; max-width: 500px; max-height: 90vh; overflow-y: auto; position: relative; box-shadow: 0 20px 60px rgba(0,0,0,0.3); animation: modalSlideIn 0.3s ease-out; }
        @keyframes modalSlideIn { from { opacity:0; transform: translateY(-50px) scale(0.9);} to {opacity:1; transform: translateY(0) scale(1);} }
        .auth-close-button { position:absolute; top:1rem; right:1rem; font-size:1.5rem; cursor:pointer; color:#999; transition: color 0.2s; }
        .auth-close-button:hover { color:#333; }
        .auth-header { text-align:center; margin-bottom:2rem; }
        .auth-header h2 { margin:0 0 0.5rem 0; color:#333; font-size:1.5rem; }
        .auth-subtitle { color:#666; margin:0; font-size:0.9rem; }
        .auth-step { display:none; }
        .auth-step.active { display:block; }
        .auth-form-group { margin-bottom:1.5rem; }
        .auth-form-group label { display:block; margin-bottom:0.5rem; color:#333; font-weight:500; }
        .required { color:#e74c3c; }
        .auth-form-group input, .auth-form-group textarea { width:100%; padding:0.75rem; border:2px solid #e1e5e9; border-radius:8px; font-size:1rem; transition:border-color 0.2s; }
        .auth-form-group input:focus, .auth-form-group textarea:focus { outline:none; border-color:#3498db; }
        .auth-submit-btn { width:100%; padding:0.875rem; border:none; border-radius:8px; font-size:1rem; font-weight:600; cursor:pointer; transition: all 0.2s; }
        .auth-submit-btn.primary { background: linear-gradient(135deg, #3498db, #2980b9); color:white; }
        .auth-submit-btn.primary:hover { background: linear-gradient(135deg, #2980b9, #21618c); transform: translateY(-1px); }
        .auth-divider { display:flex; align-items:center; margin:1.5rem 0; color:#999; }
        .auth-divider::before, .auth-divider::after { content:''; flex:1; height:1px; background:#e1e5e9; }
        .auth-divider span { margin:0 1rem; font-size:0.875rem; }
        .google-signin-btn { width:100%; display:flex; align-items:center; justify-content:center; gap:0.75rem; padding:0.875rem; border:2px solid #e1e5e9; border-radius:8px; background:white; color:#333; cursor:pointer; font-size:1rem; font-weight:500; transition: all 0.2s; text-decoration:none; }
        .google-signin-btn:hover { border-color:#4285f4; background:#f8f9fa; }
        .google-signin-btn img { width:20px; height:20px; }
        .verification-info { text-align:center; margin-bottom:2rem; }
        .verification-icon { font-size:3rem; margin-bottom:1rem; }
        #userEmailDisplay { color:#3498db; font-weight:600; }
        .auth-secondary-actions { display:flex; justify-content:space-between; margin-top:1rem; }
        .link-btn { background:none; border:none; color:#3498db; cursor:pointer; text-decoration:underline; font-size:0.875rem; }
        .auth-modal-tabs { display:flex; margin-bottom:2rem; border-bottom:2px solid #e1e5e9; }
        .auth-tab-button { flex:1; padding:1rem; border:none; background:none; cursor:pointer; font-size:1rem; color:#666; border-bottom:3px solid transparent; transition: all 0.2s; }
        .auth-tab-button.active { color:#3498db; border-bottom-color:#3498db; }
        .auth-section { display:none; }
        .form-grid { display:grid; grid-template-columns:1fr 1fr; gap:1rem; }
        .auth-forgot-password { text-align:right; margin-bottom:1rem; }
        .terms-checkbox { display:flex; align-items:flex-start; gap:0.5rem; margin-bottom:1.5rem; }
        @media (max-width:768px) { .auth-modal-content { margin:1rem; max-width:none; padding:1.5rem; } .form-grid { grid-template-columns:1fr; } .auth-secondary-actions { flex-direction:column; gap:0.5rem; align-items:center; } }
        
        /* Toast Notification Styles */
        .toast-container { position: fixed; top: 20px; right: 20px; z-index: 10000; }
        .toast { background: white; border-radius: 8px; padding: 16px 20px; margin-bottom: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); border-left: 4px solid #3498db; min-width: 320px; max-width: 400px; animation: slideInRight 0.3s ease-out; word-wrap: break-word; }
        .toast.success { border-left-color: #27ae60; }
        .toast.error { border-left-color: #e74c3c; }
        .toast.warning { border-left-color: #f39c12; }
        .toast-content { display: flex; align-items: center; gap: 12px; }
        .toast-icon { font-size: 20px; }
        .toast.success .toast-icon::before { content: '✅'; }
        .toast.error .toast-icon::before { content: '❌'; }
        .toast.warning .toast-icon::before { content: '⚠️'; }
        .toast-message { flex: 1; font-size: 15px; color: #333; line-height: 1.4; font-weight: 500; }
        .toast-close { cursor: pointer; font-size: 18px; color: #999; }
        .toast-close:hover { color: #333; }
        @keyframes slideInRight { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        @keyframes slideOutRight { from { transform: translateX(0); opacity: 1; } to { transform: translateX(100%); opacity: 0; } }
    </style>

{{-- ===========================
    USER AUTH MODAL
=========================== --}}
<div id="userAuthModal" class="auth-modal">
    <div class="auth-modal-content">
        <span class="auth-close-button" onclick="closeUserModal()">&times;</span>
        <div class="auth-header">
            <h2 id="userModalTitle">User Access</h2>
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
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/c/c1/Google_%22G%22_logo.svg/2048px-Google_%22G%22_logo.svg.png" alt="Google">
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
                    <input type="text" id="verificationCode" name="code" placeholder="Enter 6-digit code" maxlength="6" required>
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
            <button class="auth-tab-button active" id="wholesalerLoginBtn" onclick="switchWholesalerTab('login')">Login</button>
            <button class="auth-tab-button" id="wholesalerRegisterBtn" onclick="switchWholesalerTab('register')">Register</button>
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
                <div class="auth-forgot-password"><a href="#" onclick="showForgotPassword()">Forgot Password?</a></div>
                <button type="submit" class="auth-submit-btn primary">Login as Wholesaler</button>
            </form>
            <p class="auth-secondary-link">Don't have an account? <a href="#" onclick="switchWholesalerTab('register')">Register here</a></p>
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
                        <input type="url" id="facebookLink" name="facebookLink" placeholder="https://facebook.com/yourpage">
                    </div>
                    <div class="auth-form-group">
                        <label for="websiteLink">Website Link:</label>
                        <input type="url" id="websiteLink" name="websiteLink" placeholder="https://yourwebsite.com">
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
                        <label for="wholesalerConfirmPassword">Confirm Password: <span class="required">*</span></label>
                        <input type="password" id="wholesalerConfirmPassword" name="confirmPassword" required>
                    </div>
                </div>
                <div class="terms-checkbox">
                    <input type="checkbox" id="agreeTerms" required>
                    <label for="agreeTerms">I agree to the <a href="#" target="_blank">Terms & Conditions</a></label>
                </div>
                <button type="submit" class="auth-submit-btn primary">Register as Wholesaler</button>
            </form>
            <p class="auth-secondary-link">Already have an account? <a href="#" onclick="switchWholesalerTab('login')">Login here</a></p>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div id="toastContainer" class="toast-container"></div>

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
    // Toast Notification System
    // =========================
    function showToast(message, type = 'info', duration = 5000) { // 5 seconds for normal display
        let container = document.getElementById('toastContainer');
        
        // Create container if it doesn't exist
        if (!container) {
            container = document.createElement('div');
            container.id = 'toastContainer';
            container.className = 'toast-container';
            document.body.appendChild(container);
        }
        
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        
        toast.innerHTML = `
            <div class="toast-content">
                <div class="toast-icon"></div>
                <div class="toast-message">${message}</div>
                <div class="toast-close" onclick="removeToast(this.parentElement.parentElement)">&times;</div>
            </div>
        `;
        
        container.appendChild(toast);
        
        // Auto remove after duration
        setTimeout(() => {
            removeToast(toast);
        }, duration);
    }
    
    function removeToast(toast) {
        if (toast && toast.parentElement) {
            toast.style.animation = 'slideOutRight 0.3s ease-out';
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.parentElement.removeChild(toast);
                }
            }, 300);
        }
    }

    // =========================
    // USER MODAL JS
    // =========================
    function openUserLogin() { document.getElementById('userAuthModal').style.display='flex'; resetUserModal(); }
    function closeUserModal() { document.getElementById('userAuthModal').style.display='none'; resetUserModal(); }
    function resetUserModal() {
        document.getElementById('userEmailStep').classList.add('active');
        document.getElementById('userVerificationStep').classList.remove('active');
        document.getElementById('userEmail').value='';
        document.getElementById('verificationCode').value='';
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
        submitBtn.textContent = 'Sending...'; submitBtn.disabled = true;

        // Check if email belongs to admin or seller before sending OTP
        csrfFetch('/api/v2/auth/check-user-type', {
            method:'POST', headers:{'Content-Type':'application/json','Accept':'application/json'}, body:JSON.stringify({email:email})
        }).then(res => res.json()).then(data => {
            if(data.user_type && (data.user_type === 'admin' || data.user_type === 'seller')) {
                showToast('This login method is only available for customers. Please use the appropriate login for your account type.', 'error');
                submitBtn.textContent = originalText; 
                submitBtn.disabled = false;
                return;
            }
            
            // Proceed with OTP for customers only
            csrfFetch('/api/v2/auth/user-email-submit', {
                method:'POST', headers:{'Content-Type':'application/json','Accept':'application/json'}, body:JSON.stringify({email:email})
            }).then(res => res.json()).then(data => {
                if(data.result){
                    document.getElementById('userEmailStep').classList.remove('active');
                    document.getElementById('userVerificationStep').classList.add('active');
                    document.getElementById('userEmailDisplay').textContent = email;
                    showToast('Verification code sent to your email!', 'success');
                } else { showToast(Array.isArray(data.message)?data.message[0]:data.message||'Error sending verification code', 'error'); }
            }).catch(err => { console.error(err); showToast('Network error. Please check your connection.', 'error'); }).finally(()=>{ submitBtn.textContent=originalText; submitBtn.disabled=false; });
        }).catch(err => {
            // If user type check fails, proceed with OTP (for new users)
            csrfFetch('/api/v2/auth/user-email-submit', {
                method:'POST', headers:{'Content-Type':'application/json','Accept':'application/json'}, body:JSON.stringify({email:email})
            }).then(res => res.json()).then(data => {
                if(data.result){
                    document.getElementById('userEmailStep').classList.remove('active');
                    document.getElementById('userVerificationStep').classList.add('active');
                    document.getElementById('userEmailDisplay').textContent = email;
                    showToast('Verification code sent to your email!', 'success');
                } else { showToast(Array.isArray(data.message)?data.message[0]:data.message||'Error sending verification code', 'error'); }
            }).catch(err => { console.error(err); showToast('Network error. Please check your connection.', 'error'); }).finally(()=>{ submitBtn.textContent=originalText; submitBtn.disabled=false; });
        });
    }

    function handleUserVerification(event) {
        event.preventDefault();
        const email=document.getElementById('userEmail').value;
        const code=document.getElementById('verificationCode').value;
        const submitBtn = event.target.querySelector('button');
        const originalText = submitBtn.textContent;
        submitBtn.textContent='Verifying...'; submitBtn.disabled=true;

        csrfFetch('/api/v2/auth/user-verify-code', {
            method:'POST', headers:{'Content-Type':'application/json','Accept':'application/json'}, body:JSON.stringify({email: email, code: code})
        }).then(res=>res.json()).then(data=>{
            if(data.result){ 
                // Store authentication data in localStorage for API calls
                localStorage.setItem('auth_token',data.access_token); 
                localStorage.setItem('user',JSON.stringify(data.user));
                
                // Set authentication cookie for Laravel session
                document.cookie = `auth_token=${data.access_token}; path=/; max-age=86400; SameSite=Lax`;
                
                showToast('Email verified successfully! Welcome to Urban Mart!', 'success');
                closeUserModal(); 
                
                // Redirect with proper Laravel session authentication
                // The server now logs the user in, so we can redirect immediately
                setTimeout(() => {
                    // Reload current page to reflect authentication state for all user types
                    window.location.reload();
                }, 1000); // Reduced timeout since session is established server-side
            } else { 
                showToast(Array.isArray(data.message)?data.message[0]:data.message||'Invalid or expired verification code', 'error'); 
            }
        }).catch(err=>{
            console.error(err); 
            showToast('Network error. Please check your connection and try again.', 'error');
        }).finally(()=>{submitBtn.textContent=originalText; submitBtn.disabled=false;});
    }

    function resendVerificationCode() {
        const email=document.getElementById('userEmail').value;
        csrfFetch('/api/v2/auth/user-resend-code',{
            method:'POST', headers:{'Content-Type':'application/json','Accept':'application/json'}, body:JSON.stringify({email:email})
        }).then(res=>res.json()).then(data=>{ 
            if(data.result) showToast('Verification code resent successfully!', 'success'); 
            else showToast(Array.isArray(data.message)?data.message[0]:data.message||'Failed to resend code', 'error'); 
        }).catch(err=>{console.error(err); showToast('Network error. Please try again.', 'error'); });
    }

    function goBackToEmail(){ document.getElementById('userVerificationStep').classList.remove('active'); document.getElementById('userEmailStep').classList.add('active'); }

    function handleGoogleSignIn() {
        // Initialize Google OAuth
        if (typeof google !== 'undefined' && google.accounts) {
            google.accounts.id.initialize({
                client_id: '{{ env("GOOGLE_CLIENT_ID") }}',
                callback: handleGoogleResponse
            });
            
            google.accounts.id.prompt((notification) => {
                if (notification.isNotDisplayed() || notification.isSkippedMoment()) {
                    // Fallback to popup
                    google.accounts.oauth2.initTokenClient({
                        client_id: '{{ env("GOOGLE_CLIENT_ID") }}',
                        scope: 'profile email',
                        callback: handleGoogleTokenResponse
                    }).requestAccessToken();
                }
            });
        } else {
            showToast('Google OAuth is not properly configured. Please contact support.', 'error');
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
                
                showToast('Google login successful! Welcome to Urban Mart!', 'success');
                closeUserModal();
                setTimeout(() => {
                    // Reload current page to reflect authentication state
                    window.location.reload();
                }, 1000);
            } else {
                showToast(data.message || 'Google login failed', 'error');
            }
        })
        .catch(err => {
            console.error(err);
            showToast('Google login failed. Please try again.', 'error');
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
                    
                    showToast('Google login successful! Welcome to Urban Mart!', 'success');
                    closeUserModal();
                    setTimeout(() => {
                        // Reload current page to reflect authentication state
                        window.location.reload();
                    }, 1000);
                } else {
                    showToast(data.message || 'Google login failed', 'error');
                }
            })
            .catch(err => {
                console.error(err);
                showToast('Google login failed. Please try again.', 'error');
            });
        }
    }

    // =========================
    // WHOLESALER MODAL JS
    // =========================
    function openWholesalerLogin(){ document.getElementById('wholesalerAuthModal').style.display='flex'; switchWholesalerTab('login'); }
    function closeWholesalerModal(){ document.getElementById('wholesalerAuthModal').style.display='none'; }

    function switchWholesalerTab(tab){
        const loginBtn=document.getElementById('wholesalerLoginBtn');
        const registerBtn=document.getElementById('wholesalerRegisterBtn');
        const loginSection=document.getElementById('wholesalerLoginSection');
        const registerSection=document.getElementById('wholesalerRegisterSection');
        if(tab==='login'){ loginBtn.classList.add('active'); registerBtn.classList.remove('active'); loginSection.style.display='block'; registerSection.style.display='none'; document.getElementById('wholesalerModalTitle').textContent='Wholesaler Login'; }
        else{ registerBtn.classList.add('active'); loginBtn.classList.remove('active'); registerSection.style.display='block'; loginSection.style.display='none'; document.getElementById('wholesalerModalTitle').textContent='Wholesaler Registration'; }
    }

    /**
     * Handle wholesaler login form submission
     * @param {Event} event - Form submit event
     */
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
                 // Store authentication data in localStorage for API calls
                 localStorage.setItem('auth_token', data.access_token);
                 localStorage.setItem('user', JSON.stringify(data.user));
                 
                 // Set authentication cookie for Laravel session
                 document.cookie = `auth_token=${data.access_token}; path=/; max-age=86400; SameSite=Lax`;
                 
                 showToast('Wholesaler login successful! Welcome back!', 'success');
                 closeWholesalerModal();
                 setTimeout(() => {
                     // Use Laravel route for dashboard to ensure proper session authentication
                     window.location.href = '{{ route("dashboard") }}';
                 }, 1500);
             } else {
                 showToast(data.message || 'Login failed', 'error');
             }
         })
         .catch(err => {
             console.error(err);
             showToast('Login failed. Please try again.', 'error');
         })
        .finally(() => {
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        });
    }

    /**
     * Handle wholesaler registration form submission
     * @param {Event} event - Form submit event
     */
    function handleWholesalerRegistration(event) {
        event.preventDefault();
        const formData = new FormData(event.target);
        const data = Object.fromEntries(formData);
        
        // Validate password confirmation
         if (data.password !== data.confirmPassword) {
             showToast('Passwords do not match!', 'error');
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
             if (data.result) {
                 showToast('Registration successful! Your account is pending approval. You will be notified once approved.', 'success');
                 closeWholesalerModal();
                 // Switch to login tab
                 setTimeout(() => switchWholesalerTab('login'), 1500);
             } else {
                 showToast(data.message || 'Registration failed', 'error');
             }
         })
         .catch(err => {
             console.error(err);
             showToast('Registration failed. Please try again.', 'error');
         })
        .finally(() => {
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        });
    }

    window.onclick=function(event){ if(event.target===document.getElementById('userAuthModal')) closeUserModal(); if(event.target===document.getElementById('wholesalerAuthModal')) closeWholesalerModal(); }
    document.addEventListener('keydown',function(event){ if(event.key==='Escape'){ closeUserModal(); closeWholesalerModal(); } });
</script>

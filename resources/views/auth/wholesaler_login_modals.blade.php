<!-- Mohammad Hassan -->
<div class="modal fade" id="wholesalerAuthModal" tabindex="-1" role="dialog" aria-labelledby="wholesalerAuthModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <!-- Mohammad Hassan: CSS for separator and Google sign-in button -->
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
                <h5 class="modal-title" id="wholesalerAuthModalLabel">{{ translate('Wholesaler Access') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <!-- Mohammad Hassan: Tab navigation -->
                <ul class="nav nav-tabs mb-4" id="wholesalerAuthTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="wholesaler-login-tab" data-toggle="tab" href="#wholesaler-login" role="tab">
                            {{ translate('Login') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="wholesaler-register-tab" data-toggle="tab" href="#wholesaler-register" role="tab">
                            {{ translate('Register') }}
                        </a>
                    </li>
                </ul>

                <!-- Mohammad Hassan: Tab content -->
                <div class="tab-content" id="wholesalerAuthTabContent">
                    <!-- Login Tab -->
                    <div class="tab-pane fade show active" id="wholesaler-login" role="tabpanel">
                        <form class="form-default" role="form" action="{{ route('login') }}" method="POST">
                            @csrf
                            <input type="hidden" name="user_type" value="wholesaler">
                            <div class="form-group">
                                <input type="email" class="form-control" value="" placeholder="{{ translate('Email') }}" name="email" id="wholesaler_email" autocomplete="off">
                            </div>

                            <div class="form-group">
                                <input type="password" class="form-control" placeholder="{{ translate('Password') }}" name="password" id="wholesaler_password">
                            </div>

                            <div class="row mb-2">
                                <div class="col-6">
                                    <label class="aiz-checkbox">
                                        <input type="checkbox" name="remember">
                                        <span class="opacity-60">{{ translate('Remember Me') }}</span>
                                        <span class="aiz-square-check"></span>
                                    </label>
                                </div>
                                <div class="col-6 text-right">
                                    <a href="{{ route('password.request') }}" class="text-reset opacity-60 hov-opacity-100">{{ translate('Forgot password?')}}</a>
                                </div>
                            </div>

                            <div class="mb-3">
                                <button type="submit" class="btn btn-primary btn-block fw-600">{{ translate('Login')}}</button>
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

                            <!-- Mohammad Hassan -->
                            <div class="text-center mt-2">
                                <small class="text-muted">{{ translate("Don't have a wholesaler account?") }}</small>
                                <br>
                                <a href="javascript:void(0)" onclick="$('#wholesaler-register-tab').tab('show')" class="text-primary">{{ translate('Register Now') }}</a>
                            </div>
                        </form>
                    </div>

                    <!-- Register Tab -->
                    <div class="tab-pane fade" id="wholesaler-register" role="tabpanel">
                        <!-- Mohammad Hassan: Updated form to use API endpoint for wholesaler registration -->
                        <form class="form-default" role="form" id="wholesalerRegisterForm" method="POST">
                            @csrf
                            <input type="hidden" name="user_type" value="wholesaler">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">{{ translate('Business Name') }} <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="name" placeholder="{{ translate('Business Name') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">{{ translate('Trade License Number') }}</label>
                                        <input type="text" class="form-control" name="trade_license" placeholder="{{ translate('Trade License Number') }}">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">{{ translate('Email Address') }} <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" name="email" placeholder="{{ translate('Email Address') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">{{ translate('Phone Number') }} <span class="text-danger">*</span></label>
                                        <input type="tel" class="form-control" name="phone" placeholder="{{ translate('Phone Number') }}" pattern="[0-9]{10,15}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label class="form-label">{{ translate('Business Address') }} <span class="text-danger">*</span></label>
                                        <textarea class="form-control" name="address" rows="3" placeholder="{{ translate('Business Address') }}" required></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">{{ translate('Facebook Link') }}</label>
                                        <input type="url" class="form-control" name="facebook" placeholder="https://facebook.com/yourpage">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">{{ translate('Website Link') }}</label>
                                        <input type="url" class="form-control" name="website" placeholder="https://yourwebsite.com">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="form-label">{{ translate('Password') }} <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" name="password" placeholder="{{ translate('Password') }}" minlength="8" required>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label class="form-label">{{ translate('Confirm Password') }} <span class="text-danger">*</span></label>
                                        <input type="password" class="form-control" name="password_confirmation" placeholder="{{ translate('Confirm Password') }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="aiz-checkbox">
                                    <input type="checkbox" name="terms" required>
                                    <span class="opacity-60">{{ translate('I agree to the') }} <a href="{{ route('terms') }}" target="_blank">{{ translate('Terms and Conditions') }}</a></span>
                                    <span class="aiz-square-check"></span>
                                </label>
                            </div>

                            <div class="mb-3">
                                <!-- Mohammad Hassan: Updated button to handle AJAX submission -->
                                <button type="button" class="btn btn-primary btn-block fw-600" onclick="submitWholesalerRegistration()">{{ translate('Create Account') }}</button>
                            </div>

                            <!-- Mohammad Hassan: Google Registration Button -->
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
                            <div class="text-center mt-2">
                                <small class="text-muted">{{ translate('Already have an account?') }}</small>
                                <br>
                                <a href="javascript:void(0)" onclick="$('#wholesaler-login-tab').tab('show')" class="text-primary">{{ translate('Login here') }}</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Mohammad Hassan
function openWholesalerLogin() {
    $('#wholesalerAuthModal').modal('show');
    $('#wholesaler-login-tab').tab('show');
}

// Mohammad Hassan
function openWholesalerRegister() {
    $('#wholesalerAuthModal').modal('show');
    $('#wholesaler-register-tab').tab('show');
}

// Mohammad Hassan: AJAX function for wholesaler registration
function submitWholesalerRegistration() {
    const form = document.getElementById('wholesalerRegisterForm');
    const formData = new FormData();
    
    // Map form fields to API expected field names
    formData.append('businessName', form.querySelector('[name="name"]').value);
    formData.append('email', form.querySelector('[name="email"]').value);
    formData.append('phone', form.querySelector('[name="phone"]').value);
    formData.append('address', form.querySelector('[name="address"]').value);
    formData.append('password', form.querySelector('[name="password"]').value);
    formData.append('confirmPassword', form.querySelector('[name="password_confirmation"]').value);
    formData.append('facebookLink', form.querySelector('[name="facebook"]').value || '');
    formData.append('websiteLink', form.querySelector('[name="website"]').value || '');
    formData.append('tradeLicense', form.querySelector('[name="trade_license"]').value || '');
    
    // Validate required fields
    const requiredFields = [
        {name: 'businessName', element: form.querySelector('[name="name"]')},
        {name: 'email', element: form.querySelector('[name="email"]')},
        {name: 'phone', element: form.querySelector('[name="phone"]')},
        {name: 'address', element: form.querySelector('[name="address"]')},
        {name: 'password', element: form.querySelector('[name="password"]')},
        {name: 'confirmPassword', element: form.querySelector('[name="password_confirmation"]')}
    ];
    
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.element.value.trim()) {
            isValid = false;
            field.element.classList.add('is-invalid');
        } else {
            field.element.classList.remove('is-invalid');
        }
    });
    
    // Check if terms checkbox is checked
    const termsCheckbox = form.querySelector('[name="terms"]');
    if (!termsCheckbox.checked) {
        isValid = false;
        termsCheckbox.closest('.aiz-checkbox').classList.add('text-danger');
    } else {
        termsCheckbox.closest('.aiz-checkbox').classList.remove('text-danger');
    }
    
    // Check password confirmation
    const password = form.querySelector('[name="password"]').value;
    const passwordConfirmation = form.querySelector('[name="password_confirmation"]').value;
    if (password !== passwordConfirmation) {
        isValid = false;
        form.querySelector('[name="password_confirmation"]').classList.add('is-invalid');
        alert('{{ translate("Passwords do not match") }}');
        return;
    }
    
    if (!isValid) {
        alert('{{ translate("Please fill in all required fields") }}');
        return;
    }
    
    // Show loading state
    const submitBtn = document.querySelector('#wholesalerRegisterForm button[onclick="submitWholesalerRegistration()"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '{{ translate("Creating Account...") }}';
    submitBtn.disabled = true;
    
    // Submit via AJAX to API endpoint
    fetch('/api/v2/auth/wholesaler-register', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.result) {
            alert('{{ translate("Registration successful! Your account is pending approval.") }}');
            $('#wholesalerAuthModal').modal('hide');
            form.reset();
            // Redirect to login or refresh page
            window.location.reload();
        } else {
            if (typeof data.message === 'object') {
                // Handle validation errors
                let errorMessage = '{{ translate("Please fix the following errors:") }}\n';
                Object.keys(data.message).forEach(key => {
                    errorMessage += '- ' + data.message[key].join(', ') + '\n';
                });
                alert(errorMessage);
            } else {
                alert(data.message || '{{ translate("Registration failed. Please try again.") }}');
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('{{ translate("An error occurred. Please try again.") }}');
    })
    .finally(() => {
        // Reset button state
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
}
</script>
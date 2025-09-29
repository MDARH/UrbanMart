{{-- Mohammad Hassan --}}
<div class="modal fade" id="user_type_modal" tabindex="-1" role="dialog" aria-labelledby="userTypeModalLabel"
        aria-hidden="true">
    <div class="modal-dialog modal-dialog-zoom" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title fw-600">{{ translate('Select Account Type') }}</h6>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="p-3">
                    <div class="text-center mb-4">
                        <p class="text-muted">{{ translate('Please select your account type to continue') }}</p>
                    </div>
                    
{{-- Mohammad Hassan --}}
{{-- Include reusable login cards with custom function names --}}
@include('frontend.partials.login_cards', [
    // Mohammad Hassan
    'userLoginFunction' => 'triggerCustomerLogin',
    'wholesalerLoginFunction' => 'triggerWholesalerLogin'
])
                    
                    <div class="text-center mt-3">
                        <small class="text-muted">
                            {{ translate('Don\'t have an account?') }} 
                            <a href="{{ route('user.registration') }}" class="text-primary">{{ translate('Register here') }}</a>
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Mohammad Hassan
    function showUserTypeModal() {
        $('#user_type_modal').modal('show');
    }
    
    function showLoginOptions() {
        showUserTypeModal();
    }
    
    // Mohammad Hassan
    function triggerCustomerLogin() {
        $('#user_type_modal').modal('hide');
        setTimeout(function() {
            if (typeof openCustomerLogin === 'function') {
                openCustomerLogin();
            } else {
                $('#customerAuthModal').modal('show');
            }
        }, 300);
    }
    
    // Mohammad Hassan
    function triggerWholesalerLogin() {
        $('#user_type_modal').modal('hide');
        setTimeout(function() {
            $('#wholesalerAuthModal').modal('show');
        }, 300);
    }
</script>
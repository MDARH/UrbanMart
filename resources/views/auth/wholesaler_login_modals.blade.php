<!-- Mohammad Hassan -->
<div class="modal fade" id="wholesalerAuthModal" tabindex="-1" role="dialog" aria-labelledby="wholesalerAuthModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="wholesalerAuthModalLabel">{{ translate('Wholesaler Login') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
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

                    <!-- Mohammad Hassan -->
                    <div class="text-center mt-2">
                        <small class="text-muted">{{ translate("Don't have a wholesaler account?") }}</small>
                        <br>
                        <a href="{{ route('user.registration') }}" class="text-primary">{{ translate('Register Now') }}</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function openWholesalerLogin() {
    // Mohammad Hassan
    $('#wholesalerAuthModal').modal('show');
}
</script>
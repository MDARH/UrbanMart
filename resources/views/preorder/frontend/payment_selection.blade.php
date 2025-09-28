@extends('frontend.layouts.app')

@section('content')
<section class="pt-5 mb-4">
    <div class="container">
        <div class="row">
            <div class="col-xl-8 mx-auto">
                <div class="row aiz-steps arrow-divider">
                    <div class="col done">
                        <div class="text-center text-success">
                            <i class="la-3x mb-2 las la-shopping-cart"></i>
                            <h3 class="fs-14 fw-600 d-none d-lg-block">{{ translate('1. My Cart') }}</h3>
                        </div>
                    </div>
                    <div class="col done">
                        <div class="text-center text-success">
                            <i class="la-3x mb-2 las la-map"></i>
                            <h3 class="fs-14 fw-600 d-none d-lg-block">{{ translate('2. Shipping info') }}</h3>
                        </div>
                    </div>
                    <div class="col active">
                        <div class="text-center text-primary">
                            <i class="la-3x mb-2 las la-truck"></i>
                            <h3 class="fs-14 fw-600 d-none d-lg-block">{{ translate('3. Pre-order Payment') }}</h3>
                        </div>
                    </div>
                    <div class="col">
                        <div class="text-center">
                            <i class="la-3x mb-2 opacity-50 las la-credit-card"></i>
                            <h3 class="fs-14 fw-600 d-none d-lg-block opacity-50">{{ translate('4. Confirmation') }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Mohammad Hassan -->
<section class="mb-4">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 rounded">
                    <div class="card-header">
                        <h3 class="fs-16 fw-600 mb-0">{{ translate('Pre-order Payment Information') }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="alert alert-info">
                            <i class="las la-info-circle"></i>
                            {{ translate('You are placing a pre-order for out-of-stock products. You will pay 50% now and the remaining 50% when the products arrive.') }}
                        </div>

                        @if(session('preorder_ids') && count(session('preorder_ids')) > 0)
                            @php
                                $preorders = \App\Models\Preorder::whereIn('id', session('preorder_ids'))->get();
                            @endphp
                            
                            <div class="preorder-items mb-4">
                                <h5 class="mb-3">{{ translate('Pre-order Items') }}</h5>
                                @foreach($preorders as $preorder)
                                    @php
                                        $product = $preorder->product;
                                    @endphp
                                    <div class="row align-items-center border-bottom py-3">
                                        <div class="col-md-2">
                                            <img src="{{ uploaded_asset($product->thumbnail_img) }}" 
                                                 class="img-fluid rounded" 
                                                 style="height: 60px; object-fit: cover;">
                                        </div>
                                        <div class="col-md-6">
                                            <h6 class="mb-1">{{ $product->getTranslation('name') }}</h6>
                                            <small class="text-muted">{{ translate('Quantity') }}: {{ $preorder->quantity }}</small>
                                        </div>
                                        <div class="col-md-4 text-right">
                                            <div class="text-muted small">{{ translate('Unit Price') }}: {{ single_price($preorder->unit_price) }}</div>
                                            <div class="fw-600">{{ translate('Pre-payment') }}: {{ single_price($preorder->prepayment) }}</div>
                                            <div class="text-muted small">{{ translate('Remaining') }}: {{ single_price($preorder->getRemainingAmount()) }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        <form action="{{ route('preorder.process_payment') }}" method="POST" id="preorder-payment-form">
                            @csrf
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <h5 class="mb-3">{{ translate('Select Payment Method') }}</h5>
                                    
                                    {{-- Mohammad Hassan - Hide Cash on Delivery for all preorder products --}}
                                    {{-- COD is not available for preorder products as advance payment is required --}}

                                    @if(get_setting('paypal_payment_activation') == 1)
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="radio" name="payment_option" 
                                                   id="paypal" value="paypal">
                                            <label class="form-check-label fw-600" for="paypal">
                                                {{ translate('PayPal') }}
                                            </label>
                                        </div>
                                    @endif

                                    @if(get_setting('stripe_payment_activation') == 1)
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="radio" name="payment_option" 
                                                   id="stripe" value="stripe">
                                            <label class="form-check-label fw-600" for="stripe">
                                                {{ translate('Stripe') }}
                                            </label>
                                        </div>
                                    @endif

                                    @if(get_setting('sslcommerz_payment_activation') == 1)
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="radio" name="payment_option" 
                                                   id="sslcommerz" value="sslcommerz">
                                            <label class="form-check-label fw-600" for="sslcommerz">
                                                {{ translate('SSLCommerz') }}
                                            </label>
                                        </div>
                                    @endif

                                    @if(get_setting('razorpay_payment_activation') == 1)
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="radio" name="payment_option" 
                                                   id="razorpay" value="razorpay">
                                            <label class="form-check-label fw-600" for="razorpay">
                                                {{ translate('Razorpay') }}
                                            </label>
                                        </div>
                                    @endif

                                    @if(get_setting('paystack_payment_activation') == 1)
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="radio" name="payment_option" 
                                                   id="paystack" value="paystack">
                                            <label class="form-check-label fw-600" for="paystack">
                                                {{ translate('Paystack') }}
                                            </label>
                                        </div>
                                    @endif

                                    @if(get_setting('bkash_payment_activation') == 1)
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="radio" name="payment_option" 
                                                   id="bkash" value="bkash">
                                            <label class="form-check-label fw-600" for="bkash">
                                                {{ translate('bKash') }}
                                            </label>
                                        </div>
                                    @endif

                                    @if(get_setting('nagad_payment_activation') == 1)
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="radio" name="payment_option" 
                                                   id="nagad" value="nagad">
                                            <label class="form-check-label fw-600" for="nagad">
                                                {{ translate('Nagad') }}
                                            </label>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-6">
                                    <a href="{{ route('checkout.shipping_info') }}" class="btn btn-outline-primary btn-block">
                                        <i class="las la-arrow-left"></i> {{ translate('Back to Shipping') }}
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <button type="submit" class="btn btn-primary btn-block">
                                        {{ translate('Proceed to Payment') }} <i class="las la-arrow-right"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card shadow-sm border-0 rounded">
                    <div class="card-header">
                        <h3 class="fs-16 fw-600 mb-0">{{ translate('Pre-order Summary') }}</h3>
                    </div>
                    <div class="card-body">
                        @if(session('preorder_total'))
                            {{-- Mohammad Hassan - Display advance payment and due amount details --}}
                            @php
                                $advance_amount = session('preorder_total');
                                $due_amount = session('preorder_total'); // Since it's 50% each
                                $total_order_value = $advance_amount + $due_amount;
                            @endphp
                            
                            <div class="d-flex justify-content-between mb-2">
                                <span>{{ translate('Total Order Value') }}:</span>
                                <span class="fw-600">{{ single_price($total_order_value) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2 text-success">
                                <span>{{ translate('Advance Payment (50%)') }}:</span>
                                <span class="fw-600">{{ single_price($advance_amount) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2 text-warning">
                                <span>{{ translate('Due on Delivery (50%)') }}:</span>
                                <span class="fw-600">{{ single_price($due_amount) }}</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between mb-0">
                                <span class="fs-16 fw-600">{{ translate('Pay Now') }}:</span>
                                <span class="fs-16 fw-600 text-primary">{{ single_price($advance_amount) }}</span>
                            </div>
                            <small class="text-muted">{{ translate('You will pay') }} {{ single_price($due_amount) }} {{ translate('when products are delivered') }}</small>
                        @endif
                    </div>
                </div>

                <div class="card shadow-sm border-0 rounded mt-3">
                    <div class="card-body">
                        <h6 class="fw-600 mb-3">{{ translate('Pre-order Process') }}</h6>
                        <div class="d-flex align-items-start mb-3">
                            <div class="badge badge-primary rounded-circle mr-3">1</div>
                            <div>
                                <div class="fw-600">{{ translate('Pay 50% Now') }}</div>
                                <small class="text-muted">{{ translate('Secure your pre-order with partial payment') }}</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-start mb-3">
                            <div class="badge badge-secondary rounded-circle mr-3">2</div>
                            <div>
                                <div class="fw-600">{{ translate('Wait for Arrival') }}</div>
                                <small class="text-muted">{{ translate('We will notify you when products arrive') }}</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-start">
                            <div class="badge badge-success rounded-circle mr-3">3</div>
                            <div>
                                <div class="fw-600">{{ translate('Complete Payment') }}</div>
                                <small class="text-muted">{{ translate('Pay remaining 50% and receive your products') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.getElementById('preorder-payment-form').addEventListener('submit', function(e) {
    const selectedPayment = document.querySelector('input[name="payment_option"]:checked');
    if (!selectedPayment) {
        e.preventDefault();
        alert('{{ translate("Please select a payment method") }}');
        return false;
    }
});
</script>
@endsection
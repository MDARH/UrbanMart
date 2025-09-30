<div class="z-3 sticky-top-lg">
    <div class="card rounded-0 border">

        @php
            $subtotal_for_min_order_amount = 0;
            $subtotal = 0;
            $tax = 0;
            $product_shipping_cost = 0;
            $shipping = 0;
            $coupon_code = null;
            $coupon_discount = 0;
            $total_point = 0;
            // Mohammad Hassan
            $has_preorder_products = false;
            $preorder_total = 0;
            $preorder_advance_payment = 0;
            $preorder_due_amount = 0;
            $shipping_info = session('shipping_info');
        @endphp

        @foreach ($carts as $key => $cartItem)
            @php
                $product = get_single_product($cartItem['product_id']);
                $subtotal_for_min_order_amount += cart_product_price($cartItem, $cartItem->product, false, false) * $cartItem['quantity'];
                $subtotal += cart_product_price($cartItem, $product, false, false) * $cartItem['quantity'];
                $tax += cart_product_tax($cartItem, $product, false) * $cartItem['quantity'];
                $product_shipping_cost = $cartItem['shipping_cost'];
                $shipping += $product_shipping_cost;
                if ((get_setting('coupon_system') == 1) && ($cartItem->coupon_applied == 1)) {
                    $coupon_code = $cartItem->coupon_code;
                    $coupon_discount = $carts->sum('discount');
                }
                if (addon_is_activated('club_point')) {
                    $total_point += $product->earn_point * $cartItem['quantity'];
                }
                
                // Mohammad Hassan
                // Check if product is out of stock and can be preordered
                if ($product && $product->isOutOfStock() && $product->isPreorderAvailable()) {
                    // Show preorder details regardless of system activation for testing
                    $has_preorder_products = true;
                    $item_total = $product->unit_price * $cartItem['quantity'];
                    $preorder_total += $item_total;
                    $preorder_advance_payment += $item_total * 0.5; // 50% advance payment
                    $preorder_due_amount += $item_total * 0.5; // 50% due on delivery
                }
            @endphp
        @endforeach

        <!-- Mohammad Hassan - Debug Information Hidden -->

        <div class="card-header pt-4 pb-1 border-bottom-0">
            <h3 class="fs-16 fw-700 mb-0">{{ translate('Order Summary') }}</h3>
            <div class="text-right">
                <!-- Minimum Order Amount -->
                @if (get_setting('minimum_order_amount_check') == 1 && $subtotal_for_min_order_amount < get_setting('minimum_order_amount'))
                    <span class="text-warning fs-12 px-2 border border-warning rounded">
                        {{ translate('Minimum Order Amount') . ' ' . single_price(get_setting('minimum_order_amount')) }}
                    </span>
                @endif
            </div>
        </div>

        <div class="card-body pt-2">

            <div class="row gutters-5">
                <!-- Total Products -->
                <div class="@if (addon_is_activated('club_point')) col-6 @else col-12 @endif">
                    <div class="d-flex align-items-center justify-content-between bg-primary p-2">
                        <span class="fs-13 text-white">{{ translate('Total Products') }}</span>
                        <span class="fs-13 fw-700 text-white">{{ sprintf("%02d", count($carts)) }}</span>
                    </div>
                </div>
                @if (addon_is_activated('club_point'))
                    <!-- Total Clubpoint -->
                    <div class="col-6">
                        <div class="d-flex align-items-center justify-content-between bg-secondary-base p-2">
                            <span class="fs-13 text-white">{{ translate('Total Clubpoint') }}</span>
                            <span class="fs-13 fw-700 text-white">{{ sprintf("%02d", $total_point) }}</span>
                        </div>
                    </div>
                @endif
            </div>

            <input type="hidden" id="sub_total" value="{{ $subtotal }}">

            <table class="table my-3">
                <tfoot>
                    <!-- Subtotal -->
                    <tr class="cart-subtotal">
                        <th class="pl-0 fs-14 fw-400 pt-0 pb-2 text-dark border-top-0">{{ translate('Subtotal') }} ({{ sprintf("%02d", count($carts)) }} {{ translate('Products') }})</th>
                        <td class="text-right pr-0 fs-14 pt-0 pb-2 text-dark border-top-0" id="cart-subtotal-amount">{{ single_price($subtotal) }}</td>
                    </tr>
                    <!-- Tax -->
                    <tr class="cart-tax">
                        <th class="pl-0 fs-14 fw-400 pt-0 pb-2 text-dark border-top-0">{{ translate('Tax') }}</th>
                        <td class="text-right pr-0 fs-14 pt-0 pb-2 text-dark border-top-0">{{ single_price($tax) }}</td>
                    </tr>
                    @if ($proceed != 1)
                    <!-- Total Shipping -->
                    <tr class="cart-shipping">
                        <th class="pl-0 fs-14 fw-400 pt-0 pb-2 text-dark border-top-0">{{ translate('Total Shipping') }}</th>
                        <td class="text-right pr-0 fs-14 pt-0 pb-2 text-dark border-top-0">{{ single_price($shipping) }}</td>
                    </tr>
                    @endif
                    <!-- Redeem point -->
                    @if (Session::has('club_point'))
                        <tr class="cart-club-point">
                            <th class="pl-0 fs-14 fw-400 pt-0 pb-2 text-dark border-top-0">{{ translate('Redeem point') }}</th>
                            <td class="text-right pr-0 fs-14 pt-0 pb-2 text-dark border-top-0">{{ single_price(Session::get('club_point')) }}</td>
                        </tr>
                    @endif
                    <!-- Coupon Discount -->
                    @if ($coupon_discount > 0)
                        <tr class="cart-coupon-discount">
                            <th class="pl-0 fs-14 fw-400 pt-0 pb-2 text-dark border-top-0">{{ translate('Coupon Discount') }}</th>
                            <td class="text-right pr-0 fs-14 pt-0 pb-2 text-dark border-top-0">{{ single_price($coupon_discount) }}</td>
                        </tr>
                    @endif

                    @php
                        $total = $subtotal + $tax + $shipping;
                        if (Session::has('club_point')) {
                            $total -= Session::get('club_point');
                        }
                        if ($coupon_discount > 0) {
                            $total -= $coupon_discount;
                        }
                    @endphp
                    
                    @if($has_preorder_products)
                        <!-- Mohammad Hassan -->
                        <!-- Preorder Payment Breakdown -->
                        <tr class="preorder-breakdown-header">
                            <th colspan="2" class="pl-0 fs-14 fw-700 text-primary border-top pt-3 text-uppercase">{{ translate('Pre-order Payment Details') }}</th>
                        </tr>
                        <tr class="preorder-total-value">
                            <th class="pl-0 fs-14 fw-400 pt-2 pb-2 text-dark border-top-0">{{ translate('Total Order Value') }}</th>
                            <td class="text-right pr-0 fs-14 pt-2 pb-2 text-dark border-top-0">{{ single_price($preorder_total) }}</td>
                        </tr>
                        <tr class="preorder-advance-payment">
                            <th class="pl-0 fs-14 fw-400 pt-0 pb-2 text-dark border-top-0">{{ translate('Advance Payment (50%)') }}</th>
                            <td class="text-right pr-0 fs-14 pt-0 pb-2 text-primary border-top-0 fw-700">{{ single_price($preorder_advance_payment) }}</td>
                        </tr>
                        <tr class="preorder-due-amount">
                            <th class="pl-0 fs-14 fw-400 pt-0 pb-2 text-dark border-top-0">{{ translate('Due on Delivery (50%)') }}</th>
                            <td class="text-right pr-0 fs-14 pt-0 pb-2 text-muted border-top-0">{{ single_price($preorder_due_amount) }}</td>
                        </tr>
                        <tr class="preorder-pay-now">
                            <th class="pl-0 fs-14 text-dark fw-700 border-top-0 pt-3 text-uppercase">{{ translate('Pay Now') }}</th>
                            <td class="text-right pr-0 fs-16 fw-700 text-primary border-top-0 pt-3">{{ single_price($preorder_advance_payment) }}</td>
                        </tr>
                        <tr>
                            <td colspan="2" class="pl-0 pr-0 pt-2 pb-0 border-top-0">
                                <small class="text-muted">{{ translate('You will pay the remaining amount upon delivery') }}</small>
                            </td>
                        </tr>
                    @else
                        <!-- Mohammad Hassan -->
                        <!-- Regular Total -->
                        <tr class="cart-total">
                            <th class="pl-0 fs-14 text-dark fw-700 border-top-0 pt-3 text-uppercase">{{ translate('Total') }}</th>
                            <td class="text-right pr-0 fs-16 fw-700 text-primary border-top-0 pt-3" id="cart-total-amount">{{ single_price($total) }}</td>
                        </tr>
                    @endif

                </tfoot>
            </table>

            <!-- Coupon System -->
            @if (get_setting('coupon_system') == 1)
                @if ($coupon_discount > 0 && $coupon_code)
                    <div class="mt-3">
                        <form class="" id="remove-coupon-form" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="proceed" value="{{ $proceed }}">
                            <div class="input-group">
                                <div class="form-control">{{ $coupon_code }}</div>
                                <div class="input-group-append">
                                    <button type="button" id="coupon-remove"
                                        class="btn btn-primary">{{ translate('Change Coupon') }}</button>
                                </div>
                            </div>
                        </form>
                    </div>
                @else
                    <div class="mt-3">
                        <form class="" id="apply-coupon-form" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="proceed" value="{{ $proceed }}">
                            <div class="input-group">
                                <input type="text" class="form-control rounded-0" name="code"
                                    onkeydown="return event.key != 'Enter';"
                                    placeholder="{{ translate('Have coupon code? Apply here') }}" required>
                                <div class="input-group-append">
                                    <button type="button" id="coupon-apply"
                                        class="btn btn-primary rounded-0">{{ translate('Apply') }}</button>
                                </div>
                            </div>
                            @if (!auth()->check())
                                <small>{{ translate('You must Login as customer to apply coupon') }}</small>
                            @endif

                        </form>
                    </div>
                @endif
            @endif

            @if ($proceed == 1)
            <!-- Continue to Shipping -->
            <div class="mt-4">
                {{-- Mohammad Hassan --}}
                @if(Auth::check())
                    <a href="{{ route('checkout') }}" class="btn btn-primary btn-block fs-14 fw-700 rounded-0 px-4">
                        {{ translate('Proceed to Checkout')}} ({{ sprintf("%02d", count($carts)) }})
                    </a>
                @else
                    <button class="btn btn-primary btn-block fs-14 fw-700 rounded-0 px-4" onclick="showLoginOptions()">
                        {{ translate('Proceed to Checkout')}} ({{ sprintf("%02d", count($carts)) }})
                    </button>
                @endif
            </div>
            @endif

        </div>
    </div>
</div>

{{-- Mohammad Hassan --}}
@php
$physical = false;
$subtotal = 0;
foreach ($products as $key => $cartItem){
    $product = get_single_product($cartItem);
    if ($product->digital == 0) {
        $physical = true;
    }
}
@endphp

<!-- Order Details Table -->
<div class="mb-4">
    <h6 class="fs-16 fw-700 mb-3">{{ translate('Order Details') }}</h6>
        <div class="table-responsive">
            <table class="table table-borderless">
                <thead class="bg-light">
                    <tr>
                        <th class="border-0 fs-14 fw-600">{{ translate('Product') }}</th>
                        <th class="border-0 fs-14 fw-600 text-center">{{ translate('Unit Price') }}</th>
                        <th class="border-0 fs-14 fw-600 text-center">{{ translate('Qty') }}</th>
                        <th class="border-0 fs-14 fw-600 text-right">{{ translate('Total') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $key => $cartItem)
                    @php
                        $product = get_single_product($cartItem);
                        $cart_item = collect($carts)->firstWhere('product_id', $cartItem);
                        
                        // Mohammad Hassan - Calculate unit price and total
                        $unit_price = $cart_item['price'] ?? $product->unit_price;
                        $quantity = $cart_item['quantity'] ?? 1;
                        $total_price = $unit_price * $quantity;
                        $subtotal += $total_price;
                        
                        // Mohammad Hassan - Build product name with variant
                        $product_name_with_choice = $product->getTranslation('name');
                        if (isset($cart_item['variant_name']) && !empty($cart_item['variant_name'])) {
                            $product_name_with_choice .= ' - ' . $cart_item['variant_name'];
                        }
                        if ($product_variation[$key] != '') {
                            $product_name_with_choice .= ' (' . $product_variation[$key] . ')';
                        }
                    @endphp
                    <tr>
                        <td class="border-0 py-3">
                            <div class="d-flex align-items-center">
                                <div class="mr-3">
                                    <img src="{{ get_image($product->thumbnail) }}"
                                        class="img-fit size-50px rounded"
                                        alt="{{ $product->getTranslation('name') }}"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                </div>
                                <div>
                                    <span class="fs-14 fw-500 text-dark d-block">{{ $product_name_with_choice }}</span>
                                    {{-- Mohammad Hassan - Wholesale tiered discount info --}}
                                    @if (auth()->check() && auth()->user()->user_type == 'wholesaler' && 
                                         isset($cart_item['price_tier_min_qty']) && $cart_item['price_tier_min_qty'] > 0)
                                        <small class="text-success">
                                            <i class="las la-tag"></i>
                                            {{ translate('Tier Price') }}: {{ single_price($cart_item['tier_price']) }}
                                            ({{ translate('Min Qty') }}: {{ $cart_item['price_tier_min_qty'] }})
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="border-0 py-3 text-center">
                            <span class="fs-14 fw-600">{{ single_price($unit_price) }}</span>
                        </td>
                        <td class="border-0 py-3 text-center">
                            {{-- Mohammad Hassan - Quantity controls --}}
                            <div class="d-flex align-items-center justify-content-center">
                                <button type="button" class="btn btn-sm btn-outline-secondary quantity-btn-minus" 
                                        data-product-id="{{ $cartItem }}" 
                                        data-unit-price="{{ $unit_price }}"
                                        style="width: 30px; height: 30px; padding: 0; border-radius: 50%;">
                                    <i class="las la-minus"></i>
                                </button>
                                <input type="number" class="form-control text-center quantity-input mx-2" 
                                       value="{{ $quantity }}" 
                                       min="1" 
                                       max="999"
                                       data-product-id="{{ $cartItem }}"
                                       data-unit-price="{{ $unit_price }}"
                                       style="width: 60px; height: 30px; padding: 0; font-size: 14px;">
                                <button type="button" class="btn btn-sm btn-outline-secondary quantity-btn-plus" 
                                        data-product-id="{{ $cartItem }}" 
                                        data-unit-price="{{ $unit_price }}"
                                        style="width: 30px; height: 30px; padding: 0; border-radius: 50%;">
                                    <i class="las la-plus"></i>
                                </button>
                            </div>
                        </td>
                        <td class="border-0 py-3 text-right">
                            <span class="fs-14 fw-700 text-primary item-total" data-product-id="{{ $cartItem }}">{{ single_price($total_price) }}</span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="bg-light">
                        <td colspan="3" class="border-0 py-3 fs-14 fw-700">{{ translate('Subtotal') }}</td>
                        <td class="border-0 py-3 text-right fs-16 fw-700 text-primary" id="order-details-subtotal">{{ single_price($subtotal) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
</div>

{{-- Mohammad Hassan - JavaScript for quantity controls and price updates --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Function to format price
    function formatPrice(amount) {
        return '৳' + parseFloat(amount).toFixed(2);
    }
    
    // Function to update item total and subtotal
    function updatePrices() {
        let subtotal = 0;
        
        // Update each item total
        document.querySelectorAll('.quantity-input').forEach(function(input) {
            const quantity = parseInt(input.value) || 1;
            const unitPrice = parseFloat(input.dataset.unitPrice) || 0;
            const productId = input.dataset.productId;
            const itemTotal = quantity * unitPrice;
            
            // Update item total display
            const totalElement = document.querySelector('.item-total[data-product-id="' + productId + '"]');
            if (totalElement) {
                totalElement.textContent = formatPrice(itemTotal);
            }
            
            subtotal += itemTotal;
        });
        
        // Update subtotal in order details
        const subtotalElement = document.getElementById('order-details-subtotal');
        if (subtotalElement) {
            subtotalElement.textContent = formatPrice(subtotal);
        }
        
        // Update subtotal in order summary (cart summary)
        const cartSubtotalElement = document.getElementById('cart-subtotal-amount');
        if (cartSubtotalElement) {
            cartSubtotalElement.textContent = formatPrice(subtotal);
        }
        
        // Update total in order summary
        updateOrderSummaryTotal(subtotal);
    }
    
    // Function to update order summary total
    function updateOrderSummaryTotal(subtotal) {
        // Get tax and shipping values
        const taxElement = document.querySelector('.cart-tax td:last-child');
        const shippingElement = document.querySelector('.cart-shipping td:last-child');
        
        let tax = 0;
        let shipping = 0;
        
        if (taxElement) {
            const taxText = taxElement.textContent.replace(/[^\d.]/g, '');
            tax = parseFloat(taxText) || 0;
        }
        
        if (shippingElement) {
            const shippingText = shippingElement.textContent.replace(/[^\d.]/g, '');
            shipping = parseFloat(shippingText) || 0;
        }
        
        const total = subtotal + tax + shipping;
        
        // Update total in cart summary
        const totalElement = document.querySelector('#cart-total-amount');
        if (totalElement) {
            totalElement.textContent = formatPrice(total);
        }
    }
    
    // Plus button click handler
    document.addEventListener('click', function(e) {
        if (e.target.closest('.quantity-btn-plus')) {
            const button = e.target.closest('.quantity-btn-plus');
            const productId = button.dataset.productId;
            const input = document.querySelector('.quantity-input[data-product-id="' + productId + '"]');
            
            if (input) {
                let currentValue = parseInt(input.value) || 1;
                const maxValue = parseInt(input.getAttribute('max')) || 999;
                
                if (currentValue < maxValue) {
                    input.value = currentValue + 1;
                    updatePrices();
                }
            }
        }
    });
    
    // Minus button click handler
    document.addEventListener('click', function(e) {
        if (e.target.closest('.quantity-btn-minus')) {
            const button = e.target.closest('.quantity-btn-minus');
            const productId = button.dataset.productId;
            const input = document.querySelector('.quantity-input[data-product-id="' + productId + '"]');
            
            if (input) {
                let currentValue = parseInt(input.value) || 1;
                const minValue = parseInt(input.getAttribute('min')) || 1;
                
                if (currentValue > minValue) {
                    input.value = currentValue - 1;
                    updatePrices();
                }
            }
        }
    });
    
    // Input change handler
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('quantity-input')) {
            const input = e.target;
            let value = parseInt(input.value) || 1;
            const minValue = parseInt(input.getAttribute('min')) || 1;
            const maxValue = parseInt(input.getAttribute('max')) || 999;
            
            // Ensure value is within bounds
            if (value < minValue) {
                value = minValue;
                input.value = value;
            } else if (value > maxValue) {
                value = maxValue;
                input.value = value;
            }
            
            updatePrices();
        }
    });
});
</script>

@if ($physical)
{{-- Mohammad Hassan - Separate Delivery Type Section --}}
<div class="mb-4">
    <h6 class="fs-16 fw-700 mb-3">{{ translate('Choose Delivery Type') }}</h6>
    <div class="row gutters-16">
            <!-- Home Delivery -->
            @if (get_setting('shipping_type') != 'carrier_wise_shipping')
            <div class="col-md-6">
                <label class="aiz-megabox d-block bg-white mb-0">
                    <input
                        type="radio"
                        name="shipping_type_{{ $owner_id }}"
                        value="home_delivery"
                        onchange="show_pickup_point(this, {{ $owner_id }})"
                        data-target=".pickup_point_id_{{ $owner_id }}"
                        checked required>
                    <span class="d-flex aiz-megabox-elem rounded-0" style="padding: 0.75rem 1.2rem;">
                        <span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                        <span class="flex-grow-1 pl-3 fw-600">{{ translate('Home Delivery') }}</span>
                    </span>
                </label>
            </div>
            <!-- Carrier -->
            @else
            <div class="col-md-6">
                <label class="aiz-megabox d-block bg-white mb-0">
                    <input
                        type="radio"
                        name="shipping_type_{{ $owner_id }}"
                        value="carrier"
                        class="shipping-type-radio"
                        data-owner="{{ $owner_id }}"
                        onchange="show_pickup_point(this, {{ $owner_id }})"
                        data-target=".pickup_point_id_{{ $owner_id }}"
                        checked required>
                    <span class="d-flex aiz-megabox-elem rounded-0" style="padding: 0.75rem 1.2rem;">
                        <span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                        <span class="flex-grow-1 pl-3 fw-600">{{ translate('Carrier') }}</span>
                    </span>
                </label>
            </div>
            @endif
            <!-- Local Pickup -->
            @if ($pickup_point_list)
            <div class="col-md-6">
                <label class="aiz-megabox d-block bg-white mb-0">
                    <input
                        type="radio"
                        name="shipping_type_{{ $owner_id }}"
                        value="pickup_point"
                        class="shipping-type-radio"
                        data-owner="{{ $owner_id }}"
                        onchange="show_pickup_point(this, {{ $owner_id }})"
                        data-target=".pickup_point_id_{{ $owner_id }}"
                        required>
                    <span class="d-flex aiz-megabox-elem rounded-0" style="padding: 0.75rem 1.2rem;">
                        <span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                        <span class="flex-grow-1 pl-3 fw-600">{{ translate('Local Pickup') }}</span>
                    </span>
                </label>
            </div>
            @endif
        </div>

        <!-- Pickup Point List -->
        @if ($pickup_point_list)
        <div class="mt-3 pickup_point_id_{{ $owner_id }} d-none">
            <select
                class="form-control aiz-selectpicker rounded-0"
                name="pickup_point_id_{{ $owner_id }}"
                data-live-search="true"
                onchange="updateDeliveryInfo('pickup_point', this.value, {{ $owner_id }})">
                <option value="">{{ translate('Select your nearest pickup point')}}</option>
                @foreach ($pickup_point_list as $pick_up_point)
                <option
                    value="{{ $pick_up_point->id }}"
                    data-content="<span class='d-block'>
                                                <span class='d-block fs-16 fw-600 mb-2'>{{ $pick_up_point->getTranslation('name') }}</span>
                                                <span class='d-block opacity-50 fs-12'><i class='las la-map-marker'></i> {{ $pick_up_point->getTranslation('address') }}</span>
                                                <span class='d-block opacity-50 fs-12'><i class='las la-phone'></i>{{ $pick_up_point->phone }}</span>
                                            </span>">
                </option>
                @endforeach
            </select>
        </div>
        @endif

        <!-- Carrier Wise Shipping -->
        @if (get_setting('shipping_type') == 'carrier_wise_shipping')
        <div class="row pt-3 carrier_id_{{ $owner_id }}">
            @if($carrier_list->isEmpty())
            <div class="col-md-12">
                <div class="alert alert-danger col-md-12 mb-2">
                    <strong>{{ translate('Shipping is not available to your selected address.') }}</strong><br>
                    {{ translate('Please choose a different address.') }}
                </div>
                <span class="shipping-unavailable-flag" style="display: none;"></span>
            </div>
            @else
            @foreach($carrier_list as $carrier_key => $carrier)
            <div class="col-md-12 mb-2">
                <label class="aiz-megabox d-block bg-white mb-0">
                    <input
                        type="radio"
                        name="carrier_id_{{ $owner_id }}"
                        value="{{ $carrier->id }}"
                        @if($carrier_key==0) checked @endif
                        onchange="updateDeliveryInfo('carrier', {{ $carrier->id }}, {{ $owner_id }})">
                    <span class="d-flex flex-wrap p-3 aiz-megabox-elem rounded-0">
                        <span class="aiz-rounded-check flex-shrink-0 mt-1"></span>
                        <span class="flex-grow-1 pl-3 fw-600">
                            <img src="{{ uploaded_asset($carrier->logo)}}" alt="Image" class="w-50px img-fit">
                        </span>
                        <span class="flex-grow-1 pl-3 fw-700">{{ $carrier->name }}</span>
                        <span class="flex-grow-1 pl-3 fw-600">{{ translate('Transit in').' '.$carrier->transit_time }}</span>
                        <span class="flex-grow-1 pl-4 pl-sm-3 fw-600 mt-2 mt-sm-0 text-sm-right">{{ single_price(carrier_base_price($carts, $carrier->id, $owner_id, $shipping_info)) }}</span>
                    </span>
                </label>
            </div>
            @endforeach
            @endif
        </div>
        @endif
    </div>
@endif
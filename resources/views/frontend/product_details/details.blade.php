{{-- Finds the attribute ID for 'Size' to be used in JavaScript
@php
    $size_attribute_id = null;
    if ($detailedProduct->choice_options != null) {
        foreach (json_decode($detailedProduct->choice_options) as $choice) {
            if (strcasecmp(get_single_attribute_name($choice->attribute_id), 'size') == 0) {
                $size_attribute_id = $choice->attribute_id;
                break;
            }
        }
    }
@endphp

<div class="text-left">
    <!-- Product Name -->
    <h2 class="mb-4 fs-16 fw-700 text-dark">
        {{ $detailedProduct->getTranslation('name') }}
    </h2>

    <!-- This hidden form will be used by JavaScript to send data -->
    <form id="option-choice-form" class="d-none">
        @csrf
        <input type="hidden" name="id" value="{{ $detailedProduct->id }}">


        @if ($detailedProduct->choice_options != null)
            @foreach (json_decode($detailedProduct->choice_options) as $choice)
                @if (strcasecmp(get_single_attribute_name($choice->attribute_id), 'size') != 0)

                    <input type="radio" name="attribute_id_{{ $choice->attribute_id }}" value="{{ $choice->values[0] }}" checked>
                @endif
            @endforeach
        @endif


        <input type="hidden" name="color" value="">
        @if ($size_attribute_id)
            <input type="hidden" name="attribute_id_{{ $size_attribute_id }}" value="">
        @endif
        <input type="hidden" name="quantity" value="">

    </form>

    <!-- Color Selection -->
    <div class="mb-4">
        <h4 class="fs-14 fw-600 text-dark mb-2">{{ translate('Color') }} : <span class="text-primary selected-color-name"></span></h4>
        <div class="color-selection-wrapper">
            @if ($detailedProduct->colors != null && count(json_decode($detailedProduct->colors)) > 0)
                @foreach (json_decode($detailedProduct->colors) as $key => $color_value)
                    @php $color_name = get_single_color_name($color_value); @endphp
                    <div class="color-option @if ($key == 0) selected @endif" data-color-value="{{ $color_value }}" data-color-name="{{ $color_name }}">
                        <span class="color-swatch-box" style="background: {{ $color_value }};"></span>
                        <span class="color-name">{{ $color_name }}</span>
                        <i class="las la-check-circle selected-icon"></i>
                    </div>
                @endforeach
            @else
                <p class="text-muted">{{ translate('No colors available') }}</p>
            @endif
        </div>
    </div>
    <hr>

    <!-- Tiered Pricing Display -->
    <div class="bulk-pricing-section mb-4">
        <h4 class="fs-14 fw-600 text-dark mb-3">{{ translate('Bulk Pricing') }}</h4>
        <div class="d-flex flex-wrap mb-3 pricing-tiers-container">

            <div class="pricing-tier active"><div class="tier-price">৳527</div><div class="tier-quantity">2 or more</div></div>
            <div class="pricing-tier featured"><div class="tier-price">৳464</div><div class="tier-quantity">100 or more</div><div class="tier-badge">Best Value</div></div>
            <div class="pricing-tier"><div class="tier-price">৳420</div><div class="tier-quantity">500 or more</div></div>
            <div class="pricing-tier"><div class="tier-price">৳400</div><div class="tier-quantity">1000 or more</div></div>
        </div>
    </div>

    <!-- Size Selection Table -->
    <div class="mb-4">
        <h4 class="fs-14 fw-600 text-dark mb-3">{{ translate('Size') }}</h4>
        <div class="size-pricing-table">
            <div class="table-responsive">
                <table class="table table-borderless mb-0">
                    <thead><tr class="table-header"><th>{{ translate('Size') }}</th><th>{{ translate('Unit Price') }}</th><th>{{ translate('Total Price') }}</th><th class="text-right">{{ translate('Add/Quantity') }}</th></tr></thead>
                    <tbody id="size-pricing-tbody">

                        <tr class="size-row" data-size="M"><td class="size-cell"><span class="size-name">M</span></td><td class="price-cell"><span class="unit-price">৳ 393.00</span></td><td class="total-price-cell"><span class="total-price" data-unit-price="393">৳ 0.00</span></td><td class="quantity-cell"><button type="button" class="add-btn">{{ translate('Add') }}</button><div class="quantity-controls d-none"><button type="button" class="qty-btn minus-btn"><i class="las la-minus"></i></button><input type="number" class="qty-input" value="1" min="1" max="1923"><button type="button" class="qty-btn plus-btn"><i class="las la-plus"></i></button></div><div class="stock-info d-none">Stock: 1923</div></td></tr>
                        <tr class="size-row" data-size="L"><td class="size-cell"><span class="size-name">L</span></td><td class="price-cell"><span class="unit-price">৳ 393.00</span></td><td class="total-price-cell"><span class="total-price" data-unit-price="393">৳ 0.00</span></td><td class="quantity-cell"><button type="button" class="add-btn">{{ translate('Add') }}</button><div class="quantity-controls d-none"><button type="button" class="qty-btn minus-btn"><i class="las la-minus"></i></button><input type="number" class="qty-input" value="1" min="1" max="2156"><button type="button" class="qty-btn plus-btn"><i class="las la-plus"></i></button></div><div class="stock-info d-none">Stock: 2156</div></td></tr>
                        <tr class="size-row" data-size="XL"><td class="size-cell"><span class="size-name">XL</span></td><td class="price-cell"><span class="unit-price">৳ 393.00</span></td><td class="total-price-cell"><span class="total-price" data-unit-price="393">৳ 0.00</span></td><td class="quantity-cell"><button type="button" class="add-btn">{{ translate('Add') }}</button><div class="quantity-controls d-none"><button type="button" class="qty-btn minus-btn"><i class="las la-minus"></i></button><input type="number" class="qty-input" value="1" min="1" max="1834"><button type="button" class="qty-btn plus-btn"><i class="las la-plus"></i></button></div><div class="stock-info d-none">Stock: 1834</div></td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    @if ($detailedProduct->auction_product != 1)
        <div class="grand-total-section">
            <div class="total-summary">
                <div class="total-items"><span class="label">{{ translate('Total Items') }}:</span><span class="value" id="total-items">0</span></div>
                <div class="grand-total"><span class="label">{{ translate('Grand Total') }}:</span><span class="value" id="grand-total">৳ 0.00</span></div>
            </div>
            <div class="action-buttons">
                @if (Auth::check())
                    <button type="button" class="btn btn-outline-primary add-to-cart-btn" onclick="handleCartAction(false)"><i class="las la-shopping-cart"></i> {{ translate('Add to Cart') }}</button>
                    <button type="button" class="btn btn-primary buy-now-btn" onclick="handleCartAction(true)"><i class="las la-bolt"></i> {{ translate('Buy Now') }}</button>
                @else
                    <button type="button" class="btn btn-outline-primary add-to-cart-btn" onclick="promptLogin()"><i class="las la-shopping-cart"></i> {{ translate('Add to Cart') }}</button>
                    <button type="button" class="btn btn-primary buy-now-btn" onclick="promptLogin()"><i class="las la-bolt"></i> {{ translate('Buy Now') }}</button>
                @endif
            </div>
        </div>
    @endif
    <hr>
</div>

<style>

    .pricing-tiers-container{gap:12px}.pricing-tier{background:#f8f9fa;border:2px solid #e9ecef;border-radius:8px;padding:16px 20px;text-align:center;min-width:120px;position:relative;transition:all .3s ease;cursor:pointer;color:#34495e}.pricing-tier:hover{border-color:#f76892;transform:translateY(-2px);box-shadow:0 4px 12px rgba(247,104,146,.2)}.pricing-tiers-container>div:nth-child(1){background-color:#f76892;border-color:#f76892;color:#fff}.pricing-tiers-container>div:nth-child(2){background-color:#e5395f;border-color:#e5395f;color:#fff}.pricing-tiers-container>div:nth-child(3){background-color:#d11e42;border-color:#d11e42;color:#fff}.pricing-tiers-container>div:nth-child(4){background-color:#a00d2b;border-color:#a00d2b;color:#fff}.tier-price{font-size:20px;font-weight:700;color:inherit;margin-bottom:4px}.tier-quantity{font-size:12px;opacity:.8;font-weight:500}.tier-badge{position:absolute;top:-8px;left:50%;transform:translateX(-50%);background:#ff9800;color:#fff;font-size:10px;padding:2px 8px;border-radius:10px;font-weight:600;white-space:nowrap}.color-selection-wrapper{display:flex;gap:12px;flex-wrap:wrap}.color-option{position:relative;border:2px solid #e9ecef;border-radius:8px;padding:8px;cursor:pointer;transition:all .3s ease;background:#fff;min-width:80px;text-align:center}.color-option:hover{border-color:#3498db;transform:translateY(-2px)}.color-option.selected{border-color:#27ae60;background:#e8f5e8}.color-swatch-box{display:block;width:40px;height:40px;border-radius:4px;margin:0 auto 4px;object-fit:cover;border:1px solid #dee2e6}.color-name{display:block;font-size:11px;font-weight:500;color:#666}.selected-icon{position:absolute;top:-5px;right:-5px;background:#27ae60;color:#fff;border-radius:50%;font-size:14px;width:20px;height:20px;display:none;align-items:center;justify-content:center}.color-option.selected .selected-icon{display:flex}.size-pricing-table{background:#fff;border-radius:12px;overflow:hidden;box-shadow:0 2px 8px rgba(0,0,0,.1)}.table-header{background:linear-gradient(135deg,#667eea,#764ba2);color:#fff}.table-header th{border:none;font-weight:600;font-size:14px;padding:16px 12px;vertical-align:middle}.size-row{transition:all .3s ease;border-left:4px solid transparent}.size-row:hover{background:#f8f9fa}.size-row.active{background:#e3f2fd;border-left-color:#3498db}.size-row td{padding:16px 12px;vertical-align:middle;border-bottom:1px solid #eee}.size-pricing-table tbody tr:last-child td{border-bottom:none}.size-name{font-weight:600;font-size:16px;color:#2c3e50;background:#ecf0f1;padding:6px 12px;border-radius:20px;display:inline-block;min-width:40px;text-align:center}.unit-price,.total-price{font-weight:600;font-size:16px}.unit-price{color:#7f8c8d}.total-price{color:#e74c3c}.quantity-cell{text-align:right}.add-btn{background:#34495e;color:#fff;border:none;padding:8px 16px;border-radius:6px;font-weight:600;font-size:13px;transition:all .3s ease;min-width:60px;display:inline-block}.add-btn:hover{background:#2c3e50;transform:translateY(-1px)}.quantity-controls{display:flex;align-items:center;justify-content:center;border-radius:20px;overflow:hidden;background:#34495e;width:fit-content;margin-left:auto;color:#fff}.qty-btn{background:#34495e;border:none;width:32px;height:32px;display:flex;align-items:center;justify-content:center;color:#fff;transition:all .2s ease;cursor:pointer}.qty-btn:hover:not(:disabled){background:#4a6680}.qty-btn:disabled{opacity:.5;cursor:not-allowed}.qty-input{border:none;width:50px;height:32px;text-align:center;font-weight:600;font-size:14px;background:#34495e;color:#fff;padding:0;-moz-appearance:textfield}.qty-input::-webkit-inner-spin-button,.qty-input::-webkit-outer-spin-button{-webkit-appearance:none;margin:0}.stock-info{font-size:11px;color:#27ae60;margin-top:4px;text-align:center}.grand-total-section{background:#fff;border-radius:12px;padding:20px;border:2px dashed #a0c5e8;margin-top:20px;box-shadow:0 4px 15px rgba(0,0,0,.08)}.total-summary{display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;flex-wrap:wrap;gap:10px}.total-items .label,.grand-total .label{font-weight:600;color:#666}.total-items .value{background:#3498db;color:#fff;padding:4px 10px;border-radius:15px;font-weight:700;font-size:14px}.grand-total .value{font-size:20px;font-weight:700;color:#e74c3c}.action-buttons{display:flex;gap:12px;flex-wrap:wrap}.add-to-cart-btn,.buy-now-btn{flex:1;min-width:140px;padding:12px 20px;font-weight:600;border-radius:8px;transition:all .3s ease;display:flex;align-items:center;justify-content:center;gap:8px}.add-to-cart-btn{background-color:transparent;border:1px solid #a0c5e8;color:#3498db}.add-to-cart-btn:hover{background-color:#e3f2fd;border-color:#3498db}.buy-now-btn{background-color:#7b98d1;border:1px solid #7b98d1;color:#fff}.buy-now-btn:hover{background-color:#6a82bd;border-color:#6a82bd}
</style>

<script>
    const SIZE_ATTRIBUTE_ID = '{{ $size_attribute_id }}';

    function promptLogin() {
        AIZ.plugins.notify('warning', '{{ translate('Please log in to continue') }}');
        // Mohammad Hassan - Use customer login for buy now/add to cart
                // Mohammad Hassan
showUserTypeModal();
    }

    // This is the main function called by buttons
    async function handleCartAction(isBuyNow = false) {
        const itemsToAdd = [];
        document.querySelectorAll('.size-row').forEach(row => {
            const qtyControls = row.querySelector('.quantity-controls');
            if (qtyControls && !qtyControls.classList.contains('d-none')) {
                const quantity = parseInt(row.querySelector('.qty-input').value, 10);
                if (quantity > 0) {
                    itemsToAdd.push({ size: row.dataset.size, quantity: quantity });
                }
            }
        });

        if (itemsToAdd.length === 0) {
            AIZ.plugins.notify('warning', '{{ translate('Please select a size and quantity first.') }}');
            return;
        }

        // Disable buttons to prevent multiple clicks
        $('.add-to-cart-btn, .buy-now-btn').prop('disabled', true);

        let lastResponse = null;
        for (const item of itemsToAdd) {
            try {
                lastResponse = await addToCartPromise(item);
                if (lastResponse.status !== 1) {
                    // Stop if any request fails
                    throw new Error(lastResponse.message || 'Failed to add item.');
                }
            } catch (error) {
                AIZ.plugins.notify('danger', '{{ translate('An error occurred. Please try again.') }}');
                console.error("AJAX Error:", error);
                $('.add-to-cart-btn, .buy-now-btn').prop('disabled', false); // Re-enable buttons
                return; // Stop execution
            }
        }

        // Re-enable buttons after all actions are complete
        $('.add-to-cart-btn, .buy-now-btn').prop('disabled', false);

        if (lastResponse && lastResponse.status === 1) {
            if (isBuyNow) {
                window.location.href = '{{ route('checkout') }}';
            } else {
                AIZ.plugins.notify('success', '{{ translate('All selected items have been added to cart.') }}');
                $('#cart_items').html(lastResponse.cart_view);
            }
        }
    }


function addToCartPromise(item) {
    return new Promise((resolve, reject) => {
        const form = document.getElementById('option-choice-form');
        const formData = new FormData(form);

        formData.set('quantity', item.quantity);
        if (SIZE_ATTRIBUTE_ID) {
            formData.set(`attribute_id_${SIZE_ATTRIBUTE_ID}`, item.size);
        }
        const selectedColorEl = document.querySelector('.color-option.selected');
        if (selectedColorEl) {
            formData.set('color', selectedColorEl.dataset.colorValue);
        }

        $.ajax({
            type: "POST",
            url: "{{ route('cart.addToCart') }}",
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: response => {
                console.log("Cart Response:", response);
                resolve(response);
            },
            error: (xhr, status, error) => {
                console.error("AJAX Error:", xhr.responseText);
                reject(xhr);
            }
        });
    });
}

    document.addEventListener('DOMContentLoaded', function() {
        function updateRowTotal(row) {
            const input = row.querySelector('.qty-input');
            const totalPriceSpan = row.querySelector('.total-price');
            const unitPrice = parseFloat(totalPriceSpan.getAttribute('data-unit-price'));
            const quantity = parseInt(input.value) || 0;
            totalPriceSpan.textContent = `৳ ${(unitPrice * quantity).toFixed(2)}`;
        }

        function updateQuantityButtons(row) {
            const input = row.querySelector('.qty-input');
            const minusBtn = row.querySelector('.qty-btn.minus-btn');
            const plusBtn = row.querySelector('.qty-btn.plus-btn');
            const value = parseInt(input.value);
            const min = parseInt(input.min);
            const max = parseInt(input.max);
            minusBtn.disabled = (value <= min);
            plusBtn.disabled = (value >= max);
        }

        function updateGrandTotal() {
            let totalItems = 0;
            let grandTotal = 0;
            document.querySelectorAll('.size-row').forEach(row => {
                const qtyControls = row.querySelector('.quantity-controls');
                if (qtyControls && !qtyControls.classList.contains('d-none')) {
                    const quantity = parseInt(row.querySelector('.qty-input').value) || 0;
                    if (quantity > 0) {
                        const unitPrice = parseFloat(row.querySelector('.total-price').getAttribute('data-unit-price'));
                        totalItems += quantity;
                        grandTotal += (unitPrice * quantity);
                    }
                }
            });
            document.getElementById('total-items').textContent = totalItems;
            document.getElementById('grand-total').textContent = `৳ ${grandTotal.toFixed(2)}`;
        }

        document.querySelectorAll('.add-btn').forEach(button => {
            button.addEventListener('click', function() {
                const row = this.closest('.size-row');
                this.classList.add('d-none');
                row.querySelector('.quantity-controls').classList.remove('d-none');
                row.querySelector('.stock-info').classList.remove('d-none');
                row.classList.add('active');
                row.querySelector('.qty-input').value = 1;
                updateRowTotal(row);
                updateQuantityButtons(row);
                updateGrandTotal();
            });
        });

        document.querySelectorAll('.qty-btn').forEach(button => {
            button.addEventListener('click', function() {
                const row = this.closest('.size-row');
                const input = row.querySelector('.qty-input');
                let currentValue = parseInt(input.value);
                if (this.classList.contains('plus-btn')) {
                    input.value = currentValue + 1;
                } else if (this.classList.contains('minus-btn')) {
                    input.value = currentValue - 1;
                }
                updateRowTotal(row);
                updateQuantityButtons(row);
                updateGrandTotal();
            });
        });

        document.querySelectorAll('.qty-input').forEach(input => {
            input.addEventListener('input', function() {
                const row = this.closest('.size-row');
                updateRowTotal(row);
                updateQuantityButtons(row);
                updateGrandTotal();
            });
        });

        const selectedColorNameSpan = document.querySelector('.selected-color-name');
        document.querySelectorAll('.color-option').forEach(option => {
            option.addEventListener('click', function() {
                document.querySelectorAll('.color-option').forEach(o => o.classList.remove('selected'));
                this.classList.add('selected');
                if (selectedColorNameSpan) {
                    selectedColorNameSpan.textContent = this.dataset.colorName;
                }
            });
        });

        function initializePage() {
            updateGrandTotal();
            const initialColor = document.querySelector('.color-option.selected');
            if (initialColor && selectedColorNameSpan) {
                selectedColorNameSpan.textContent = initialColor.dataset.colorName;
            }
        }

        initializePage();
    });
</script> --}}





<div class="text-left">
    <!-- Product Name -->
    <h2 class="mb-4 fs-16 fw-700 text-dark">
        {{ $detailedProduct->getTranslation('name') }}
    </h2>

    <div class="row align-items-center mb-3">
        <!-- Review -->
        @if ($detailedProduct->auction_product != 1)
            <div class="col-12">
                @php
                    $total = 0;
                    $total += $detailedProduct->reviews->where('status', 1)->count();
                @endphp
                <span class="rating rating-mr-2">
                    {{ renderStarRating($detailedProduct->rating) }}
                </span>
                <span class="ml-1 opacity-50 fs-14">({{ $total }}
                    {{ translate('reviews') }})</span>
            </div>
        @endif
        <!-- Estimate Shipping Time -->
        @if ($detailedProduct->est_shipping_days)
            <div class="col-auto fs-14 mt-1">
                <small class="mr-1 opacity-50 fs-14">{{ translate('Estimate Shipping Time') }}:</small>
                <span class="fw-500">{{ $detailedProduct->est_shipping_days }} {{ translate('Days') }}</span>
            </div>
        @endif
        <!-- In stock -->
        @if ($detailedProduct->digital == 1)
            <div class="col-12 mt-1">
                <span class="badge badge-md badge-inline badge-pill badge-success">{{ translate('In stock') }}</span>
            </div>
        @endif
    </div>
    <div class="row align-items-center">
        @if (get_setting('product_query_activation') == 1)
            <!-- Ask about this product -->
            <div class="col-xl-3 col-lg-4 col-md-3 col-sm-4 mb-3">
                <a href="javascript:void();" onclick="goToView('product_query')"
                    class="text-primary fs-14 fw-600 d-flex">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 32 32">
                        <g id="Group_25571" data-name="Group 25571" transform="translate(-975 -411)">
                            <g id="Path_32843" data-name="Path 32843" transform="translate(975 411)" fill="#fff">
                                <path
                                    d="M 16 31 C 11.9933500289917 31 8.226519584655762 29.43972969055176 5.393400192260742 26.60659980773926 C 2.560270071029663 23.77347946166992 1 20.00665092468262 1 16 C 1 11.9933500289917 2.560270071029663 8.226519584655762 5.393400192260742 5.393400192260742 C 8.226519584655762 2.560270071029663 11.9933500289917 1 16 1 C 20.00665092468262 1 23.77347946166992 2.560270071029663 26.60659980773926 5.393400192260742 C 29.43972969055176 8.226519584655762 31 11.9933500289917 31 16 C 31 20.00665092468262 29.43972969055176 23.77347946166992 26.60659980773926 26.60659980773926 C 23.77347946166992 29.43972969055176 20.00665092468262 31 16 31 Z"
                                    stroke="none" />
                                <path
                                    d="M 16 2 C 12.26045989990234 2 8.744749069213867 3.456249237060547 6.100500106811523 6.100500106811523 C 3.456249237060547 8.744749069213867 2 12.26045989990234 2 16 C 2 19.73954010009766 3.456249237060547 23.2552490234375 6.100500106811523 25.89949989318848 C 8.744749069213867 28.54375076293945 12.26045989990234 30 16 30 C 19.73954010009766 30 23.2552490234375 28.54375076293945 25.89949989318848 25.89949989318848 C 28.54375076293945 23.2552490234375 30 19.73954010009766 30 16 C 30 12.26045989990234 28.54375076293945 8.744749069213867 25.89949989318848 6.100500106811523 C 23.2552490234375 3.456249237060547 19.73954010009766 2 16 2 M 16 0 C 24.8365592956543 0 32 7.163440704345703 32 16 C 32 24.8365592956543 24.8365592956543 32 16 32 C 7.163440704345703 32 0 24.8365592956543 0 16 C 0 7.163440704345703 7.163440704345703 0 16 0 Z"
                                    stroke="none" fill="{{ get_setting('secondary_base_color', '#ffc519') }}" />
                            </g>
                            <path id="Path_32842" data-name="Path 32842"
                                d="M28.738,30.935a1.185,1.185,0,0,1-1.185-1.185,3.964,3.964,0,0,1,.942-2.613c.089-.095.213-.207.361-.344.735-.658,2.252-2.032,2.252-3.555a2.228,2.228,0,0,0-2.37-2.37,2.228,2.228,0,0,0-2.37,2.37,1.185,1.185,0,1,1-2.37,0,4.592,4.592,0,0,1,4.74-4.74,4.592,4.592,0,0,1,4.74,4.74c0,2.577-2.044,4.432-3.028,5.333l-.284.255a1.89,1.89,0,0,0-.243.948A1.185,1.185,0,0,1,28.738,30.935Zm0,3.561a1.185,1.185,0,0,1-.835-2.026,1.226,1.226,0,0,1,1.671,0,1.061,1.061,0,0,1,.148.184,1.345,1.345,0,0,1,.113.2,1.41,1.41,0,0,1,.065.225,1.138,1.138,0,0,1,0,.462,1.338,1.338,0,0,1-.065.219,1.185,1.185,0,0,1-.113.207,1.06,1.06,0,0,1-.148.184A1.185,1.185,0,0,1,28.738,34.5Z"
                                transform="translate(962.004 400.504)"
                                fill="{{ get_setting('secondary_base_color', '#ffc519') }}" />
                        </g>
                    </svg>
                    <span class="ml-2 text-primary animate-underline-blue">{{ translate('Product Inquiry') }}</span>
                </a>
            </div>
        @endif
        <div class="col mb-3">
            @if ($detailedProduct->auction_product != 1)
                <div class="d-flex">
                    <!-- Add to wishlist button -->
                    <a href="javascript:void(0)" onclick="addToWishList({{ $detailedProduct->id }})"
                        class="mr-3 fs-14 text-dark opacity-60 has-transitiuon hov-opacity-100">
                        <i class="la la-heart-o mr-1"></i>
                        {{ translate('Add to Wishlist') }}
                    </a>
                    <!-- Add to compare button -->
                    <a href="javascript:void(0)" onclick="addToCompare({{ $detailedProduct->id }})"
                        class="fs-14 text-dark opacity-60 has-transitiuon hov-opacity-100">
                        <i class="las la-sync mr-1"></i>
                        {{ translate('Add to Compare') }}
                    </a>
                </div>
            @endif
        </div>
    </div>


    <!-- Brand Logo & Name -->
    @if ($detailedProduct->brand != null)
        <div class="d-flex flex-wrap align-items-center mb-3">
            <span class="text-secondary fs-14 fw-400 mr-4 w-80px">{{ translate('Brand') }}</span><br>
            <a href="{{ route('products.brand', $detailedProduct->brand->slug) }}"
                class="text-reset hov-text-primary fs-14 fw-700">{{ $detailedProduct->brand->name }}</a>
        </div>
    @endif

    {{-- Warranty --}}
    @if ($detailedProduct->has_warranty == 1 && $detailedProduct->warranty_id != null)
        <div class="d-flex flex-wrap align-items-center mb-3">
            <span class="text-secondary fs-14 fw-400 mr-4 w-80px">{{ translate('Warranty') }}</span><br>
            <img src="{{ uploaded_asset($detailedProduct->warranty->logo) }}" height="40">
            <span class="border border-secondary-base btn fs-12 ml-3 px-3 py-1 rounded-1 text-secondary">
                {{ $detailedProduct->warranty->getTranslation('text') }}
                @if ($detailedProduct->warranty_note_id != null)
                    <span href="javascript:void(1);" data-toggle="modal" data-target="#warranty-note-modal"
                        class="border-bottom border-bottom-4 ml-2 text-secondary-base">
                        {{ translate('View Details') }}
                    </span>
                @endif
            </span>
        </div>
    @endif

    <!-- Seller Info -->
    <div class="d-flex flex-wrap align-items-center">
        <div class="d-flex align-items-center mr-4">
            <!-- Shop Name -->
            @if ($detailedProduct->added_by == 'seller' && get_setting('vendor_system_activation') == 1)
                <span class="text-secondary fs-14 fw-400 mr-4 w-80px">{{ translate('Sold by') }}</span>
                <a href="{{ route('shop.visit', $detailedProduct->user->shop->slug) }}"
                    class="text-reset hov-text-primary fs-14 fw-700">{{ $detailedProduct->user->shop->name }}</a>
            @else
                <p class="mb-0 fs-14 fw-700">{{ translate('Inhouse product') }}</p>
            @endif
        </div>
        <div class="w-100 d-sm-none"></div>
        <!-- Messase to seller -->
        @if (get_setting('conversation_system') == 1)
            <div class="">
                <button
                    class="btn btn-sm btn-soft-secondary-base btn-outline-secondary-base hov-svg-white hov-text-white rounded-4"
                    onclick="show_chat_modal()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"
                        class="mr-2 has-transition">
                        <g id="Group_23918" data-name="Group 23918" transform="translate(1053.151 256.688)">
                            <path id="Path_3012" data-name="Path 3012"
                                d="M134.849,88.312h-8a2,2,0,0,0-2,2v5a2,2,0,0,0,2,2v3l2.4-3h5.6a2,2,0,0,0,2-2v-5a2,2,0,0,0-2-2m1,7a1,1,0,0,1-1,1h-8a1,1,0,0,1-1-1v-5a1,1,0,0,1,1-1h8a1,1,0,0,1,1,1Z"
                                transform="translate(-1178 -341)"
                                fill="{{ get_setting('secondary_base_color', '#ffc519') }}" />
                            <path id="Path_3013" data-name="Path 3013"
                                d="M134.849,81.312h8a1,1,0,0,1,1,1v5a1,1,0,0,1-1,1h-.5a.5.5,0,0,0,0,1h.5a2,2,0,0,0,2-2v-5a2,2,0,0,0-2-2h-8a2,2,0,0,0-2,2v.5a.5.5,0,0,0,1,0v-.5a1,1,0,0,1,1-1"
                                transform="translate(-1182 -337)"
                                fill="{{ get_setting('secondary_base_color', '#ffc519') }}" />
                            <path id="Path_3014" data-name="Path 3014"
                                d="M131.349,93.312h5a.5.5,0,0,1,0,1h-5a.5.5,0,0,1,0-1"
                                transform="translate(-1181 -343.5)"
                                fill="{{ get_setting('secondary_base_color', '#ffc519') }}" />
                            <path id="Path_3015" data-name="Path 3015"
                                d="M131.349,99.312h5a.5.5,0,1,1,0,1h-5a.5.5,0,1,1,0-1"
                                transform="translate(-1181 -346.5)"
                                fill="{{ get_setting('secondary_base_color', '#ffc519') }}" />
                        </g>
                    </svg>

                    {{ translate('Message Seller') }}
                </button>
            </div>
        @endif
        @if (get_setting('whatsapp_order') == 1)
            @php
                $storeName = env('APP_NAME');
                $productTitle = $detailedProduct->getTranslation('name');
                $productUrl = URL::to('/product') . '/' . $detailedProduct->slug;
                $template = get_setting('order_messege_template');
                $message = str_replace(
                    ['[[storeName]]', '[[productTitle]]', '[[productUrl]]'],
                    [$storeName, $productTitle, $productUrl],
                    $template,
                );

                $whatsappNumber = env('WHATSAPP_NUMBER');
                $whatsappUrl = "https://wa.me/{$whatsappNumber}?text=" . urlencode($message);
            @endphp
            @if (
                ($detailedProduct->added_by == 'seller' && get_setting('whatsapp_order_seller_prods') == 1) ||
                    $detailedProduct->added_by == 'admin')
                <div class="ml-2">
                    <a class="btn btn-sm btn-soft-whatsapp-base hov-svg-white hov-text-white rounded-4"
                        href="{{ $whatsappUrl }}" target="_blank">
                        <i class="lab la-whatsapp mr-1"></i>{{ translate('Order Via WhatsApp') }}
                    </a>
                </div>
            @endif
        @endif


        <!-- Size guide -->
        @php
            $sizeChartId =
                $detailedProduct->main_category && $detailedProduct->main_category->sizeChart
                    ? $detailedProduct->main_category->sizeChart->id
                    : 0;
            $sizeChartName =
                $detailedProduct->main_category && $detailedProduct->main_category->sizeChart
                    ? $detailedProduct->main_category->sizeChart->name
                    : null;
        @endphp
        @if ($sizeChartId != 0)
            <div class=" ml-4">
                <a href="javascript:void(1);"
                    onclick='showSizeChartDetail({{ $sizeChartId }}, "{{ $sizeChartName }}")'
                    class="animate-underline-primary">{{ translate('Show size guide') }}</a>
            </div>
        @endif
    </div>

    <hr>

    <!-- For auction product -->
    @if ($detailedProduct->auction_product)
        <div class="row no-gutters mb-3">
            <div class="col-sm-2">
                <div class="text-secondary fs-14 fw-400 mt-1">{{ translate('Auction Will End') }}</div>
            </div>
            <div class="col-sm-10">
                @if ($detailedProduct->auction_end_date > strtotime('now'))
                    <div class="aiz-count-down align-items-center"
                        data-date="{{ date('Y/m/d H:i:s', $detailedProduct->auction_end_date) }}"></div>
                @else
                    <p>{{ translate('Ended') }}</p>
                @endif

            </div>
        </div>

        <div class="row no-gutters mb-3">
            <div class="col-sm-2">
                <div class="text-secondary fs-14 fw-400 mt-1">{{ translate('Starting Bid') }}</div>
            </div>
            <div class="col-sm-10">
                <span class="opacity-50 fs-20">
                    {{ single_price($detailedProduct->starting_bid) }}
                </span>
                @if ($detailedProduct->unit != null)
                    <span class="opacity-70">/{{ $detailedProduct->getTranslation('unit') }}</span>
                @endif
            </div>
        </div>

        @if (Auth::check() && Auth::user()->product_bids->where('product_id', $detailedProduct->id)->first() != null)
            <div class="row no-gutters mb-3">
                <div class="col-sm-2">
                    <div class="text-secondary fs-14 fw-400 mt-1">{{ translate('My Bidded Amount') }}</div>
                </div>
                <div class="col-sm-10">
                    <span class="opacity-50 fs-20">
                        {{ single_price(Auth::user()->product_bids->where('product_id', $detailedProduct->id)->first()->amount) }}
                    </span>
                </div>
            </div>
            <hr>
        @endif

        @php $highest_bid = $detailedProduct->bids->max('amount'); @endphp
        <div class="row no-gutters my-2 mb-3">
            <div class="col-sm-2">
                <div class="text-secondary fs-14 fw-400 mt-1">{{ translate('Highest Bid') }}</div>
            </div>
            <div class="col-sm-10">
                <strong class="h3 fw-600 text-primary">
                    @if ($highest_bid != null)
                        {{ single_price($highest_bid) }}
                    @endif
                </strong>
            </div>
        </div>
    @else
        <!-- Without auction product -->
        @if ($detailedProduct->wholesale_product == 1)
            <!-- Wholesale -->
            <table class="table mb-3">
                <thead>
                    <tr>
                        <th class="border-top-0">{{ translate('Min Qty') }}</th>
                        <th class="border-top-0">{{ translate('Max Qty') }}</th>
                        <th class="border-top-0">{{ translate('Unit Price') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($detailedProduct->stocks->first()->wholesalePrices as $wholesalePrice)
                        <tr>
                            <td>{{ $wholesalePrice->min_qty }}</td>
                            <td>{{ $wholesalePrice->max_qty }}</td>
                            <td>{{ single_price($wholesalePrice->price) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <!-- Without Wholesale -->
            @if (home_price($detailedProduct) != home_discounted_price($detailedProduct))
                <div class="row no-gutters mb-3">
                    <div class="col-sm-2">
                        <div class="text-secondary fs-14 fw-400">{{ translate('Price') }}</div>
                    </div>
                    <div class="col-sm-10">
                        <div class="d-flex align-items-center">
                            <!-- Discount Price -->
                            <strong class="fs-16 fw-700 text-primary">
                                {{ home_discounted_price($detailedProduct) }}
                            </strong>
                            <!-- Home Price -->
                            <del class="fs-14 opacity-60 ml-2">
                                {{ home_price($detailedProduct) }}
                            </del>
                            <!-- Unit -->
                            @if ($detailedProduct->unit != null)
                                <span class="opacity-70 ml-1">/{{ $detailedProduct->getTranslation('unit') }}</span>
                            @endif
                            <!-- Discount percentage -->
                            @if (discount_in_percentage($detailedProduct) > 0)
                                <span class="bg-primary ml-2 fs-11 fw-700 text-white w-35px text-center p-1"
                                    style="padding-top:2px;padding-bottom:2px;">-{{ discount_in_percentage($detailedProduct) }}%</span>
                            @endif
                            <!-- Club Point -->
                            @if (addon_is_activated('club_point') && $detailedProduct->earn_point > 0)
                                <div class="ml-2 bg-secondary-base d-flex justify-content-center align-items-center px-3 py-1"
                                    style="width: fit-content;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                        viewBox="0 0 12 12">
                                        <g id="Group_23922" data-name="Group 23922" transform="translate(-973 -633)">
                                            <circle id="Ellipse_39" data-name="Ellipse 39" cx="6"
                                                cy="6" r="6" transform="translate(973 633)" fill="#fff" />
                                            <g id="Group_23920" data-name="Group 23920"
                                                transform="translate(973 633)">
                                                <path id="Path_28698" data-name="Path 28698"
                                                    d="M7.667,3H4.333L3,5,6,9,9,5Z" transform="translate(0 0)"
                                                    fill="#f3af3d" />
                                                <path id="Path_28699" data-name="Path 28699"
                                                    d="M5.33,3h-1L3,5,6,9,4.331,5Z" transform="translate(0 0)"
                                                    fill="#f3af3d" opacity="0.5" />
                                                <path id="Path_28700" data-name="Path 28700"
                                                    d="M12.666,3h1L15,5,12,9,l1.664-4Z" transform="translate(-5.995 0)"
                                                    fill="#f3af3d" />
                                            </g>
                                        </g>
                                    </svg>
                                    <small class="fs-11 fw-500 text-white ml-2">{{ translate('Club Point') }}:
                                        {{ $detailedProduct->earn_point }}</small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @else
                <div class="row no-gutters mb-3">
                    <div class="col-sm-2">
                        <div class="text-secondary fs-14 fw-400">{{ translate('Price') }}</div>
                    </div>
                    <div class="col-sm-10">
                        <div class="d-flex align-items-center">
                            <!-- Discount Price -->
                            <strong class="fs-16 fw-700 text-primary">
                                {{ home_discounted_price($detailedProduct) }}
                            </strong>
                            <!-- Unit -->
                            @if ($detailedProduct->unit != null)
                                <span class="opacity-70">/{{ $detailedProduct->getTranslation('unit') }}</span>
                            @endif
                            <!-- Club Point -->
                            @if (addon_is_activated('club_point') && $detailedProduct->earn_point > 0)
                                <div class="ml-2 bg-secondary-base d-flex justify-content-center align-items-center px-3 py-1"
                                    style="width: fit-content;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12"
                                        viewBox="0 0 12 12">
                                        <g id="Group_23922" data-name="Group 23922" transform="translate(-973 -633)">
                                            <circle id="Ellipse_39" data-name="Ellipse 39" cx="6"
                                                cy="6" r="6" transform="translate(973 633)" fill="#fff" />
                                            <g id="Group_23920" data-name="Group 23920"
                                                transform="translate(973 633)">
                                                <path id="Path_28698" data-name="Path 28698"
                                                    d="M7.667,3H4.333L3,5,6,9,9,5Z" transform="translate(0 0)"
                                                    fill="#f3af3d" />
                                                <path id="Path_28699" data-name="Path 28699"
                                                    d="M5.33,3h-1L3,5,6,9,4.331,5Z" transform="translate(0 0)"
                                                    fill="#f3af3d" opacity="0.5" />
                                                <path id="Path_28700" data-name="Path 28700"
                                                    d="M12.666,3h1L15,5,12,9,l1.664-4Z" transform="translate(-5.995 0)"
                                                    fill="#f3af3d" />
                                            </g>
                                        </g>
                                    </svg>
                                    <small class="fs-11 fw-500 text-white ml-2">{{ translate('Club Point') }}:
                                        {{ $detailedProduct->earn_point }}</small>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        @endif
    @endif

    @if ($detailedProduct->auction_product != 1)
        <form id="option-choice-form">
            @csrf
            <input type="hidden" name="id" value="{{ $detailedProduct->id }}">

            @if ($detailedProduct->digital == 0)
                <!-- Choice Options -->
                @if ($detailedProduct->choice_options != null)
                    @foreach (json_decode($detailedProduct->choice_options) as $key => $choice)
                        <div class="row no-gutters mb-3">
                            <div class="col-sm-2">
                                <div class="text-secondary fs-14 fw-400 mt-2 ">
                                    {{ get_single_attribute_name($choice->attribute_id) }}
                                </div>
                            </div>
                            <div class="col-sm-10">
                                <div class="aiz-radio-inline">
                                    @foreach ($choice->values as $key => $value)
                                        <label class="aiz-megabox pl-0 mr-2 mb-0">
                                            <input type="radio" name="attribute_id_{{ $choice->attribute_id }}"
                                                value="{{ $value }}"
                                                @if ($key == 0) checked @endif>
                                            <span
                                                class="aiz-megabox-elem rounded-0 d-flex align-items-center justify-content-center py-1 px-3">
                                                {{ $value }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif

                <!-- Color Options -->
                @if ($detailedProduct->colors != null && count(json_decode($detailedProduct->colors)) > 0)
                    <div class="row no-gutters mb-3">
                        <div class="col-sm-2">
                            <div class="text-secondary fs-14 fw-400 mt-2">{{ translate('Color') }}</div>
                        </div>
                        <div class="col-sm-10">
                            <div class="aiz-radio-inline">
                                @foreach (json_decode($detailedProduct->colors) as $key => $color)
                                    <label class="aiz-megabox pl-0 mr-2 mb-0" data-toggle="tooltip"
                                        data-title="{{ get_single_color_name($color) }}">
                                        <input type="radio" name="color"
                                            value="{{ get_single_color_name($color) }}"
                                            @if ($key == 0) checked @endif>
                                        <span
                                            class="aiz-megabox-elem rounded-0 d-flex align-items-center justify-content-center p-1">
                                            <span class="size-25px d-inline-block rounded"
                                                style="background: {{ $color }};"></span>
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Quantity + Add to cart -->
                <div class="row no-gutters mb-3">
                    <div class="col-sm-2">
                        <div class="text-secondary fs-14 fw-400 mt-2">{{ translate('Quantity') }}</div>
                    </div>
                    <div class="col-sm-10">
                        <div class="product-quantity d-flex align-items-center">
                            <div class="row no-gutters align-items-center aiz-plus-minus mr-3" style="width: 130px;">
                                <button class="btn col-auto btn-icon btn-sm btn-light rounded-0" type="button"
                                    data-type="minus" data-field="quantity" disabled="">
                                    <i class="las la-minus"></i>
                                </button>
                                <input type="number" name="quantity"
                                    class="col border-0 text-center flex-grow-1 fs-16 input-number" placeholder="1"
                                    value="{{ $detailedProduct->min_qty }}" min="{{ $detailedProduct->min_qty }}"
                                    max="10" lang="en">
                                <button class="btn col-auto btn-icon btn-sm btn-light rounded-0" type="button"
                                    data-type="plus" data-field="quantity">
                                    <i class="las la-plus"></i>
                                </button>
                            </div>
                            @php
                                $qty = 0;
                                foreach ($detailedProduct->stocks as $key => $stock) {
                                    $qty += $stock->qty;
                                }
                            @endphp
                            <div class="avialable-amount opacity-60">
                                @if ($detailedProduct->stock_visibility_state == 'quantity')
                                    (<span id="available-quantity">{{ $qty }}</span>
                                    {{ translate('available') }})
                                @elseif($detailedProduct->stock_visibility_state == 'text' && $qty >= 1)
                                    (<span id="available-quantity">{{ translate('In Stock') }}</span>)
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Quantity -->
                <input type="hidden" name="quantity" value="1">
            @endif

            <!-- Total Price -->
            <div class="row no-gutters pb-3 d-none" id="chosen_price_div">
                <div class="col-sm-2">
                    <div class="text-secondary fs-14 fw-400 mt-1">{{ translate('Total Price') }}</div>
                </div>
                <div class="col-sm-10">
                    <div class="product-price">
                        <strong id="chosen_price" class="fs-20 fw-700 text-primary">

                        </strong>
                    </div>
                </div>
            </div>

        </form>
    @endif

    @if ($detailedProduct->auction_product)
        @php
            $highest_bid = $detailedProduct->bids->max('amount');
            $min_bid_amount = $highest_bid != null ? $highest_bid + 1 : $detailedProduct->starting_bid;
        @endphp
        @if ($detailedProduct->auction_end_date >= strtotime('now'))
            <div class="mt-4">
                @if (Auth::check() && $detailedProduct->user_id == Auth::user()->id)
                    <span
                        class="badge badge-inline badge-danger">{{ translate('Seller cannot Place Bid to His Own Product') }}</span>
                @else
                    <button type="button" class="btn btn-primary buy-now  fw-600 min-w-150px rounded-0"
                        onclick="bid_modal()">
                        <i class="las la-gavel"></i>
                        @if (Auth::check() && Auth::user()->product_bids->where('product_id', $detailedProduct->id)->first() != null)
                            {{ translate('Change Bid') }}
                        @else
                            {{ translate('Place Bid') }}
                        @endif
                    </button>
                @endif
            </div>
        @endif
    @else
        <!-- Add to cart & Buy now Buttons -->
        <div class="mt-3">
            @if ($detailedProduct->digital == 0)
                @if (
                    (get_setting('product_external_link_for_seller') == 1 &&
                        $detailedProduct->added_by == 'seller' &&
                        $detailedProduct->external_link != null) ||
                        ($detailedProduct->added_by != 'seller' && $detailedProduct->external_link != null))
                    <a type="button" class="btn btn-primary buy-now fw-600 add-to-cart px-4 rounded-0"
                        href="{{ $detailedProduct->external_link }}">
                        <i class="la la-share"></i> {{ translate($detailedProduct->external_link_btn) }}
                    </a>
                @else
                    {{-- Mohammad Hassan --}}
                    <button type="button"
                        class="btn btn-secondary-base mr-2 add-to-cart fw-600 min-w-150px rounded-0 text-white"
                        onclick="addToCart()">
                        <i class="las la-shopping-bag"></i> {{ translate('Add to cart') }}
                    </button>
                    {{-- Mohammad Hassan --}}
                    <button type="button"
                        class="btn btn-primary mr-2 buy-now fw-600 add-to-cart min-w-150px rounded-0"
                        @if (Auth::check()) onclick="buyNow()" @else onclick="showLoginOptions()" @endif>
                        <i class="la la-shopping-cart"></i> {{ translate('Buy Now') }}
                    </button>
                @endif

                {{-- Out of Stock and Pre-Order Buttons --}}
                <button type="button" class="btn btn-secondary out-of-stock fw-600 d-none" disabled>
                    <i class="la la-cart-arrow-down"></i> {{ translate('Out of Stock') }}
                </button>
                {{-- Mohammad Hassan --}}
                <button type="button" class="btn btn-warning out-of-stock fw-600 d-none min-w-150px rounded-0"
                    @if (Auth::check()) data-toggle="modal" data-target="#preOrderModal" @else onclick="showLoginOptions()" @endif>
                    <i class="la la-clock"></i> {{ translate('Pre-Order') }}
                </button>
                <!-- END NEW -->
            @elseif ($detailedProduct->digital == 1)
                {{-- Mohammad Hassan --}}
                <button type="button"
                    class="btn btn-secondary-base mr-2 add-to-cart fw-600 min-w-150px rounded-0 text-white"
                    onclick="addToCart()">
                    <i class="las la-shopping-bag"></i> {{ translate('Add to cart') }}
                </button>
                {{-- Mohammad Hassan --}}
                <button type="button" class="btn btn-primary mr-2 buy-now fw-600 add-to-cart min-w-150px rounded-0"
                    @if (Auth::check()) onclick="buyNow()" @else onclick="showLoginOptions()" @endif>
                    <i class="la la-shopping-cart"></i> {{ translate('Buy Now') }}
                </button>
            @endif
        </div>

        <!-- Promote Link -->
        <div class="d-table width-100 mt-3">
            <div class="d-table-cell">
                @if (Auth::check() &&
                        addon_is_activated('affiliate_system') &&
                        get_affliate_option_status() &&
                        Auth::user()->affiliate_user != null &&
                        Auth::user()->affiliate_user->status)
                    @php
                        if (Auth::check()) {
                            if (Auth::user()->referral_code == null) {
                                Auth::user()->referral_code = substr(Auth::user()->id . Str::random(10), 0, 10);
                                Auth::user()->save();
                            }
                            $referral_code = Auth::user()->referral_code;
                            $referral_code_url =
                                URL::to('/product') .
                                '/' .
                                $detailedProduct->slug .
                                "?product_referral_code=$referral_code";
                        }
                    @endphp
                    <div>
                        <button type="button" id="ref-cpurl-btn" class="btn btn-secondary w-200px rounded-0"
                            data-attrcpy="{{ translate('Copied') }}" onclick="CopyToClipboard(this)"
                            data-url="{{ $referral_code_url }}">{{ translate('Copy the Promote Link') }}</button>
                    </div>
                @endif
            </div>
        </div>

        <!-- Refund -->
        @php
            $refund_sticker = get_setting('refund_sticker');
        @endphp
        @if (addon_is_activated('refund_request'))
            <div class="row no-gutters mt-3">
                <div class="col-sm-2">
                    <div class="text-secondary fs-14 fw-400 mt-2">{{ translate('Refund') }}</div>
                </div>
                <div class="col-sm-10">
                    @if ($detailedProduct->refundable == 1)
                        <a href="{{ route('returnpolicy') }}" target="_blank">
                            @if ($refund_sticker != null)
                                <img src="{{ uploaded_asset($refund_sticker) }}" height="36">
                            @else
                                <img src="{{ static_asset('assets/img/refund-sticker.jpg') }}" height="36">
                            @endif
                        </a>
                        @if ($detailedProduct->refund_note_id != null)
                            <span href="javascript:void(1);" data-toggle="modal" data-target="#refund-note-modal"
                                class="border-bottom border-bottom-4 ml-2 text-secondary-base">
                                {{ translate('Refund Note') }}
                            </span>
                        @endif

                        <a href="{{ route('returnpolicy') }}" class="text-blue hov-text-primary fs-14 ml-3"
                            target="_blank">{{ translate('View Policy') }}</a>
                    @else
                        <div class="text-dark fs-14 fw-400 mt-2">{{ translate('Not Applicable') }}</div>
                    @endif
                </div>
            </div>
        @endif

        <!-- Seller Guarantees -->
        @if ($detailedProduct->digital == 1)
            @if ($detailedProduct->added_by == 'seller')
                <div class="row no-gutters mt-3">
                    <div class="col-2">
                        <div class="text-secondary fs-14 fw-400">{{ translate('Seller Guarantees') }}</div>
                    </div>
                    <div class="col-10">
                        @if ($detailedProduct->user->shop->verification_status == 1)
                            <span class="text-success fs-14 fw-700">{{ translate('Verified seller') }}</span>
                        @else
                            <span class="text-danger fs-14 fw-700">{{ translate('Non verified seller') }}</span>
                        @endif
                    </div>
                </div>
            @endif
        @endif
    @endif

    <!-- Share -->
    <div class="row no-gutters mt-4">
        <div class="col-sm-2">
            <div class="text-secondary fs-14 fw-400 mt-2">{{ translate('Share') }}</div>
        </div>
        <div class="col-sm-10">
            <div class="aiz-share"></div>
        </div>
    </div>
</div>

<!-- NEW: Pre-Order Modal -->
<div class="modal fade" id="preOrderModal" tabindex="-1" role="dialog" aria-labelledby="preOrderModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="preOrderModalLabel">{{ translate('Pre-Order Confirmation') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>{{ translate('This product is currently out of stock but available for pre-order.') }}</p>
                <h6 class="fw-600">{{ translate('Pre-Order Terms:') }}</h6>
                <p class="mb-1">
                    {{ translate('An advance payment of 50% is required to confirm your order.') }}
                </p>
                <p>
                    {{ translate('The remaining 50% will be due upon cash on delivery.') }}
                </p>
                <p class="text-primary">
                    {{ translate('Our team will contact you shortly to process the advance payment after you confirm.') }}
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    data-dismiss="modal">{{ translate('Cancel') }}</button>
                <button type="button" class="btn btn-primary"
                    onclick="confirmPreOrder()">{{ translate('Confirm Pre-Order') }}</button>
            </div>
        </div>
    </div>
</div>
<!-- END NEW -->

<!-- NEW: Pre-Order Javascript -->
<!-- MODIFIED: Pre-Order Javascript -->
<script type="text/javascript">
    function confirmPreOrder() {
        // Add a pre-order flag to the form
        if (!$('#option-choice-form').find('input[name="is_preorder"]').length) {
            $('#option-choice-form').append('<input type="hidden" name="is_preorder" value="1">');
        } else {
            $('#option-choice-form').find('input[name="is_preorder"]').val('1');
        }

        // Hide the modal
        $('#preOrderModal').modal('hide');

        // Call the addToCart function with the 'buyNow' flag set to true
        addToCart(true);
    }
</script>

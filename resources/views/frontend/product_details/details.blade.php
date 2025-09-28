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
        openUserLogin();
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
    <h2 class="mb-2 fs-18 fw-800 text-dark">
        {{ $detailedProduct->getTranslation('name') }}
    </h2>
    <hr>

    <!-- Color Section -->
    <div class="mb-4">
        <h5 class="mb-3">Color : Black</h5>
        <div class="d-flex flex-wrap">
            <div class="color-option mr-3 mb-2 p-2 border-2 selected-color" data-color="Black"
                style="border: 2px solid #6366f1; border-radius: 8px;">
                <img src="https://via.placeholder.com/60x60/000000/FFFFFF?text=Black" alt="Black"
                    style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
            </div>
            <div class="color-option mr-3 mb-2 p-2 border" data-color="Brown"
                style="border: 1px solid #ddd; border-radius: 8px; cursor: pointer;">
                <img src="https://via.placeholder.com/60x60/8B4513/FFFFFF?text=Brown" alt="Brown"
                    style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
            </div>
            <div class="color-option mr-3 mb-2 p-2 border" data-color="Gray"
                style="border: 1px solid #ddd; border-radius: 8px; cursor: pointer;">
                <img src="https://via.placeholder.com/60x60/A9A9A9/FFFFFF?text=Gray" alt="Gray"
                    style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
            </div>
            <div class="color-option mr-3 mb-2 p-2 border" data-color="Red"
                style="border: 1px solid #ddd; border-radius: 8px; cursor: pointer;">
                <img src="https://via.placeholder.com/60x60/DC143C/FFFFFF?text=Red" alt="Red"
                    style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
            </div>
            <div class="color-option mr-3 mb-2 p-2 border" data-color="Tan"
                style="border: 1px solid #ddd; border-radius: 8px; cursor: pointer;">
                <img src="https://via.placeholder.com/60x60/DEB887/FFFFFF?text=Tan" alt="Tan"
                    style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
            </div>
            <div class="color-option mr-3 mb-2 p-2 border" data-color="Blue"
                style="border: 1px solid #ddd; border-radius: 8px; cursor: pointer;">
                <img src="https://via.placeholder.com/60x60/4682B4/FFFFFF?text=Blue" alt="Blue"
                    style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
            </div>
        </div>
    </div>

    {{-- START: DYNAMIC PRICE TIERS --}}
    @if ($detailedProduct->priceTiers && count($detailedProduct->priceTiers) > 0)
        <div class="d-flex mb-3" id="price-tier-options">
            @foreach ($detailedProduct->priceTiers as $key => $tier)
                <div class="price-tier-item text-center rounded-lg p-3 mr-3 @if ($key == 0) active @endif"
                    data-price="{{ $tier->price }}" data-min-qty="{{ $tier->min_qty }}"
                    onclick="selectPriceTier(this)">
                    <div class="fs-18 fw-600">৳{{ $tier->price }}</div>
                    <div class="fs-13">{{ $tier->min_qty }} or more</div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Default Price Tiers if no dynamic tiers -->
        <div class="d-flex mb-3" id="price-tier-options">
            <div class="price-tier-item text-center rounded-lg p-3 mr-3 active" data-price="20.00" data-min-qty="3"
                onclick="selectPriceTier(this)">
                <div class="fs-18 fw-600">৳20.00</div>
                <div class="fs-13">3 or more</div>
            </div>
            <div class="price-tier-item text-center rounded-lg p-3 mr-3" data-price="17.95" data-min-qty="10"
                onclick="selectPriceTier(this)">
                <div class="fs-18 fw-600">৳17.95</div>
                <div class="fs-13">10 or more</div>
            </div>
            <div class="price-tier-item text-center rounded-lg p-3 mr-3" data-price="15.00" data-min-qty="50"
                onclick="selectPriceTier(this)">
                <div class="fs-18 fw-600">৳15.00</div>
                <div class="fs-13">50 or more</div>
            </div>
            <div class="price-tier-item text-center rounded-lg p-3 mr-3" data-price="12.94" data-min-qty="100"
                onclick="selectPriceTier(this)">
                <div class="fs-18 fw-600">৳12.94</div>
                <div class="fs-13">100 or more</div>
            </div>
        </div>
    @endif
    {{-- END: DYNAMIC PRICE TIERS --}}

    <!-- Size Table -->
    <div class="mb-4">
        <h5 class="mb-3">Size</h5>
        <div class="size-table-container"
            style="max-height: 300px; overflow-y: auto; border: 1px solid #e0e0e0; border-radius: 8px;">
            <table class="table table-bordered mb-0" id="sizeTable">
                <thead class="bg-light sticky-top">
                    <tr style="height: 45px;">
                        <th style="padding: 8px 12px;">Size</th>
                        <th style="padding: 8px 12px;">Unit Price</th>
                        <th style="padding: 8px 12px;">Total Price</th>
                        <th style="padding: 8px 12px;">Add/Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <tr data-size="M" data-original-price="393" style="height: 60px;">
                        <td style="padding: 8px 12px;">M</td>
                        <td class="unit-price" style="padding: 8px 12px;">৳ 393.00</td>
                        <td class="total-price" style="padding: 8px 12px;">৳ 2358.00</td>
                        <td style="padding: 8px 12px;">
                            <div class="d-flex align-items-center justify-content-center">
                                <div class="quantity-control d-flex align-items-center mb-1 active" data-row-id="M">
                                    <button type="button" class="btn btn-sm minus-btn"
                                        style="background: #6366f1; color: white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;"
                                        onclick="decreaseQuantity(this)">-</button>
                                    <input type="number" class="quantity-input mx-2 text-center" value="6"
                                        min="1" style="width: 40px; border: none; height: 30px;"
                                        onchange="updateTotal(this)" readonly>
                                    <button type="button" class="btn btn-sm plus-btn"
                                        style="background: #6366f1; color: white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;"
                                        onclick="increaseQuantity(this)">+</button>
                                </div>
                            </div>
                            <div class="text-center">
                                <small class="text-muted">Stock 1923</small>
                            </div>
                        </td>
                    </tr>
                    <tr data-size="L" data-original-price="393" style="height: 60px;">
                        <td style="padding: 8px 12px;">L</td>
                        <td class="unit-price" style="padding: 8px 12px;">৳ 393.00</td>
                        <td class="total-price" style="padding: 8px 12px;">৳ 393.00</td>
                        <td style="padding: 8px 12px;">
                            <div class="d-flex align-items-center justify-content-center">
                                <button type="button" class="btn add-btn" data-row-id="L"
                                    style="background: #6366f1; color: white; border-radius: 8px; padding: 6px 20px;"
                                    onclick="addToCartRow(this)">Add</button>
                                <div class="quantity-control d-flex align-items-center mb-1" data-row-id="L">
                                    <button type="button" class="btn btn-sm minus-btn"
                                        style="background: #6366f1; color: white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;"
                                        onclick="decreaseQuantity(this)">-</button>
                                    <input type="number" class="quantity-input mx-2 text-center" value="1"
                                        min="1" style="width: 40px; border: none; height: 30px;"
                                        onchange="updateTotal(this)" readonly>
                                    <button type="button" class="btn btn-sm plus-btn"
                                        style="background: #6366f1; color: white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;"
                                        onclick="increaseQuantity(this)">+</button>
                                </div>
                            </div>
                            <div class="text-center">
                                <small class="text-muted">Stock 1879</small>
                            </div>
                        </td>
                    </tr>
                    <tr data-size="XL" data-original-price="393" style="height: 60px;">
                        <td style="padding: 8px 12px;">XL</td>
                        <td class="unit-price" style="padding: 8px 12px;">৳ 393.00</td>
                        <td class="total-price" style="padding: 8px 12px;">৳ 393.00</td>
                        <td style="padding: 8px 12px;">
                            <div class="d-flex align-items-center justify-content-center">
                                <button type="button" class="btn add-btn" data-row-id="XL"
                                    style="background: #6366f1; color: white; border-radius: 8px; padding: 6px 20px;"
                                    onclick="addToCartRow(this)">Add</button>
                                <div class="quantity-control d-flex align-items-center mb-1" data-row-id="XL">
                                    <button type="button" class="btn btn-sm minus-btn"
                                        style="background: #6366f1; color: white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;"
                                        onclick="decreaseQuantity(this)">-</button>
                                    <input type="number" class="quantity-input mx-2 text-center" value="1"
                                        min="1" style="width: 40px; border: none; height: 30px;"
                                        onchange="updateTotal(this)" readonly>
                                    <button type="button" class="btn btn-sm plus-btn"
                                        style="background: #6366f1; color: white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;"
                                        onclick="increaseQuantity(this)">+</button>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <tr data-size="S" data-original-price="2178" style="height: 60px;">
                        <td style="padding: 8px 12px;">S</td>
                        <td class="unit-price" style="padding: 8px 12px;">৳ 2178</td>
                        <td class="total-price" style="padding: 8px 12px;">৳ 2178</td>
                        <td style="padding: 8px 12px;">
                            <div class="d-flex align-items-center justify-content-center">
                                <button type="button" class="btn add-btn" data-row-id="S"
                                    style="background: #6366f1; color: white; border-radius: 8px; padding: 6px 20px;"
                                    onclick="addToCartRow(this)">Add</button>
                                <div class="quantity-control d-flex align-items-center mb-1" data-row-id="S">
                                    <button type="button" class="btn btn-sm minus-btn"
                                        style="background: #6366f1; color: white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;"
                                        onclick="decreaseQuantity(this)">-</button>
                                    <input type="number" class="quantity-input mx-2 text-center" value="1"
                                        min="1" style="width: 40px; border: none; height: 30px;"
                                        onchange="updateTotal(this)" readonly>
                                    <button type="button" class="btn btn-sm plus-btn"
                                        style="background: #6366f1; color: white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;"
                                        onclick="increaseQuantity(this)">+</button>
                                </div>
                            </div>
                            <div class="text-center">
                                <small class="text-muted">2669</small>
                            </div>
                        </td>
                    </tr>
                    <tr data-size="M2" data-original-price="2178" style="height: 60px;">
                        <td style="padding: 8px 12px;">M</td>
                        <td class="unit-price" style="padding: 8px 12px;">৳ 2178</td>
                        <td class="total-price" style="padding: 8px 12px;">৳ 2178</td>
                        <td style="padding: 8px 12px;">
                            <div class="d-flex align-items-center justify-content-center">
                                <button type="button" class="btn add-btn" data-row-id="M2"
                                    style="background: #6366f1; color: white; border-radius: 8px; padding: 6px 20px;"
                                    onclick="addToCartRow(this)">Add</button>
                                <div class="quantity-control d-flex align-items-center mb-1" data-row-id="M2">
                                    <button type="button" class="btn btn-sm minus-btn"
                                        style="background: #6366f1; color: white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;"
                                        onclick="decreaseQuantity(this)">-</button>
                                    <input type="number" class="quantity-input mx-2 text-center" value="1"
                                        min="1" style="width: 40px; border: none; height: 30px;"
                                        onchange="updateTotal(this)" readonly>
                                    <button type="button" class="btn btn-sm plus-btn"
                                        style="background: #6366f1; color: white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;"
                                        onclick="increaseQuantity(this)">+</button>
                                </div>
                            </div>
                            <div class="text-center">
                                <small class="text-muted">2610</small>
                            </div>
                        </td>
                    </tr>
                    <tr data-size="XXL" data-original-price="2178" style="height: 60px;">
                        <td style="padding: 8px 12px;">XXL</td>
                        <td class="unit-price" style="padding: 8px 12px;">৳ 2178</td>
                        <td class="total-price" style="padding: 8px 12px;">৳ 2178</td>
                        <td style="padding: 8px 12px;">
                            <div class="d-flex align-items-center justify-content-center">
                                <button type="button" class="btn add-btn" data-row-id="XXL"
                                    style="background: #6366f1; color: white; border-radius: 8px; padding: 6px 20px;"
                                    onclick="addToCartRow(this)">Add</button>
                                <div class="quantity-control d-flex align-items-center mb-1" data-row-id="XXL">
                                    <button type="button" class="btn btn-sm minus-btn"
                                        style="background: #6366f1; color: white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;"
                                        onclick="decreaseQuantity(this)">-</button>
                                    <input type="number" class="quantity-input mx-2 text-center" value="1"
                                        min="1" style="width: 40px; border: none; height: 30px;"
                                        onchange="updateTotal(this)" readonly>
                                    <button type="button" class="btn btn-sm plus-btn"
                                        style="background: #6366f1; color: white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;"
                                        onclick="increaseQuantity(this)">+</button>
                                </div>
                            </div>
                            <div class="text-center">
                                <small class="text-muted">2898</small>
                            </div>
                        </td>
                    </tr>
                    <tr data-size="XL2" data-original-price="2178" style="height: 60px;">
                        <td style="padding: 8px 12px;">XL</td>
                        <td class="unit-price" style="padding: 8px 12px;">৳ 2178</td>
                        <td class="total-price" style="padding: 8px 12px;">৳ 2178</td>
                        <td style="padding: 8px 12px;">
                            <div class="d-flex align-items-center justify-content-center">
                                <button type="button" class="btn add-btn" data-row-id="XL2"
                                    style="background: #6366f1; color: white; border-radius: 8px; padding: 6px 20px;"
                                    onclick="addToCartRow(this)">Add</button>
                                <div class="quantity-control d-flex align-items-center mb-1" data-row-id="XL2">
                                    <button type="button" class="btn btn-sm minus-btn"
                                        style="background: #6366f1; color: white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;"
                                        onclick="decreaseQuantity(this)">-</button>
                                    <input type="number" class="quantity-input mx-2 text-center" value="1"
                                        min="1" style="width: 40px; border: none; height: 30px;"
                                        onchange="updateTotal(this)" readonly>
                                    <button type="button" class="btn btn-sm plus-btn"
                                        style="background: #6366f1; color: white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;"
                                        onclick="increaseQuantity(this)">+</button>
                                </div>
                            </div>
                            <div class="text-center">
                                <small class="text-muted">2849</small>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="text-center mt-2">
            <button type="button" class="btn btn-link text-muted" onclick="toggleScrollMore()">
                <span id="scrollMoreText">▼ Scroll More</span>
            </button>
        </div>
    </div>

    <style>
        /* Add some basic styling for the active state */
        .price-tier-item {
            background-color: #f3f3f3;
            color: #333;
            border: 1px solid #e0e0e0;
            min-width: 120px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .price-tier-item.active {
            background-color: #6366f1;
            color: white;
            border-color: #6366f1;
        }

        .color-option {
            transition: all 0.3s ease;
        }

        .color-option:hover {
            border-color: #6366f1 !important;
        }

        .selected-color {
            border-color: #6366f1 !important;
        }

        .size-table-container {
            position: relative;
        }

        .size-table-container::-webkit-scrollbar {
            width: 8px;
        }

        .size-table-container::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .size-table-container::-webkit-scrollbar-thumb {
            background: #6366f1;
            border-radius: 10px;
        }

        .size-table-container::-webkit-scrollbar-thumb:hover {
            background: #4f46e5;
        }

        .sticky-top {
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .quantity-input:focus {
            outline: none;
            border: 1px solid #6366f1 !important;
        }

        .quantity-control {
            display: none;
        }

        .quantity-control.active {
            display: flex !important;
        }

        .add-btn.hidden {
            display: none;
        }

        .btn:hover {
            transform: scale(1.05);
        }

        .btn {
            transition: all 0.2s ease;
        }
    </style>

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
                    <button type="button" class="btn btn-primary buy-now fw-600 min-w-150px rounded-0"
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
                    <button type="button"
                        class="btn btn-secondary-base mr-2 add-to-cart fw-600 min-w-150px rounded-0 text-white"
                        @if (Auth::check() || get_Setting('guest_checkout_activation') == 1) onclick="addToCart()" @else onclick="showLoginModal()" @endif>
                        <i class="las la-shopping-bag"></i> {{ translate('Add to cart') }}
                    </button>
                    <button type="button"
                        class="btn btn-primary mr-2 buy-now fw-600 add-to-cart min-w-150px rounded-0"
                        @if (Auth::check() || get_Setting('guest_checkout_activation') == 1) onclick="addToCart()" @else onclick="showLoginModal()" @endif>
                        <i class="la la-shopping-cart"></i> {{ translate('Buy Now') }}
                    </button>
                @endif

                {{-- Out of Stock and Pre-Order Buttons --}}
                <button type="button" class="btn btn-secondary out-of-stock fw-600 d-none" disabled>
                    <i class="la la-cart-arrow-down"></i> {{ translate('Out of Stock') }}
                </button>
                <button type="button" class="btn btn-warning out-of-stock fw-600 d-none min-w-150px rounded-0"
                    @if (Auth::check() || get_Setting('guest_checkout_activation') == 1) data-toggle="modal" data-target="#preOrderModal" @else onclick="showLoginModal()" @endif>
                    <i class="la la-clock"></i> {{ translate('Pre-Order') }}
                </button>
            @elseif ($detailedProduct->digital == 1)
                <button type="button"
                    class="btn btn-secondary-base mr-2 add-to-cart fw-600 min-w-150px rounded-0 text-white"
                    @if (Auth::check() || get_Setting('guest_checkout_activation') == 1) onclick="addToCart()" @else onclick="showLoginModal()" @endif>
                    <i class="las la-shopping-bag"></i> {{ translate('Add to cart') }}
                </button>
                <button type="button" class="btn btn-primary mr-2 buy-now fw-600 add-to-cart min-w-150px rounded-0"
                    @if (Auth::check() || get_Setting('guest_checkout_activation') == 1) onclick="addToCart()" @else onclick="showLoginModal()" @endif>
                    <i class="la la-shopping-cart"></i> {{ translate('Buy Now') }}
                </button>
            @endif
        </div>
        <hr>
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

        <!-- Estimate Shipping Time -->
        @if ($detailedProduct->est_shipping_days)
            <div class="col-auto fs-14 mt-1 " style="margin-left: -13px;">
                <small class="mr-1 opacity-50 fs-14">{{ translate('Estimate Shipping Time') }}:</small>
                <span class="fw-500">{{ $detailedProduct->est_shipping_days }} {{ translate('Days') }}</span>
            </div>
        @endif

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

<script type="text/javascript">
    let selectedTierPrice = null;

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

    function selectPriceTier(element) {
        // Remove active class from all tiers
        $('#price-tier-options .price-tier-item').removeClass('active');
        // Add active class to the clicked one
        $(element).addClass('active');

        // Get data from the selected tier
        var price = parseFloat($(element).data('price'));
        var minQty = $(element).data('min-qty');

        // Store selected tier price
        selectedTierPrice = price;

        // Update all unit prices in the table
        updateAllUnitPrices();

        // Update the main price display
        $('#chosen_price').text('৳' + price);

        // Update the quantity input's value and minimum attribute
        var quantityInput = $('input[name="quantity"]');
        quantityInput.val(minQty);
        quantityInput.attr('min', minQty);

        // Trigger the change event for any other scripts that might be listening
        quantityInput.trigger('change');
    }

    function updateAllUnitPrices() {
        $('#sizeTable tbody tr').each(function() {
            var row = $(this);
            var originalPrice = parseFloat(row.data('original-price'));
            var newPrice = selectedTierPrice || originalPrice;

            // Update unit price
            row.find('.unit-price').text('৳ ' + newPrice.toFixed(2));

            // Update total price based on current quantity
            var quantity = parseInt(row.find('.quantity-input').val()) || 1;
            var total = newPrice * quantity;
            row.find('.total-price').text('৳ ' + total.toFixed(2));
        });
    }

    // Color selection functionality
    $('.color-option').click(function() {
        $('.color-option').removeClass('selected-color');
        $(this).addClass('selected-color');

        var selectedColor = $(this).data('color');
        $('h5:contains("Color")').text('Color : ' + selectedColor);
    });

    // Add to cart row functionality
    function addToCartRow(button) {
        var rowId = $(button).data('row-id');
        var row = $('tr[data-size="' + rowId + '"]');

        // Hide Add button
        $(button).addClass('hidden');

        // Show quantity controls
        var quantityControl = row.find('.quantity-control[data-row-id="' + rowId + '"]');
        quantityControl.addClass('active');

        // Set initial quantity to 1 and update total
        var quantityInput = quantityControl.find('.quantity-input');
        quantityInput.val(1);
        updateTotal(quantityInput[0]);
    }

    // Size table quantity functions
    function increaseQuantity(button) {
        var input = $(button).siblings('.quantity-input');
        var currentVal = parseInt(input.val());
        input.val(currentVal + 1);
        updateTotal(input[0]);
    }

    function decreaseQuantity(button) {
        var input = $(button).siblings('.quantity-input');
        var currentVal = parseInt(input.val());
        if (currentVal > 1) {
            input.val(currentVal - 1);
            updateTotal(input[0]);
        } else {
            // If quantity becomes 0, hide quantity controls and show Add button
            var quantityControl = $(button).closest('.quantity-control');
            var row = $(button).closest('tr');
            var rowId = quantityControl.data('row-id');

            quantityControl.removeClass('active');
            row.find('.add-btn[data-row-id="' + rowId + '"]').removeClass('hidden');

            // Reset to original price calculation
            input.val(1);
            updateTotal(input[0]);
        }
    }

    function updateTotal(input) {
        var quantity = parseInt($(input).val());
        var row = $(input).closest('tr');
        var originalPrice = parseFloat(row.data('original-price'));
        var unitPrice = selectedTierPrice || originalPrice;
        var total = quantity * unitPrice;

        row.find('.total-price').text('৳ ' + total.toFixed(2));
    }

    function addToCart(size) {
        console.log('Adding ' + size + ' to cart');
        // Add your cart functionality here
    }

    function toggleScrollMore() {
        var container = $('.size-table-container');
        var text = $('#scrollMoreText');

        if (container.css('max-height') === '300px') {
            container.css('max-height', 'none');
            text.text('▲ Show Less');
        } else {
            container.css('max-height', '300px');
            text.text('▼ Scroll More');
        }
    }

    // On page load, set the initial price and quantity from the first (default active) tier
    $(document).ready(function() {
        var firstTier = $('#price-tier-options .price-tier-item.active');
        if (firstTier.length) {
            selectPriceTier(firstTier[0]);
        }
    });
</script>

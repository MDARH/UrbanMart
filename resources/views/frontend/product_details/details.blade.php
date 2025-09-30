@php
    // Get product discount rate
    $product_discount_rate = 0;
    if (isset($detailedProduct->discount) && $detailedProduct->discount_type == 'percent') {
        $product_discount_rate = (float) $detailedProduct->discount;
    }

    // Get product tax rate
    $product_tax_rate = 0;
    if (isset($detailedProduct->tax) && $detailedProduct->tax_type == 'percent') {
        $product_tax_rate = (float) $detailedProduct->tax;
    }

    // Prepare price tiers for JavaScript: Sort by min_qty descending for easier logic
    $price_tiers_json = '[]';
    if (
        Auth::check() &&
        Auth::user()->user_type == 'wholesaler' &&
        $detailedProduct->priceTiers &&
        count($detailedProduct->priceTiers) > 0
    ) {
        $tiers = collect($detailedProduct->priceTiers)
            ->map(function ($tier) {
                return ['min_qty' => (int) $tier->min_qty, 'price' => (float) $tier->price];
            })
            ->sortByDesc('min_qty')
            ->values(); // Sorting high to low is key for the JS logic
        $price_tiers_json = json_encode($tiers);
    }

    // Mohammad Hassan - Calculate base and discounted prices for display
    $base_price = $detailedProduct->unit_price;
    $discounted_price = $base_price;
    if ($detailedProduct->discount_type == 'percent' && $detailedProduct->discount > 0) {
        $discounted_price = $base_price - ($base_price * $detailedProduct->discount / 100);
    } elseif ($detailedProduct->discount_type == 'amount' && $detailedProduct->discount > 0) {
        $discounted_price = $base_price - $detailedProduct->discount;
    }
    $has_discount = $base_price > $discounted_price;
@endphp

<div class="text-left">
    <!-- Product Name -->
    <h2 class="mb-3 fs-20 fw-800 text-dark">
        {{ $detailedProduct->getTranslation('name') }}
    </h2>

    <!-- Mohammad Hassan - Enhanced Price Display Section -->
    <div class="price-section mb-4 p-3 bg-light rounded-lg border" style="max-width: calc(100% - 145px);">
        <div class="d-flex align-items-center flex-wrap">
            @if($has_discount)
                <div class="mr-3 mb-2">
                    <span class="fs-24 fw-700 text-primary">৳{{ number_format($discounted_price, 2) }}</span>
                    <small class="text-muted ml-1">{{ translate('Discounted Price') }}</small>
                </div>
                <div class="mr-3 mb-2">
                    <del class="fs-18 fw-500 text-muted">৳{{ number_format($base_price, 2) }}</del>
                    <small class="text-muted ml-1">{{ translate('Original Price') }}</small>
                </div>
                <div class="mb-2">
                    <span class="badge badge-danger fs-12 fw-600">
                        {{ $detailedProduct->discount_type == 'percent' ? $detailedProduct->discount.'% OFF' : '৳'.$detailedProduct->discount.' OFF' }}
                    </span>
                </div>
            @else
                <div class="mr-3 mb-2">
                    <span class="fs-24 fw-700 text-primary">৳{{ number_format($base_price, 2) }}</span>
                    <small class="text-muted ml-1">{{ translate('Price') }}</small>
                </div>
            @endif
        </div>

        @if($detailedProduct->tax > 0)
            <div class="mt-2">
                <small class="text-info">
                    <i class="fas fa-info-circle"></i>
                    {{ translate('Tax') }}: {{ $detailedProduct->tax_type == 'percent' ? $detailedProduct->tax.'%' : '৳'.$detailedProduct->tax }}
                </small>
            </div>
        @endif
    </div>

    <hr>

    <!-- Dynamic Color Section -->
    @if ($detailedProduct->colors != null && count(json_decode($detailedProduct->colors)) > 0)
        <div class="mb-4">
            <h5 class="mb-3 fs-16 fw-600">{{ translate('Color') }} :
                <span class="text-primary fw-700"
                    id="selected-color-name">{{ get_single_color_name(json_decode($detailedProduct->colors)[0]) }}</span>
            </h5>
            <div class="d-flex flex-wrap" id="color-options">
                @foreach (json_decode($detailedProduct->colors) as $key => $color)
                    <div class="color-option mr-3 mb-2 p-1 border @if ($key == 0) selected-color @endif"
                        data-color="{{ get_single_color_name($color) }}" data-color-value="{{ $color }}"
                        style="border-width: @if ($key == 0) 2px @else 1px @endif; border-style: solid; border-color: @if ($key == 0) #3D52A0 @else #ddd @endif; border-radius: 8px; cursor: pointer;"
                        onclick="selectColor(this, '{{ get_single_color_name($color) }}', '{{ $color }}')">
                        <div class="color-swatch"
                            style="width: 56px; height: 50px; background-color: {{ $color }}; border-radius: 4px; position: relative;">
                            <span class="color-name"
                                style="position: absolute; bottom: 5px; left: 50%; transform: translateX(-50%);
                                         font-size: 10px; color: {{ isDarkColor($color) ? '#fff' : '#000' }};
                                         text-shadow: 1px 1px 1px {{ isDarkColor($color) ? '#000' : '#fff' }};">
                                {{ get_single_color_name($color) }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- START: DYNAMIC PRICE TIERS (Only for Wholesalers) --}}
    @if (Auth::check() &&
            Auth::user()->user_type == 'wholesaler' &&
            $detailedProduct->priceTiers &&
            count($detailedProduct->priceTiers) > 0)
        <div class="mb-4">
            <h5 class="mb-3 fs-16 fw-600">{{ translate('Wholesale Price Tiers') }}</h5>
            <div class="d-flex flex-wrap mb-3" id="price-tier-options"
                style="gap: 12px; justify-content: flex-start; margin-right: 145px;">
                @foreach (collect($detailedProduct->priceTiers)->sortBy('min_qty') as $key => $tier)
                    <div class="price-tier-item text-center rounded-lg p-3 mb-2" data-price="{{ $tier->price }}"
                        data-min-qty="{{ $tier->min_qty }}" onclick="selectPriceTier(this)"
                        style="flex: 1 1 calc(25% - 12px); min-width: 110px;">
                        <div class="fs-18 fw-600">৳{{ $tier->price }}</div>
                        <div class="fs-13">{{ $tier->min_qty }} or more</div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
    {{-- END: DYNAMIC PRICE TIERS --}}

    <!-- Size/Variant Table -->
    <div class="mb-4">
        <h5 class="mb-3 fs-16 fw-600">{{ translate('Model/Size') }}</h5>
        <div class="size-table-container"
            style="max-height: 300px; overflow-y: auto; border: 1px solid #e0e0e0; border-radius: 8px;margin-right: 145px;">
            <table class="table table-bordered mb-0" id="sizeTable">
                <thead class="bg-light sticky-top">
                    <tr style="height: 45px;">
                        @php
                            $attributeName = '';
                            if ($detailedProduct->choice_options != null) {
                                $choiceOptions = json_decode($detailedProduct->choice_options);
                                if (count($choiceOptions) > 0) {
                                    $attributeName = get_single_attribute_name($choiceOptions[0]->attribute_id);
                                }
                            }
                        @endphp
                        <th style="padding: 8px 12px;">{{ $attributeName ?: translate('Variant') }}</th>
                        <th style="padding: 8px 12px;">{{ translate('Unit Price') }}</th>
                        <th style="padding: 8px 12px;">{{ translate('Total Price') }}</th>
                        <th style="padding: 8px 12px;">{{ translate('Add/Quantity') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($detailedProduct->stocks as $key => $stock)
                        @php
                            $variantId = $stock->variant ?? $stock->id;
                            $variantName = $stock->variant ?? translate('Default');
                        @endphp
                        <tr data-size="{{ $variantId }}" data-original-price="{{ $stock->price }}"
                            data-stock-qty="{{ $stock->qty }}" style="height: 60px;">

                            <td style="padding: 8px 12px;">{{ $variantName }}</td>
                            <td class="unit-price" style="padding: 8px 12px;">৳ {{ number_format($stock->price, 2) }}
                            </td>
                            <td class="total-price" style="padding: 8px 12px;">৳ 0.00</td>
                            <td style="padding: 8px 12px;">
                                <div class="d-flex align-items-center justify-content-end">
                                    <button type="button" class="btn add-btn" data-row-id="{{ $variantId }}"
                                        style="background: #3D52A0; color: white; border-radius: 8px; padding: 6px 20px;"
                                        onclick="addToCartRow(this)">{{ translate('Add') }}</button>
                                    <div class="quantity-control d-flex align-items-center"
                                        data-row-id="{{ $variantId }}" style="display: none;">
                                        <button type="button" class="btn btn-sm minus-btn"
                                            style="background: #3D52A0; color: white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;"
                                            onclick="decreaseQuantity(this)">-</button>
                                        <input type="number" class="quantity-input mx-2 text-center" value="0"
                                            min="0" style="width: 40px; height: 30px;">
                                        <button type="button" class="btn btn-sm plus-btn"
                                            style="background: #3D52A0; color: white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;"
                                            onclick="increaseQuantity(this)">+</button>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <small class="text-muted stock-text">{{ translate('Stock') }}:
                                        {{ $stock->qty }}</small>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @if ($detailedProduct->auction_product != 1)
        <form id="option-choice-form">
            @csrf
            <input type="hidden" name="id" value="{{ $detailedProduct->id }}">
            <input type="hidden" name="quantity" value="0">

            <!-- Mohammad Hassan - Order Summary section removed -->
        </form>
    @endif

    {{-- Mohammad Hassan - Enhanced Purchase Buttons --}}
    @if (!$detailedProduct->auction_product)
        <div class="mt-4 mb-3">
            <div class="d-flex flex-wrap gap-3">
                <button type="button" class="btn btn-info add-to-cart fw-600 px-4 py-2 rounded-lg text-white"
                    style="min-width: 160px; background: #17a2b8; border: none;"
                    @if (Auth::check() || get_Setting('guest_checkout_activation') == 1) onclick="addToCartFromTable()" @else onclick="showLoginModal()" @endif>
                    <i class="las la-shopping-bag mr-1"></i> {{ translate('Add to Cart') }}
                </button>
                <button type="button" class="btn btn-primary buy-now fw-600 px-4 py-2 rounded-lg"
                    style="min-width: 160px; background: #3D52A0; border: none;"
                    @if (Auth::check() || get_Setting('guest_checkout_activation') == 1) onclick="buyNowFromTable()" @else onclick="showLoginModal()" @endif>
                    <i class="la la-shopping-cart mr-1"></i> {{ translate('Buy Now') }}
                </button>
            </div>
            <div class="mt-2">
                <small class="text-muted">
                    <i class="fas fa-shield-alt text-success"></i>
                    {{ translate('Secure checkout with multiple payment options') }}
                </small>
            </div>
        </div>
        <hr>
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

<style>
    .price-tier-item {
        background-color: #f8f9fa;
        color: #333;
        border: 2px solid #e9ecef;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .price-tier-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    .price-tier-item.active {
        background-color: #3D52A0;
        color: white;
        border-color: #3D52A0;
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(61, 82, 160, 0.3);
    }

    .color-option {
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .color-option:hover {
        border-color: #3D52A0 !important;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    .selected-color {
        border-color: #3D52A0 !important;
        border-width: 2px !important;
        box-shadow: 0 4px 8px rgba(61, 82, 160, 0.2);
    }

    .size-table-container::-webkit-scrollbar {
        width: 8px;
    }

    .size-table-container::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    .size-table-container::-webkit-scrollbar-thumb {
        background: #3D52A0;
        border-radius: 4px;
    }

    .size-table-container::-webkit-scrollbar-thumb:hover {
        background: #2a3d7a;
    }

    .sticky-top {
        position: sticky;
        top: 0;
        z-index: 10;
        background: #f8f9fa !important;
    }

    .quantity-control {
        display: none !important;
    }

    .quantity-control.active {
        display: flex !important;
    }

    .add-btn {
        display: block !important;
        transition: all 0.3s ease;
    }

    .add-btn:hover {
        background: #2a3d7a !important;
        transform: translateY(-1px);
    }

    .add-btn.hidden {
        display: none !important;
    }

    .price-section {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-left: 4px solid #3D52A0;
    }

    .btn {
        transition: all 0.3s ease;
    }

    .btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    .gap-3 {
        gap: 1rem;
    }
</style>

<script type="text/javascript">
    const PRODUCT_ID = {{ $detailedProduct->id }};
    const LOCAL_STORAGE_KEY = 'cart_state_' + PRODUCT_ID;
    const GLOBAL_DISCOUNT_PERCENT = {{ $product_discount_rate }};
    const GLOBAL_TAX_PERCENT = {{ $product_tax_rate }};
    const PRICE_TIERS = {!! $price_tiers_json !!};

    function saveCartState() {
        const selectedItems = extractSelectedItems();
        const stateToSave = {
            items: selectedItems
        };
        if (selectedItems.length > 0) {
            localStorage.setItem(LOCAL_STORAGE_KEY, JSON.stringify(stateToSave));
        } else {
            localStorage.removeItem(LOCAL_STORAGE_KEY);
        }
    }

    function loadCartState() {
        const savedState = localStorage.getItem(LOCAL_STORAGE_KEY);
        if (!savedState) return;
        try {
            const state = JSON.parse(savedState);
            if (Array.isArray(state.items) && state.items.length > 0) {
                state.items.forEach(item => {
                    const row = $(`tr[data-size="${item.size}"]`);
                    if (row.length && item.quantity > 0) {
                        row.find('.add-btn').addClass('hidden');
                        row.find('.quantity-control').addClass('active');
                        row.find('.quantity-input').val(item.quantity);
                    }
                });
            }
            updateGrandTotal();
        } catch (e) {
            console.error("Failed to load cart state:", e);
            localStorage.removeItem(LOCAL_STORAGE_KEY);
        }
    }

    function selectPriceTier(element) {
        const minQty = parseInt($(element).data('min-qty'));
        let totalQuantity = 0;
        $('#sizeTable .quantity-input').each(function() {
            if ($(this).closest('.quantity-control').hasClass('active')) {
                totalQuantity += parseInt($(this).val());
            }
        });

        if (totalQuantity < minQty) {
            const neededQty = minQty - totalQuantity;
            let targetRow = $('#sizeTable tbody tr').filter(function() {
                return $(this).find('.quantity-control').hasClass('active');
            }).first();

            if (targetRow.length === 0) {
                targetRow = $('#sizeTable tbody tr').first();
                const addBtn = targetRow.find('.add-btn');
                if (addBtn.length) addToCartRow(addBtn[0], 0);
            }

            const input = targetRow.find('.quantity-input');
            const currentVal = parseInt(input.val());
            const maxStock = parseInt(targetRow.data('stock-qty'));
            const newQty = Math.min(currentVal + neededQty, maxStock);
            input.val(newQty);
        }
        updateGrandTotal();
    }

    function updateGrandTotal() {
        let totalQuantity = 0;
        let totalBaseSubtotal = 0;
        $('#sizeTable tbody tr').each(function() {
            if ($(this).find('.quantity-control').hasClass('active')) {
                totalQuantity += parseInt($(this).find('.quantity-input').val()) || 0;
            }
        });

        let activeTierPrice = null;
        let activeMinQty = 0;
        if (PRICE_TIERS.length > 0) {
            for (const tier of PRICE_TIERS) {
                if (totalQuantity >= tier.min_qty) {
                    activeTierPrice = tier.price;
                    activeMinQty = tier.min_qty;
                    break;
                }
            }
        }

        $('#price-tier-options .price-tier-item').removeClass('active');
        if (activeMinQty > 0) {
            $(`#price-tier-options .price-tier-item[data-min-qty="${activeMinQty}"]`).addClass('active');
        }

        $('#sizeTable tbody tr').each(function() {
            const row = $(this);
            const originalPrice = parseFloat(row.data('original-price'));
            const unitPrice = activeTierPrice !== null ? activeTierPrice : originalPrice;

            row.find('.unit-price').text('৳ ' + unitPrice.toFixed(2));

            if (row.find('.quantity-control').hasClass('active')) {
                const quantity = parseInt(row.find('.quantity-input').val()) || 0;

                // ***** CHANGE HERE: Show only the base total price in the total price column *****
                row.find('.total-price').text('৳ ' + (unitPrice * quantity).toFixed(2));

                totalBaseSubtotal += quantity * unitPrice;
            } else {
                row.find('.total-price').text('৳ 0.00');
            }
        });

        const totalDiscount = totalBaseSubtotal * GLOBAL_DISCOUNT_PERCENT / 100;
        const subtotalAfterDiscount = totalBaseSubtotal - totalDiscount;
        const totalTax = subtotalAfterDiscount * GLOBAL_TAX_PERCENT / 100;
        const finalTotal = subtotalAfterDiscount + totalTax;

        if (totalQuantity > 0) {
            $('#chosen_price_div').removeClass('d-none');
            $('#chosen_quantity').text(totalQuantity);
            $('#chosen_base_price').text('৳ ' + totalBaseSubtotal.toFixed(2));
            $('#chosen_grand_total').text('৳ ' + finalTotal.toFixed(2));
            (GLOBAL_DISCOUNT_PERCENT > 0) ? $('#discount_row').show().find('#chosen_discount_value').text('- ৳ ' +
                totalDiscount.toFixed(2)): $('#discount_row').hide();
            (GLOBAL_TAX_PERCENT > 0) ? $('#tax_row').show().find('#chosen_tax_value').text('+ ৳ ' + totalTax.toFixed(
                2)): $('#tax_row').hide();
        } else {
            $('#chosen_price_div').addClass('d-none');
        }

        $('input[name="quantity"]').val(totalQuantity);
        saveCartState();
    }

    function calculateEffectiveUnitPrice(basePrice) {
        if (basePrice <= 0) return 0;
        const unitDiscounted = basePrice - (basePrice * GLOBAL_DISCOUNT_PERCENT / 100);
        return unitDiscounted + (unitDiscounted * GLOBAL_TAX_PERCENT / 100);
    }

    function addToCartRow(button, initialQty = 1) {
        const row = $(button).closest('tr');
        $(button).addClass('hidden');
        row.find('.quantity-control').addClass('active');
        row.find('.quantity-input').val(initialQty);
        updateGrandTotal();
    }

    function increaseQuantity(button) {
        const input = $(button).siblings('.quantity-input');
        const maxQty = parseInt($(button).closest('tr').data('stock-qty'));
        if (parseInt(input.val()) < maxQty) {
            input.val(parseInt(input.val()) + 1);
            updateGrandTotal();
        } else {
            AIZ.plugins.notify('warning', 'Maximum stock limit reached.');
        }
    }

    function decreaseQuantity(button) {
        const input = $(button).siblings('.quantity-input');
        if (parseInt(input.val()) > 1) {
            input.val(parseInt(input.val()) - 1);
        } else {
            $(button).closest('.quantity-control').removeClass('active');
            $(button).closest('tr').find('.add-btn').removeClass('hidden');
            input.val(0);
        }
        updateGrandTotal();
    }

    function extractSelectedItems() {
        const selectedItems = [];
        $('#sizeTable tbody tr').each(function() {
            const row = $(this);
            if (row.find('.quantity-control').hasClass('active')) {
                const quantity = parseInt(row.find('.quantity-input').val()) || 0;
                if (quantity > 0) {
                    selectedItems.push({
                        size: row.data('size'),
                        quantity: quantity
                    });
                }
            }
        });
        return selectedItems;
    }

    function setHiddenSelectedItems(items) {
        if (!$('#option-choice-form').find('input[name="selected_items"]').length) {
            $('#option-choice-form').append('<input type="hidden" name="selected_items">');
        }
        $('#option-choice-form').find('input[name="selected_items"]').val(JSON.stringify(items));
    }

    function addToCartFromTable() {
        const selectedItems = extractSelectedItems();
        if (selectedItems.length === 0) {
            AIZ.plugins.notify('warning', '{{ translate('Please select at least one item') }}');
            return;
        }
        setHiddenSelectedItems(selectedItems);
        addToCart();
    }

    function buyNowFromTable() {
        const selectedItems = extractSelectedItems();
        if (selectedItems.length === 0) {
            AIZ.plugins.notify('warning', '{{ translate('Please select at least one item') }}');
            return;
        }

        // Set hidden fields for selected items
        setHiddenSelectedItems(selectedItems);

        // Mohammad Hassan - Updated buy now to redirect to checkout instead of cart
        const form = document.getElementById('option-choice-form');
        const formData = new FormData(form);

        $.ajax({
            type: "POST",
            url: '{{ route('cart.addToCart') }}',
            data: formData,
            processData: false,
            contentType: false,
            success: function(data) {
                if (data.status == 1) {
                    // Redirect directly to checkout page for buy now
                    window.location.href = "{{ route('checkout') }}";
                } else {
                    AIZ.plugins.notify('danger', data.message || "{{ translate('Something went wrong') }}");
                }
            },
            error: function() {
                AIZ.plugins.notify('danger', "{{ translate('Something went wrong') }}");
            }
        });
    }

    $(document).ready(function() {
        loadCartState();
        updateGrandTotal();
    });
</script>

<script>
    function selectColor(element, colorName, colorValue) {
        document.querySelectorAll('#color-options .color-option').forEach(option => {
            option.classList.remove('selected-color');
            option.style.borderWidth = '1px';
            option.style.borderColor = '#ddd';
        });
        element.classList.add('selected-color');
        document.getElementById('selected-color-name').textContent = colorName;
        $('input[name="color"][value="' + colorName + '"]').prop('checked', true);
        if (typeof getVariantPrice === 'function') {
            getVariantPrice();
        }
    }
</script>

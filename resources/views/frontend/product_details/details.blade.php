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
@endphp

<div class="text-left">
    <!-- Product Name -->
    <h2 class="mb-2 fs-18 fw-800 text-dark">
        {{ $detailedProduct->getTranslation('name') }}
    </h2>
    <hr>

    <!-- Dynamic Color Section -->
    @if ($detailedProduct->colors != null && count(json_decode($detailedProduct->colors)) > 0)
        <div class="mb-4">
            <h5 class="mb-3">{{ translate('Color') }} :
                <span
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
    @endif
    {{-- END: DYNAMIC PRICE TIERS --}}

    <!-- Size/Variant Table -->
    <div class="mb-4">
        <h5 class="mb-3">{{ translate('Model/Size') }}</h5>
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
                                            min="0" style="width: 40px; border: none; height: 30px;" readonly>
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

            <!-- Professional Price Breakdown Box -->
            <div class="p-3 mt-3 border rounded-lg d-none" id="chosen_price_div" style="max-width: calc(100% - 145px);">
                <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                    <div class="text-secondary fs-14 fw-400">{{ translate('Total Quantity') }}</div>
                    <strong id="chosen_quantity" class="fs-16 fw-600 text-dark">0</strong>
                </div>
                <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                    <div class="text-secondary fs-14 fw-400">{{ translate('Base Price') }}</div>
                    <strong id="chosen_base_price" class="fs-16 fw-600 text-dark">৳ 0.00</strong>
                </div>
                <div class="d-flex justify-content-between mb-2 pb-2 border-bottom" id="discount_row"
                    style="display: none;">
                    <div class="text-secondary fs-14 fw-400">{{ translate('Discount') }}</div>
                    <strong id="chosen_discount_value" class="fs-16 fw-600 text-danger">- ৳ 0.00</strong>
                </div>
                <div class="d-flex justify-content-between mb-2 pb-2 border-bottom" id="tax_row"
                    style="display: none;">
                    <div class="text-secondary fs-14 fw-400">{{ translate('Tax') }}</div>
                    <strong id="chosen_tax_value" class="fs-16 fw-600 text-success">+ ৳ 0.00</strong>
                </div>
                <div class="d-flex justify-content-between mb-2 pt-1">
                    <div class="text-secondary fs-16 fw-600">{{ translate('Grand Total') }}</div>
                    <strong id="chosen_grand_total" class="fs-22 fw-700 text-primary">৳ 0.00</strong>
                </div>
            </div>
        </form>
    @endif

    {{-- Purchase Buttons --}}
    @if (!$detailedProduct->auction_product)
        <div class="mt-3">
            <button type="button" class="btn btn-info mr-2 add-to-cart fw-600 min-w-150px rounded-0 text-white"
                @if (Auth::check() || get_Setting('guest_checkout_activation') == 1) onclick="addToCartFromTable()" @else onclick="showLoginModal()" @endif>
                <i class="las la-shopping-bag"></i> {{ translate('Add to cart') }}
            </button>
            <button type="button" class="btn btn-dark mr-2 buy-now fw-600 add-to-cart min-w-150px rounded-0"
                @if (Auth::check() || get_Setting('guest_checkout_activation') == 1) onclick="buyNowFromTable()" @else onclick="showLoginModal()" @endif>
                <i class="la la-shopping-cart"></i> {{ translate('Buy Now') }}
            </button>
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
        background-color: #f3f3f3;
        color: #333;
        border: 1px solid #e0e0e0;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .price-tier-item.active {
        background-color: #3D52A0;
        color: white;
        border-color: #3D52A0;
        transform: scale(1.05);
    }

    .color-option {
        transition: all 0.3s ease;
    }

    .color-option:hover {
        border-color: #3D52A0 !important;
    }

    .selected-color {
        border-color: #3D52A0 !important;
        border-width: 2px !important;
    }

    .size-table-container::-webkit-scrollbar {
        width: 8px;
    }

    .size-table-container::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .size-table-container::-webkit-scrollbar-thumb {
        background: #3D52A0;
        border-radius: 10px;
    }

    .sticky-top {
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .quantity-control {
        display: none !important;
    }

    .quantity-control.active {
        display: flex !important;
    }

    .add-btn {
        display: block !important;
    }

    .add-btn.hidden {
        display: none !important;
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
        setHiddenSelectedItems(selectedItems);
        buyNow();
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

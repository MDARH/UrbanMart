@php
    $product_discount_rate = 0;
    if (isset($detailedProduct->discount) && $detailedProduct->discount_type == 'percent') {
        $product_discount_rate = (float)$detailedProduct->discount;
    }
    $product_tax_rate = 0;
    if (isset($detailedProduct->tax) && $detailedProduct->tax_type == 'percent') {
        $product_tax_rate = (float)$detailedProduct->tax;
    }

@endphp

<div class="text-left">
    <!-- Product Name -->
    <h2 class="mb-2 fs-18 fw-800 text-dark">
        {{ $detailedProduct->getTranslation('name') }}
    </h2>
    <hr>
    <!-- Dynamic Color Section (NEW Code) -->
    @if ($detailedProduct->colors != null && count(json_decode($detailedProduct->colors)) > 0)
        <div class="mb-4">
            <h5 class="mb-3">{{ translate('Color') }} :
                <span id="selected-color-name">{{ get_single_color_name(json_decode($detailedProduct->colors)[0]) }}</span>
            </h5>
            <div class="d-flex flex-wrap" id="color-options">
                @foreach (json_decode($detailedProduct->colors) as $key => $color)
                    <div class="color-option mr-3 mb-2 p-1 border @if($key == 0) border-2 selected-color @endif"
                         data-color="{{ get_single_color_name($color) }}"
                         data-color-value="{{ $color }}"
                         style="border: @if($key == 0) 2px solid #3D52A0 @else 1px solid #ddd @endif; border-radius: 8px; cursor: pointer;"
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

    {{-- START: DYNAMIC PRICE TIERS (NEW Code) --}}
    @if ($detailedProduct->priceTiers && count($detailedProduct->priceTiers) > 0)
        <div class="d-flex flex-wrap mb-3" id="price-tier-options" style="gap: 12px; justify-content: flex-start;margin-right: 145px;">
            @foreach ($detailedProduct->priceTiers as $key => $tier)
                <div class="price-tier-item text-center rounded-lg p-3 mb-2 @if ($key == 0) active @endif"
                    data-price="{{ $tier->price }}" data-min-qty="{{ $tier->min_qty }}"
                    onclick="selectPriceTier(this)"
                    style="flex: 1 1 calc(20% - 12px); min-width: 110px;">
                    <div class="fs-18 fw-600">৳{{ $tier->price }}</div>
                    <div class="fs-13">{{ $tier->min_qty }} or more</div>
                </div>
            @endforeach
        </div>
    @endif
    {{-- END: DYNAMIC PRICE TIERS --}}

    <!-- Size Table -->
    <div class="mb-4">
        <h5 class="mb-3">{{ translate('Model/Size') }}</h5>
        <div class="size-table-container"
            style="max-height: 300px; overflow-y: auto; border: 1px solid #e0e0e0; border-radius: 8px;margin-right: 145px;">
            <table class="table table-bordered mb-0" id="sizeTable">
                <thead class="bg-light sticky-top">
                    <tr style="height: 45px;">
                        {{-- DYNAMIC ATTRIBUTE NAME --}}
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
                        <th style="padding: 8px 12px;">{{ translate('Unit Price (Final)') }}</th>
                        <th style="padding: 8px 12px;">{{ translate('Total Price') }}</th>
                        <th style="padding: 8px 12px;">{{ translate('Add/Quantity') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $stocks = $detailedProduct->stocks;
                    @endphp
                    @foreach ($stocks as $key => $stock)
                        @php
                            $variantId = $stock->variant ?? $stock->id;
                            $variantName = $stock->variant ?? translate('Default');
                            $originalPrice = $stock->price; // Base price for this specific stock/variant
                            $qty = $stock->qty;
                        @endphp
                        <tr data-size="{{ $variantId }}"
                            data-original-price="{{ $originalPrice }}"
                            data-stock-qty="{{ $qty }}"
                            style="height: 60px;">

                            <td style="padding: 8px 12px;">{{ $variantName }}</td>
                            <td class="unit-price" style="padding: 8px 12px;">৳ {{ number_format($originalPrice, 2) }}</td>
                            <td class="total-price" style="padding: 8px 12px;">৳ 0.00</td>

                            <td style="padding: 8px 12px;">
                                <div class="d-flex align-items-center justify-content-end">
                                    {{-- Initial Add Button (Color changed to #3D52A0) --}}
                                    <button type="button" class="btn add-btn" data-row-id="{{ $variantId }}"
                                        style="background: #3D52A0; color: white; border-radius: 8px; padding: 6px 20px;"
                                        onclick="addToCartRow(this)">{{ translate('Add') }}</button>

                                    {{-- Quantity Control (Initially Hidden) --}}
                                    <div class="quantity-control d-flex align-items-center mb-1" data-row-id="{{ $variantId }}" style="display: none;">
                                        <button type="button" class="btn btn-sm minus-btn"
                                            style="background: #3D52A0; color: white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;"
                                            onclick="decreaseQuantity(this)">-</button>
                                        <input type="number" class="quantity-input mx-2 text-center" value="1"
                                            min="1" style="width: 40px; border: none; height: 30px;"
                                            onchange="updateTotal(this)" readonly>
                                        <button type="button" class="btn btn-sm plus-btn"
                                            style="background: #3D52A0; color: white; border-radius: 50%; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;"
                                            onclick="increaseQuantity(this)">+</button>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <small class="text-muted stock-text">{{ translate('Stock') }} {{ $qty }}</small>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- START: Old Price/Wholesale/Auction Display Logic -->
    @if ($detailedProduct->auction_product)
        @include('partials.product_details.auction_info', ['detailedProduct' => $detailedProduct])
    @else
        {{-- Existing Price/Discount info hidden as the table takes over --}}
    @endif
    <!-- END: Old Price/Wholesale/Auction Display Logic -->


    @if ($detailedProduct->auction_product != 1)
        <form id="option-choice-form">
            @csrf
            <input type="hidden" name="id" value="{{ $detailedProduct->id }}">

            @if ($detailedProduct->digital == 0)
                {{-- HIDDEN FORM INPUTS --}}
                @if ($detailedProduct->choice_options != null)
                    <div class="d-none">
                        @foreach (json_decode($detailedProduct->choice_options) as $key => $choice)
                            <div class="aiz-radio-inline">
                                @foreach ($choice->values as $key => $value)
                                    <label class="aiz-megabox pl-0 mr-2 mb-0">
                                        <input type="radio" name="attribute_id_{{ $choice->attribute_id }}"
                                            value="{{ $value }}"
                                            @if ($key == 0) checked @endif>
                                    </label>
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                @endif
                @if ($detailedProduct->colors != null && count(json_decode($detailedProduct->colors)) > 0)
                    <div class="d-none">
                        <div class="aiz-radio-inline">
                            @foreach (json_decode($detailedProduct->colors) as $key => $color)
                                <label class="aiz-megabox pl-0 mr-2 mb-0">
                                    <input type="radio" name="color"
                                        value="{{ get_single_color_name($color) }}"
                                        @if ($key == 0) checked @endif>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif
                <!-- Hidden Quantity Input for Cart Submission (Aggregated by JS) -->
                <input type="hidden" name="quantity" value="0">

            @else
                <!-- Digital Quantity -->
                <input type="hidden" name="quantity" value="1">
            @endif

            <!-- Total Price Display (Aggregated by JS) - Consolidated UI -->
            <div class="p-3 mt-3 border rounded-lg d-none" id="chosen_price_div" style="max-width: calc(100% - 145px);">

                {{-- Total Quantity and Price Container (Cleaned up for better structure) --}}
                <div class="d-flex justify-content-between mb-2 pb-2 border-bottom">
                    <div class="text-secondary fs-14 fw-400">{{ translate('Total Quantity') }}</div>
                    <strong id="chosen_quantity" class="fs-18 fw-700 text-dark">0</strong>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <div class="text-secondary fs-14 fw-400">{{ translate('Total Price') }}</div>
                    <strong id="chosen_price_final" class="fs-24 fw-700 text-primary">৳ 0.00</strong>
                </div>

                {{-- Price Breakdown (Professional Look) --}}
                <div id="price_breakdown_section" class="fs-12 text-dark">
                    {{-- JS will inject: Base Amount, Discount, Tax --}}
                </div>
            </div>

        </form>
    @endif

    {{-- START: Purchase Buttons (Using new table-aware functions) --}}
    @if ($detailedProduct->auction_product)
        {{-- Auction Buttons (Retained) --}}
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
                    <!-- New Functions for Table Data Collection -->
                    <button type="button"
                        class="btn btn-info mr-2 add-to-cart fw-600 min-w-150px rounded-0 text-white"
                        @if (Auth::check() || get_Setting('guest_checkout_activation') == 1) onclick="addToCartFromTable()" @else onclick="showLoginModal()" @endif>
                        <i class="las la-shopping-bag"></i> {{ translate('Add to cart') }}
                    </button>
                    <button type="button"
                        class="btn btn-dark mr-2 buy-now fw-600 add-to-cart min-w-150px rounded-0"
                        @if (Auth::check() || get_Setting('guest_checkout_activation') == 1) onclick="buyNowFromTable()" @else onclick="showLoginModal()" @endif>
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
                <!-- New Functions for Table Data Collection -->
                <button type="button"
                    class="btn btn-secondary-base mr-2 add-to-cart fw-600 min-w-150px rounded-0 text-white"
                    @if (Auth::check() || get_Setting('guest_checkout_activation') == 1) onclick="addToCartFromTable()" @else onclick="showLoginModal()" @endif>
                    <i class="las la-shopping-bag"></i> {{ translate('Add to cart') }}
                </button>
                <button type="button" class="btn btn-primary mr-2 buy-now fw-600 add-to-cart min-w-150px rounded-0"
                    @if (Auth::check() || get_Setting('guest_checkout_activation') == 1) onclick="buyNowFromTable()" @else onclick="showLoginModal()" @endif>
                    <i class="la la-shopping-cart"></i> {{ translate('Buy Now') }}
                </button>
            @endif
        </div>
        <hr>
    @endif
    {{-- END: Purchase Buttons --}}

    <!-- Refund (Old Code) -->
    {{-- @if ($detailedProduct->auction_product != 1)
        @if (addon_is_activated('refund_request'))
            @php
                $refund_sticker = get_setting('refund_sticker');
            @endphp
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

        <!-- Seller Guarantees (Old Code) -->
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

        <!-- Promote Link (Old Code) -->
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
    @endif --}}
    {{-- END: Brand, Seller, Warranty, Refund, Share --}}

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

<!-- NEW: Pre-Order Modal (Retained) -->
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

<style>
    /* CSS Styles */
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
    }
    .color-option {
        transition: all 0.3s ease;
    }

    .color-option:hover {
        border-color: #3D52A0 !important;
    }

    .selected-color {
        border-color: #3D52A0 !important;
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
        background: #3D52A0;
        border-radius: 10px;
    }

    .size-table-container::-webkit-scrollbar-thumb:hover {
        background: #3D52A0;
    }

    .sticky-top {
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .quantity-input:focus {
        outline: none;
        border: 1px solid #3D52A0 !important;
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

    .btn:hover {
        transform: scale(1.05);
    }

    .btn {
        transition: all 0.2s ease;
    }

    /* Custom styles for professional breakdown */
    #price_breakdown_section {
        border-top: 1px dashed #e0e0e0;
        padding-top: 5px;
        margin-top: 5px;
    }
</style>

<script type="text/javascript">
    let selectedTierPrice = null;
    const PRODUCT_ID = {{ $detailedProduct->id }};
    const LOCAL_STORAGE_KEY = 'cart_state_' + PRODUCT_ID;

    // --- Dynamic Discount and Tax Rates (Fetched from PHP) ---
    const GLOBAL_DISCOUNT_PERCENT = {{ $product_discount_rate }};
    const GLOBAL_TAX_PERCENT = {{ $product_tax_rate }};
    // ---------------------------------------------------------


    // --- LOCAL STORAGE PERSISTENCE FUNCTIONS ---

    function saveCartState() {
        const selectedItems = extractSelectedItems();
        const stateToSave = {
            items: selectedItems,
            tierPrice: selectedTierPrice
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
            const items = state.items;

            // 1. Restore Tier Price if saved
            if (state.tierPrice) {
                 selectedTierPrice = state.tierPrice;
                 // Find and highlight the corresponding tier item
                 $('#price-tier-options .price-tier-item').removeClass('active');
                 $(`#price-tier-options .price-tier-item[data-price="${state.tierPrice}"]`).addClass('active');
            }

            // 2. Restore individual row quantities
            if (Array.isArray(items) && items.length > 0) {
                items.forEach(item => {
                    const row = $(`tr[data-size="${item.size}"]`);
                    if (row.length && item.quantity > 0) {
                        const addBtn = row.find('.add-btn');
                        const qtyControl = row.find('.quantity-control');
                        const qtyInput = row.find('.quantity-input');

                        // Activate the row state
                        addBtn.addClass('hidden');
                        qtyControl.addClass('active');
                        qtyInput.val(item.quantity);
                    }
                });
            }

            // 3. Re-run calculations
            updateAllUnitPrices(true);

        } catch (e) {
            console.error("Failed to load or parse cart state:", e);
            localStorage.removeItem(LOCAL_STORAGE_KEY);
        }
    }


    // --- CALCULATION LOGIC ---

    function calculateEffectiveUnitPrice(basePrice) {
        if (basePrice <= 0) return 0;
        // Calculation: Base -> Discount -> Tax
        var unitDiscounted = basePrice - (basePrice * GLOBAL_DISCOUNT_PERCENT / 100);
        var effectiveUnitPrice = unitDiscounted + (unitDiscounted * GLOBAL_TAX_PERCENT / 100);
        return effectiveUnitPrice;
    }

    function selectPriceTier(element) {
        $('#price-tier-options .price-tier-item').removeClass('active');
        $(element).addClass('active');

        var price = parseFloat($(element).data('price'));
        selectedTierPrice = price;

        updateAllUnitPrices(true);
    }

    function updateAllUnitPrices(force = false) {
        $('#sizeTable tbody tr').each(function() {
            var row = $(this);
            var originalPrice = parseFloat(row.data('original-price'));
            var newPrice = selectedTierPrice || originalPrice;

            var effectiveUnitPrice = calculateEffectiveUnitPrice(newPrice);

            row.find('.unit-price').text('৳ ' + effectiveUnitPrice.toFixed(2));

            var quantityControl = row.find('.quantity-control');
            if (quantityControl.hasClass('active')) {
                var quantity = parseInt(row.find('.quantity-input').val()) || 1;
                var total = effectiveUnitPrice * quantity;
                row.find('.total-price').text('৳ ' + total.toFixed(2));
            } else {
                row.find('.total-price').text('৳ ' + effectiveUnitPrice.toFixed(2));
            }
        });
        updateGrandTotal();
    }

    function updateTotal(input, isReset = false) {
        var quantity = parseInt($(input).val());
        var row = $(input).closest('tr');
        var originalPrice = parseFloat(row.data('original-price'));
        var unitPrice = selectedTierPrice || originalPrice;

        let finalQuantity = (row.find('.quantity-control').hasClass('active') || isReset) ? quantity : 1;

        var effectiveUnitPrice = calculateEffectiveUnitPrice(unitPrice);
        var finalRowTotal = effectiveUnitPrice * finalQuantity;

        row.find('.total-price').text('৳ ' + finalRowTotal.toFixed(2));
        row.find('.unit-price').text('৳ ' + effectiveUnitPrice.toFixed(2));

        updateGrandTotal();
    }

    function updateGrandTotal() {
        var totalQuantity = 0;
        var totalBaseSubtotal = 0;

        $('#sizeTable tbody tr').each(function() {
            var row = $(this);
            var quantityControl = row.find('.quantity-control');

            if (quantityControl.hasClass('active')) {
                var quantity = parseInt(row.find('.quantity-input').val()) || 0;
                var originalPrice = parseFloat(row.data('original-price'));
                var unitPrice = selectedTierPrice || originalPrice;
                var rowBaseTotal = quantity * unitPrice;

                totalQuantity += quantity;
                totalBaseSubtotal += rowBaseTotal;
            }
        });

        // --- Grand Total Calculation (Base -> Discount -> Tax) ---
        var baseSubtotal = totalBaseSubtotal;

        var totalDiscount = baseSubtotal * GLOBAL_DISCOUNT_PERCENT / 100;
        var afterDiscount = baseSubtotal - totalDiscount;

        var totalTax = afterDiscount * GLOBAL_TAX_PERCENT / 100;
        var finalTotal = afterDiscount + totalTax;

        // --- Display Results ---

        if (totalQuantity > 0) {
            $('#chosen_price_div').removeClass('d-none');

            // 1. Total Quantity
            $('#chosen_quantity').text(totalQuantity);

            // 2. Final Price
            $('#chosen_price_final').text('৳ ' + finalTotal.toFixed(2));

            // 3. Price Breakdown (Professional Look)
            $('#price_breakdown_section').html(`
                <div class="d-flex justify-content-between mb-1">
                    <span>${translate('Base Amount')} (${GLOBAL_DISCOUNT_PERCENT}% ${translate('Discount Calculation')})</span>
                    <span>৳ ${baseSubtotal.toFixed(2)}</span>
                </div>
                <div class="d-flex justify-content-between text-danger mb-1">
                    <span>${translate('Total Discount')}:</span>
                    <span>- ৳ ${totalDiscount.toFixed(2)}</span>
                </div>
                <div class="d-flex justify-content-between text-success">
                    <span>${translate('Total Tax')} (${GLOBAL_TAX_PERCENT}%):</span>
                    <span>+ ৳ ${totalTax.toFixed(2)}</span>
                </div>
            `);
        } else {
            $('#chosen_price_div').addClass('d-none');
        }

        $('input[name="quantity"]').val(totalQuantity);
        saveCartState(); // Save state after every grand total update
    }

    // --- UTILITIES & WRAPPERS (Remaining functions unchanged) ---
    function addToCartRow(button) {
        var rowId = $(button).data('row-id');
        var row = $('tr[data-size="' + rowId + '"]');

        $(button).addClass('hidden');
        var quantityControl = row.find('.quantity-control[data-row-id="' + rowId + '"]');
        quantityControl.addClass('active');

        var quantityInput = quantityControl.find('.quantity-input');
        quantityInput.val(1);
        updateTotal(quantityInput[0]);
    }

    function increaseQuantity(button) {
        var input = $(button).siblings('.quantity-input');
        var currentVal = parseInt(input.val());
        var maxQty = parseInt($(button).closest('tr').data('stock-qty')) || 99999;

        if (currentVal < maxQty) {
            input.val(currentVal + 1);
            updateTotal(input[0]);
        } else {
             alert('Maximum stock limit reached for this variant.');
        }
    }

    function decreaseQuantity(button) {
        var input = $(button).siblings('.quantity-input');
        var currentVal = parseInt(input.val());

        if (currentVal > 1) {
            input.val(currentVal - 1);
            updateTotal(input[0]);
        } else {
            var quantityControl = $(button).closest('.quantity-control');
            var row = $(button).closest('tr');
            var rowId = quantityControl.data('row-id');

            quantityControl.removeClass('active');
            row.find('.add-btn[data-row-id="' + rowId + '"]').removeClass('hidden');

            input.val(1);
            updateTotal(input[0], true);
        }
    }


    function extractSelectedItems() {
        var selectedItems = [];
        $('#sizeTable tbody tr').each(function() {
            var row = $(this);
            var quantityControl = row.find('.quantity-control');

            if (quantityControl.hasClass('active')) {
                var quantity = parseInt(row.find('.quantity-input').val()) || 0;
                var size = row.data('size');
                var originalPrice = parseFloat(row.data('original-price'));
                var unitPrice = selectedTierPrice || originalPrice;

                selectedItems.push({
                    size: size,
                    quantity: quantity,
                    unitPrice: unitPrice,
                    total: unitPrice * quantity
                });
            }
        });
        return selectedItems;
    }

    function setHiddenSelectedItems(items) {
         if (!$('#option-choice-form').find('input[name="selected_items"]').length) {
            $('#option-choice-form').append('<input type="hidden" name="selected_items" value="">');
        }
        $('#option-choice-form').find('input[name="selected_items"]').val(JSON.stringify(items));
    }


    function addToCartFromTable() {
        var selectedItems = extractSelectedItems();
        if (selectedItems.length === 0) {
            AIZ.plugins.notify('warning', '{{ translate('Please select at least one item to add to cart') }}');
            return;
        }
        $('input[name="quantity"]').val(selectedItems.reduce((sum, item) => sum + item.quantity, 0));
        setHiddenSelectedItems(selectedItems);
        addToCart();
    }

    function buyNowFromTable() {
        var selectedItems = extractSelectedItems();
        if (selectedItems.length === 0) {
            AIZ.plugins.notify('warning', '{{ translate('Please select at least one item to add to cart') }}');
            return;
        }
        $('input[name="quantity"]').val(selectedItems.reduce((sum, item) => sum + item.quantity, 0));
        setHiddenSelectedItems(selectedItems);
        buyNow();
    }

    // Core Cart/BuyNow functions (placeholders)
    function addToCart() { /* ... AJAX implementation ... */ }
    function buyNow() { /* ... AJAX implementation ... */ }


    // --- INITIALIZATION ---
    $(document).ready(function() {
        // 1. Initialize Price Tier
        var firstTier = $('#price-tier-options .price-tier-item.active');
        if (firstTier.length) {
            selectPriceTier(firstTier[0]);
        }

        // 2. Load Cart State from Local Storage (must happen after Tier Price is potentially set)
        loadCartState();

        // 3. If no state loaded, ensure prices are calculated correctly
        if (localStorage.getItem(LOCAL_STORAGE_KEY) === null) {
            updateAllUnitPrices();
        }

        // Color initialization
        const firstColorOptionDiv = $('#color-options .color-option').first();
        if (firstColorOptionDiv.length && typeof selectColor === 'function') {
            const firstColorName = firstColorOptionDiv.data('color');
            const firstColorValue = firstColorOptionDiv.data('color-value');
            selectColor(firstColorOptionDiv[0], firstColorName, firstColorValue);
        }
    });
</script>

<script>
// Mohammad Hassan
// Dynamic Color Selection Function
function selectColor(element, colorName, colorValue) {
    const hexColor = colorValue ? colorValue.toLowerCase().trim() : '';
    let foundColorName = colorName;
    const colorOptionsDivs = document.querySelectorAll('#color-options .color-option');
    let targetElement = element;
    if (!element && hexColor) {
        targetElement = document.querySelector(`#color-options .color-option[data-color-value="${hexColor}"]`);
    }
    colorOptionsDivs.forEach(option => {
        option.classList.remove('selected-color');
        option.style.border = '1px solid #ddd';
    });
    if (targetElement) {
        targetElement.classList.add('selected-color');
        targetElement.style.border = '2px solid #3D52A0';
        foundColorName = targetElement.getAttribute('data-color');
    }
    const selectedColorNameSpan = document.getElementById('selected-color-name');
    if (selectedColorNameSpan && foundColorName) {
        selectedColorNameSpan.textContent = foundColorName;
    }
    const allRadioInputs = document.querySelectorAll('input[name="color"]');
    allRadioInputs.forEach(radioInput => {
        const $radioInput = $(radioInput);
        const $aizMegaboxLabel = $radioInput.closest('.aiz-megabox');
        if ($radioInput.val() === foundColorName) {
            $radioInput.prop('checked', true);
            $aizMegaboxLabel.addClass('checked');
        } else {
            $radioInput.prop('checked', false);
            $aizMegaboxLabel.removeClass('checked');
        }
    });
    if (typeof $ !== 'undefined' && hexColor) {
        $(document).trigger('colorChanged', [hexColor]);
    }
    if (typeof getVariantPrice === 'function') {
        getVariantPrice();
    }
}

// Professional Tab Functionality
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.professional-tab-btn');
    const tabPanes = document.querySelectorAll('.professional-tab-pane');

    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');

            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabPanes.forEach(pane => pane.classList.remove('active'));

            this.classList.add('active');
            const targetPane = document.getElementById(targetTab + '-tab');
            if (targetPane) {
                targetPane.classList.add('active');
            }
        });
    });
});
</script>

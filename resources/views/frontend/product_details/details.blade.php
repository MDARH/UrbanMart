<div class="text-left">
    <!-- Product Name -->
    <h2 class="mb-2 fs-18 fw-800 text-dark">
        {{ $detailedProduct->getTranslation('name') }}
    </h2>
    <hr>
    
    <!-- Mohammad Hassan -->
    <!-- Dynamic Color Section -->
    @if ($detailedProduct->colors != null && count(json_decode($detailedProduct->colors)) > 0)
        <div class="mb-4">
            <h5 class="mb-3">{{ translate('Color') }} : 
                <span id="selected-color-name">{{ get_single_color_name(json_decode($detailedProduct->colors)[0]) }}</span>
            </h5>
            <div class="d-flex flex-wrap" id="color-options">
                @foreach (json_decode($detailedProduct->colors) as $key => $color)
                    <div class="color-option mr-3 mb-2 p-2 border @if($key == 0) border-2 selected-color @endif"
                         data-color="{{ get_single_color_name($color) }}"
                         data-color-value="{{ $color }}"
                         style="border: @if($key == 0) 2px solid #6366f1 @else 1px solid #ddd @endif; border-radius: 8px; cursor: pointer;"
                         onclick="selectColor(this, '{{ get_single_color_name($color) }}', '{{ $color }}')">
                        <div class="color-swatch" 
                             style="width: 60px; height: 60px; background-color: {{ $color }}; border-radius: 4px; position: relative;">
                            <span class="color-name" 
                                  style="position: absolute; bottom: 2px; left: 50%; transform: translateX(-50%); 
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

    {{-- START: DYNAMIC PRICE TIERS --}}
    <!-- Mohammad Hassan: Adjusted CSS for 4 tiers per line -->
    @if ($detailedProduct->priceTiers && count($detailedProduct->priceTiers) > 0)
        <div class="d-flex flex-wrap mb-3" id="price-tier-options" style="gap: 12px;">
            @foreach ($detailedProduct->priceTiers as $key => $tier)
                <div class="price-tier-item text-center rounded-lg p-3 mb-2 @if ($key == 0) active @endif"
                    data-price="{{ $tier->price }}" data-min-qty="{{ $tier->min_qty }}"
                    onclick="selectPriceTier(this)"
                    style="flex: 1 1 calc(25% - 10px); min-width: 120px;">
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
            style="max-height: 300px; overflow-y: auto; border: 1px solid #e0e0e0; border-radius: 8px;">
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
                        <th style="padding: 8px 12px;">{{ translate('Unit Price') }}</th>
                        <th style="padding: 8px 12px;">{{ translate('Total Price') }}</th>
                        <th style="padding: 8px 12px;">{{ translate('Add/Quantity') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @php 
                        $stocks = $detailedProduct->stocks;
                        // Use a consistent ID for each row, preferably the stock ID if available, or just the variant string.
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
                                <div class="d-flex align-items-center justify-content-center">
                                    {{-- Initial Add Button --}}
                                    <button type="button" class="btn add-btn" data-row-id="{{ $variantId }}"
                                        style="background: #6366f1; color: white; border-radius: 8px; padding: 6px 20px;"
                                        onclick="addToCartRow(this)">{{ translate('Add') }}</button>
                                    
                                    {{-- Quantity Control (Initially Hidden) --}}
                                    <div class="quantity-control d-flex align-items-center mb-1" data-row-id="{{ $variantId }}" style="display: none;">
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
                                    <small class="text-muted stock-text">{{ translate('Stock') }} {{ $qty }}</small>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>

    <style>
        /* Add some basic styling for the active state */
        .price-tier-item {
            background-color: #f3f3f3;
            color: #333;
            border: 1px solid #e0e0e0;
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

        /* Initial state is none for quantity controls */
        .quantity-control {
            display: none !important;
        }

        /* Active state for quantity controls */
        .quantity-control.active {
            display: flex !important;
        }

        /* Initial state is visible for add button */
        .add-btn {
            display: block !important;
        }

        /* Hidden state for add button */
        .add-btn.hidden {
            display: none !important;
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
                <!-- Hidden Quantity Input for Cart Submission -->
                <input type="hidden" name="quantity" value="1">
            
            @else
                <!-- Digital Quantity -->
                <input type="hidden" name="quantity" value="1">
            @endif
            <!-- Total Price Display (optional for single item display, used here for combined table price) -->
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

            <div class="row no-gutters pb-3 d-none" id="chosen_quantity_div">
                <div class="col-sm-2">
                    <div class="text-secondary fs-14 fw-400 mt-1">{{ translate('Total Quantity') }}</div>
                </div>
                <div class="col-sm-10">
                    <div class="product-quantity">
                        <strong id="chosen_quantity" class="fs-20 fw-700 text-primary">
                        </strong>
                    </div>
                </div>
            </div>
        </form>
    @endif

    {{-- ... (Rest of the HTML code for Auction, Buy/Add to Cart Buttons, Brand, Warranty, etc.) ... --}}

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
                    <!-- Mohammad Hassan -->
                    <button type="button"
                        class="btn btn-secondary-base mr-2 add-to-cart fw-600 min-w-150px rounded-0 text-white"
                        @if (Auth::check() || get_Setting('guest_checkout_activation') == 1) onclick="addToCartFromTable()" @else onclick="showLoginModal()" @endif>
                        <i class="las la-shopping-bag"></i> {{ translate('Add to cart') }}
                    </button>
                    <button type="button"
                        class="btn btn-primary mr-2 buy-now fw-600 add-to-cart min-w-150px rounded-0"
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
                <!-- Mohammad Hassan -->
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
        $('#chosen_price').text('৳' + price.toFixed(2));

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

            // Update total price based on current quantity if it's currently active (added to cart)
            var quantityControl = row.find('.quantity-control');
            if (quantityControl.hasClass('active')) {
                var quantity = parseInt(row.find('.quantity-input').val()) || 1;
                var total = newPrice * quantity;
                row.find('.total-price').text('৳ ' + total.toFixed(2));
            } else {
                 // Update Total Price for non-active rows to show unit price * 1 (standard logic)
                row.find('.total-price').text('৳ ' + newPrice.toFixed(2));
            }
        });
        updateGrandTotal();
    }

    // Color selection functionality
    $('.color-option').click(function() {
        var selectedColorName = $(this).data('color');
        var selectedColorValue = $(this).data('color-value');
        selectColor(this, selectedColorName, selectedColorValue);
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
        var maxQty = parseInt($(button).closest('tr').data('stock-qty')) || 99999; 
        
        if (currentVal < maxQty) {
            input.val(currentVal + 1);
            updateTotal(input[0]);
        } else {
             // Optional: show a message if stock limit reached
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
            // If quantity becomes 1 and we try to decrease, reset to "Add" state
            var quantityControl = $(button).closest('.quantity-control');
            var row = $(button).closest('tr');
            var rowId = quantityControl.data('row-id');

            quantityControl.removeClass('active');
            row.find('.add-btn[data-row-id="' + rowId + '"]').removeClass('hidden');

            // Reset to original price calculation (Total Price = Unit Price * 1)
            input.val(1);
            updateTotal(input[0], true); // Force reset calculation for total price display
        }
    }

    function updateTotal(input, isReset = false) {
        var quantity = parseInt($(input).val());
        var row = $(input).closest('tr');
        var originalPrice = parseFloat(row.data('original-price'));
        var unitPrice = selectedTierPrice || originalPrice;
        
        // If resetting (going back to 'Add' state), total price should show unit price, not 0
        if (isReset) {
            quantity = 1; 
        }

        var total = quantity * unitPrice;
        
        // If the control is hidden (meaning in 'Add' state), the displayed total price should be 1 unit price
        if (row.find('.quantity-control').hasClass('active') || isReset) {
            row.find('.total-price').text('৳ ' + total.toFixed(2));
        } else {
            // Ensure if inactive, it shows 1 unit price based on selected tier
            row.find('.total-price').text('৳ ' + unitPrice.toFixed(2));
        }
        
        updateGrandTotal();
    }

    // Mohammad Hassan
    function updateGrandTotal() {
        var totalQuantity = 0;
        var totalPrice = 0;

        $('#sizeTable tbody tr').each(function() {
            var row = $(this);
            var quantityControl = row.find('.quantity-control');
            
            if (quantityControl.hasClass('active')) {
                var quantity = parseInt(row.find('.quantity-input').val()) || 0;
                var originalPrice = parseFloat(row.data('original-price'));
                var unitPrice = selectedTierPrice || originalPrice;
                var rowTotal = quantity * unitPrice;

                totalQuantity += quantity;
                totalPrice += rowTotal;
            }
        });

        // Show total price and quantity sections if items are selected
        if (totalQuantity > 0) {
            $('#chosen_price_div').removeClass('d-none');
            $('#chosen_quantity_div').removeClass('d-none');
            $('#chosen_price').text('৳ ' + totalPrice.toFixed(2));
            $('#chosen_quantity').text(totalQuantity);
        } else {
            $('#chosen_price_div').addClass('d-none');
            $('#chosen_quantity_div').addClass('d-none');
        }
        
        // Update main quantity input for cart integration (used by addToCart function)
        $('input[name="quantity"]').val(totalQuantity);
    }

    // Mohammad Hassan
    function addToCartFromTable() {
        console.log('addToCartFromTable function called');
        var totalQuantity = 0;
        var totalPrice = 0;
        var selectedItems = [];

        $('#sizeTable tbody tr').each(function() {
            var row = $(this);
            var quantityControl = row.find('.quantity-control');
            
            if (quantityControl.hasClass('active')) {
                var quantity = parseInt(row.find('.quantity-input').val()) || 0;
                var size = row.data('size');
                var originalPrice = parseFloat(row.data('original-price'));
                var unitPrice = selectedTierPrice || originalPrice;
                var rowTotal = quantity * unitPrice;

                totalQuantity += quantity;
                totalPrice += rowTotal;
                
                selectedItems.push({
                    size: size,
                    quantity: quantity,
                    unitPrice: unitPrice,
                    total: rowTotal
                });
            }
        });

        if (totalQuantity === 0) {
            AIZ.plugins.notify('warning', '{{ translate('Please select at least one item to add to cart') }}');
            return;
        }

        // Update the main form with total values
        $('input[name="quantity"]').val(totalQuantity);
        
        // Add selected items data to form
        if (!$('#option-choice-form').find('input[name="selected_items"]').length) {
            $('#option-choice-form').append('<input type="hidden" name="selected_items" value="">');
        }
        $('#option-choice-form').find('input[name="selected_items"]').val(JSON.stringify(selectedItems));
        
        console.log('Calling addToCart() with:', {
            totalQuantity: totalQuantity,
            selectedItems: selectedItems
        });
        
        // Call the existing addToCart function
        addToCart();
    }

    // Mohammad Hassan
    function buyNowFromTable() {
        console.log('buyNowFromTable function called');
        var totalQuantity = 0;
        var totalPrice = 0;
        var selectedItems = [];

        $('#sizeTable tbody tr').each(function() {
            var row = $(this);
            var quantityControl = row.find('.quantity-control');
            
            if (quantityControl.hasClass('active')) {
                var quantity = parseInt(row.find('.quantity-input').val()) || 0;
                var size = row.data('size');
                var originalPrice = parseFloat(row.data('original-price'));
                var unitPrice = selectedTierPrice || originalPrice;
                var rowTotal = quantity * unitPrice;

                totalQuantity += quantity;
                totalPrice += rowTotal;
                
                selectedItems.push({
                    size: size,
                    quantity: quantity,
                    unitPrice: unitPrice,
                    total: rowTotal
                });
            }
        });

        if (totalQuantity === 0) {
            AIZ.plugins.notify('warning', '{{ translate('Please select at least one item to add to cart') }}');
            return;
        }

        // Update the main form with total values
        $('input[name="quantity"]').val(totalQuantity);
        
        // Add selected items data to form
        if (!$('#option-choice-form').find('input[name="selected_items"]').length) {
            $('#option-choice-form').append('<input type="hidden" name="selected_items" value="">');
        }
        $('#option-choice-form').find('input[name="selected_items"]').val(JSON.stringify(selectedItems));
        
        console.log('Calling buyNow() with:', {
            totalQuantity: totalQuantity,
            selectedItems: selectedItems
        });
        
        // Call the existing buy now function
        buyNow();
    }

    function addToCart() {
        if (checkAddToCartValidity()) {
            $('#addToCart-modal-body').html('<div class="d-flex justify-content-center"><div class="spinner-border" role="status"><span class="sr-only">Loading...</span></div></div>');
            $('#addToCart-modal').modal();
            $('.c-preloader').show();
            $.ajax({
                type: "POST",
                url: '{{ route('cart.addToCart') }}',
                data: $('#option-choice-form').serializeArray(),
                success: function(data) {
                    $('#addToCart-modal-body').html(data.modal_view);
                    $('.c-preloader').hide();
                    $('#addToCart-modal').modal();
                    updateNavCart(data.nav_cart_view, data.cart_count);

                    // FB Pixel
                    @if (get_setting('facebook_pixel') == 1)
                        fbq('track', 'AddToCart', {
                            content_name: '{{ $detailedProduct->getTranslation('name') }}',
                            content_category: '{{ $detailedProduct->category->getTranslation('name') ?? '' }}',
                            content_ids: ['{{ $detailedProduct->id }}'],
                            content_type: 'product',
                            value: {{ cart_product_price($detailedProduct, $detailedProduct, false, false) }},
                            currency: '{{ get_system_currency()->code }}'
                        });
                    @endif
                }
            });
        } else {
            AIZ.plugins.notify('warning', '{{ translate('Please choose all the options') }}');
        }
    }

    function buyNow() {
        if (checkAddToCartValidity()) {
            $('.c-preloader').show();
            $.ajax({
                type: "POST",
                url: '{{ route('cart.addToCart') }}',
                data: $('#option-choice-form').serializeArray(),
                success: function(data) {
                    if (data.status == 1) {
                        updateNavCart(data.nav_cart_view, data.cart_count);
                        
                        // FB Pixel
                        @if (get_setting('facebook_pixel') == 1)
                            fbq('track', 'AddToCart', {
                                content_name: '{{ $detailedProduct->getTranslation('name') }}',
                                content_category: '{{ $detailedProduct->category->getTranslation('name') ?? '' }}',
                                content_ids: ['{{ $detailedProduct->id }}'],
                                content_type: 'product',
                                value: {{ cart_product_price($detailedProduct, $detailedProduct, false, false) }},
                                currency: '{{ get_system_currency()->code }}'
                            });
                        @endif

                        window.location.replace("{{ route('cart') }}");
                    } else {
                        $('.c-preloader').hide();
                        $('#addToCart-modal-body').html(data.modal_view);
                        $('#addToCart-modal').modal();
                    }
                }
            });
        } else {
            AIZ.plugins.notify('warning', '{{ translate('Please choose all the options') }}');
        }
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

    // Initialization: Call updateAllUnitPrices immediately after page load 
    // to correctly calculate the initial Total Price based on the default Price Tier.
    $(document).ready(function() {
        var firstTier = $('#price-tier-options .price-tier-item.active');
        if (firstTier.length) {
            selectPriceTier(firstTier[0]);
        } else {
            // Fallback if no price tier exists
            updateAllUnitPrices(); 
        }

        // Color initialization (from previous fix)
        const firstColorOptionDiv = $('#color-options .color-option').first();
        if (firstColorOptionDiv.length && typeof selectColor === 'function') {
            const firstColorName = firstColorOptionDiv.data('color');
            const firstColorValue = firstColorOptionDiv.data('color-value');
            selectColor(firstColorOptionDiv[0], firstColorName, firstColorValue);
        }
        
        // Initialize existing rows if any were active (like your example 'M' row)
        // Note: The example 'M' row was hardcoded as active. If this is dynamic, you might need server-side logic here.
        $('#sizeTable tbody tr').each(function() {
            const row = $(this);
            const quantityControl = row.find('.quantity-control');
            if (quantityControl.hasClass('active')) {
                updateTotal(row.find('.quantity-input')[0]);
            }
        });
    });
</script>

<script>
// Mohammad Hassan
// Professional Tab Functionality
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.professional-tab-btn');
    const tabPanes = document.querySelectorAll('.professional-tab-pane');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const targetTab = this.getAttribute('data-tab');
            
            // Remove active class from all buttons and panes
            tabButtons.forEach(btn => btn.classList.remove('active'));
            tabPanes.forEach(pane => pane.classList.remove('active'));
            
            // Add active class to clicked button and corresponding pane
            this.classList.add('active');
            const targetPane = document.getElementById(targetTab + '-tab');
            if (targetPane) {
                targetPane.classList.add('active');
            }
        });
    });
});

// Mohammad Hassan
// Dynamic Color Selection Function
function selectColor(element, colorName, colorValue) {
    console.log('selectColor called with:', { colorName, colorValue });
    
    const hexColor = colorValue ? colorValue.toLowerCase().trim() : '';
    let foundColorName = colorName;

    // 1. Update Custom Color UI
    const colorOptionsDivs = document.querySelectorAll('#color-options .color-option');
    let targetElement = element;
    
    // If element is not directly provided (e.g., called from gallery via value)
    if (!element && hexColor) {
        targetElement = document.querySelector(`#color-options .color-option[data-color-value="${hexColor}"]`);
    }

    colorOptionsDivs.forEach(option => {
        option.classList.remove('selected-color');
        option.style.border = '1px solid #ddd'; // Reset border
    });
    
    if (targetElement) {
        targetElement.classList.add('selected-color');
        targetElement.style.border = '2px solid #6366f1'; // Set border
        
        // Ensure we have correct name/value if we found the element dynamically
        foundColorName = targetElement.getAttribute('data-color');
    }
    
    // 2. Update Selected Color Name Display
    const selectedColorNameSpan = document.getElementById('selected-color-name');
    if (selectedColorNameSpan && foundColorName) {
        console.log('Color name updated from:', selectedColorNameSpan.textContent, 'to:', foundColorName);
        selectedColorNameSpan.textContent = foundColorName;
    }
    
    // 3. Synchronize hidden radio buttons (for form submission)
    const allRadioInputs = document.querySelectorAll('input[name="color"]');
    allRadioInputs.forEach(radioInput => {
        const $radioInput = $(radioInput);
        const $aizMegaboxLabel = $radioInput.closest('.aiz-megabox');
        
        // Match by color name
        if ($radioInput.val() === foundColorName) { 
            $radioInput.prop('checked', true);
            $aizMegaboxLabel.addClass('checked'); 
        } else {
            $radioInput.prop('checked', false);
            $aizMegaboxLabel.removeClass('checked');
        }
    });
    
    // 4. Trigger gallery image filtering (using hex value)
    if (typeof $ !== 'undefined' && hexColor) {
        $(document).trigger('colorChanged', [hexColor]);
    }
    
    // 5. Trigger existing variant price update events
    if (typeof getVariantPrice === 'function') {
        getVariantPrice();
    }
}
</script>
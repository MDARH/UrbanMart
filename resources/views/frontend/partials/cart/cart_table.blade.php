{{-- Mohammad Hassan --}}
{{-- Reusable Cart Table Component --}}
@php
    $is_checkout = $is_checkout ?? false;
    $show_actions = $show_actions ?? true;
    $show_selection = $show_selection ?? true;
    $products = $products ?? [];
    $product_variation = $product_variation ?? [];
    $owner_id = $owner_id ?? null;
    $seller_type = $seller_type ?? 'admin';
@endphp

<div class="table-responsive">
    <table class="table table-borderless">
        <thead class="bg-light">
            <tr>
                @if($show_selection)
                <th class="border-0 fs-14 fw-600" width="5%">
                    <div class="aiz-checkbox-inline">
                        <label class="aiz-checkbox">
                            <input type="checkbox" class="check-all" @if(isset($all_selected) && $all_selected) checked @endif>
                            <span class="aiz-square-check"></span>
                        </label>
                    </div>
                </th>
                @endif
                <th class="border-0 fs-14 fw-600">{{ translate('Product') }}</th>
                <th class="border-0 fs-14 fw-600 text-center">{{ translate('Unit Price') }}</th>
                <th class="border-0 fs-14 fw-600 text-center">{{ translate('Qty') }}</th>
                <th class="border-0 fs-14 fw-600 text-right">{{ translate('Total') }}</th>
                @if($show_actions && !$is_checkout)
                <th class="border-0 fs-14 fw-600 text-center" width="5%">{{ translate('Action') }}</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @php $subtotal = 0; @endphp
            @foreach ($carts as $key => $cart_item)
                @php
                    $product = get_single_product($cart_item['product_id']);
                    if($cart_item && $product) {
                        $unit_price = cart_product_price($cart_item, $product, false, false);
                        $quantity = $cart_item['quantity'];
                        $total = $unit_price * $quantity;
                        $subtotal += $total;
                    }
                @endphp
                @if($cart_item && $product)
                    {{-- Mohammad Hassan - Cart item row integrated directly --}}
                    <tr class="cart-item-row">
                        @if($show_selection)
                        <td class="align-middle">
                            <div class="aiz-checkbox">
                                <label class="aiz-checkbox">
                                    <input type="checkbox" class="check-one {{ isset($seller_type) ? 'check-one-'.$seller_type : '' }}" 
                                           name="id[]" value="{{ $product->id }}" 
                                           @if($cart_item['status'] == 1) checked @endif>
                                    <span class="aiz-square-check"></span>
                                </label>
                            </div>
                        </td>
                        @endif
                        
                        <!-- Product Image & Name -->
                        <td class="align-middle">
                            <div class="d-flex align-items-center">
                                <div class="mr-3">
                                    {{-- Mohammad Hassan - Make product image clickable --}}
                                    <a href="{{ route('product', $product->slug) }}" class="d-block">
                                        <img src="{{ uploaded_asset($product->thumbnail_img) }}"
                                             class="img-fit size-64px rounded"
                                             alt="{{ $product->getTranslation('name') }}"
                                             onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                    </a>
                                </div>
                                <div class="flex-grow-1">
                                    {{-- Mohammad Hassan - Make product name clickable --}}
                                    <a href="{{ route('product', $product->slug) }}" class="text-decoration-none">
                                        <h6 class="fs-14 fw-600 text-dark mb-1 hover-text-primary">{{ $product->getTranslation('name') }}</h6>
                                    </a>
                                    
                                    @if($cart_item['variation'] != '')
                                        <div class="fs-12 text-secondary mb-1">
                                            {{ translate('Variation') }}: {{ $cart_item['variation'] }}
                                        </div>
                                    @endif
                                    
                                    {{-- Mohammad Hassan - Add preorder information display --}}
                                    @if($product->isOutOfStock() && $product->isPreorderAvailable())
                                        <div class="fs-12 mb-1">
                                            <span class="preorder-badge">
                                                <i class="las la-clock mr-1"></i>{{ translate('Pre-order Item') }}
                                            </span>
                                        </div>
                                        <div class="fs-11 text-muted">
                                            {{ translate('50% advance payment required') }}
                                        </div>
                                        @if($product->available_date)
                                            <div class="fs-11 text-info">
                                                {{ translate('Expected availability') }}: {{ date('M d, Y', strtotime($product->available_date)) }}
                                            </div>
                                        @endif
                                    @endif
                                    
                                    <div class="fs-12 text-secondary mt-1">
                                        {{ translate('Tax') }}: {{ cart_product_tax($cart_item, $product) }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        
                        <!-- Unit Price -->
                        <td class="text-center align-middle">
                            <span class="fw-600 fs-14">{{ single_price($unit_price) }}</span>
                        </td>
                        
                        <!-- Quantity -->
                        <td class="text-center align-middle">
                            @if(!$is_checkout && $product->digital != 1 && $product->auction_product == 0)
                                <div class="aiz-plus-minus mx-auto" style="width: 130px;">
                                    <button class="btn btn-icon btn-sm btn-light rounded-0" type="button" 
                                            data-type="minus" data-field="quantity[{{ $cart_item['id'] }}]">
                                        <i class="las la-minus"></i>
                                    </button>
                                    {{-- Mohammad Hassan - Store original value for error handling --}}
                                    <input type="number" name="quantity[{{ $cart_item['id'] }}]"
                                           class="border-0 text-center px-0 fs-14 input-number"
                                           value="{{ $quantity }}"
                                           data-original-value="{{ $quantity }}"
                                           min="{{ $product->min_qty }}"
                                           onchange="updateQuantity({{ $cart_item['id'] }}, this)"
                                           style="width: 50px;">
                                    <button class="btn btn-icon btn-sm btn-light rounded-0" type="button" 
                                            data-type="plus" data-field="quantity[{{ $cart_item['id'] }}]">
                                        <i class="las la-plus"></i>
                                    </button>
                                </div>
                            @else
                                <span class="fw-600 fs-14">{{ $quantity }}</span>
                            @endif
                        </td>
                        
                        <!-- Total Price -->
                        <td class="text-right align-middle">
                            <span class="fw-700 fs-16 text-primary">{{ single_price($total) }}</span>
                        </td>
                        
                        @if($show_actions && !$is_checkout)
                        <!-- Actions -->
                        <td class="text-center align-middle">
                            <a href="javascript:void(0)" onclick="removeFromCartView(event, {{ $cart_item['id'] }})" 
                               class="btn btn-icon btn-sm btn-soft-danger" data-toggle="tooltip" 
                               data-title="{{ translate('Remove') }}">
                                <i class="las la-trash"></i>
                            </a>
                        </td>
                        @endif
                    </tr>
                @endif
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="@if($show_selection){{ $show_actions && !$is_checkout ? '6' : '5' }}@else{{ $show_actions && !$is_checkout ? '5' : '4' }}@endif" class="text-right">
                    <strong>{{ translate('Subtotal') }}: {{ single_price($subtotal) }}</strong>
                </td>
            </tr>
        </tfoot>
    </table>
</div>
<?php

namespace App\Utility;

use App\Models\Cart;
use Cookie;

class CartUtility
{

    // Mohammad Hassan - Enhanced cart variant creation with color support
    public static function create_cart_variant($product, $request)
    {
        $str = null;
        if (isset($request['color'])) {
            $str = $request['color'];
        }

        if (isset($product->choice_options) && count(json_decode($product->choice_options)) > 0) {
            //Gets all the choice values of customer choice option and generate a string like Black-S-Cotton
            foreach (json_decode($product->choice_options) as $key => $choice) {
                $attribute_key = 'attribute_id_' . $choice->attribute_id;
                if (isset($request[$attribute_key])) {
                    if ($str != null) {
                        $str .= '-' . str_replace(' ', '', $request[$attribute_key]);
                    } else {
                        $str .= str_replace(' ', '', $request[$attribute_key]);
                    }
                }
            }
        }
        
        // Handle selected_items from table-based selection
        if (isset($request['selected_items'])) {
            $selectedItems = json_decode($request['selected_items'], true);
            if (is_array($selectedItems) && count($selectedItems) > 0) {
                // For multiple items, we'll handle them separately in the controller
                // For now, just use the first item's variant
                $firstItem = $selectedItems[0];
                if (isset($firstItem['size'])) {
                    $str = $firstItem['size'];
                }
            }
        }
        
        return $str;
    }

    // Mohammad Hassan - Enhanced price calculation with price tiers support
    public static function get_price($product, $product_stock, $quantity, $user = null)
    {
        // Check if product_stock is null and fallback to product price
        if ($product_stock === null) {
            $price = $product->unit_price ?? 0;
        } else {
            $price = $product_stock->price;
        }
        
        if ($product->auction_product == 1) {
            $price = $product->bids->max('amount');
        }

        // Mohammad Hassan - Check for price tiers (wholesaler only)
        if ($user === null) {
            $user = auth()->user();
        }
        
        if ($user && $user->user_type == 'wholesaler' && $product->priceTiers && count($product->priceTiers) > 0) {
            // Find the best price tier for the quantity
            $bestTier = null;
            foreach ($product->priceTiers as $tier) {
                if ($quantity >= $tier->min_qty) {
                    if ($bestTier === null || $tier->min_qty > $bestTier->min_qty) {
                        $bestTier = $tier;
                    }
                }
            }
            
            if ($bestTier) {
                $price = $bestTier->price;
            }
        }

        if ($product->wholesale_product && $product_stock !== null) {
            $wholesalePrice = $product_stock->wholesalePrices->where('min_qty', '<=', $quantity)
                ->where('max_qty', '>=', $quantity)
                ->first();
            if ($wholesalePrice) {
                $price = $wholesalePrice->price;
            }
        }

        $price = self::discount_calculation($product, $price);
        return $price;
    }

    public static function discount_calculation($product, $price)
    {
        $discount_applicable = false;

        if (
            $product->discount_start_date == null ||
            (strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
                strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date)
        ) {
            $discount_applicable = true;
        }

        if ($discount_applicable) {
            if ($product->discount_type == 'percent') {
                $price -= ($price * $product->discount) / 100;
            } elseif ($product->discount_type == 'amount') {
                $price -= $product->discount;
            }
        }
        return $price;
    }

    public static function tax_calculation($product, $price)
    {
        $tax = 0;
        foreach ($product->taxes as $product_tax) {
            if ($product_tax->tax_type == 'percent') {
                $tax += ($price * $product_tax->tax) / 100;
            } elseif ($product_tax->tax_type == 'amount') {
                $tax += $product_tax->tax;
            }
        }

        return $tax;
    }

    // Mohammad Hassan - Enhanced cart data saving with color variant and price tier support
    public static function save_cart_data($cart, $product, $request, $quantity, $price, $tax, $shipping_cost, $product_stock = null)
    {
        $cart->product_id = $product->id;
        $cart->price = $price;
        $cart->tax = $tax;
        $cart->shipping_cost = $shipping_cost;
        $cart->quantity = $quantity;
        
        // Mohammad Hassan - Store color variant if available
        if (isset($request['color'])) {
            $cart->color_variant = $request['color'];
        }
        
        // Mohammad Hassan - Store variant name for display purposes
        $variant = CartUtility::create_cart_variant($product, $request);
        if ($variant) {
            $cart->variant_name = $variant;
        }
        
        // Mohammad Hassan - Price tier information is now handled via product_price_tiers table relationship
        // No need to store in cart as we can retrieve it dynamically when needed
        
        $cart->variation = CartUtility::create_cart_variant($product, $request);
        $cart->owner_id = $product->user_id;
        $cart->product_referral_code = null;

        if (Cookie::has('referred_product_id') && Cookie::get('referred_product_id') == $product->id) {
            $cart->product_referral_code = Cookie::get('product_referral_code');
        }

        $cart->save();
    }

    public static function check_auction_in_cart($carts)
    {
        foreach ($carts as $cart) {
            if ($cart->product->auction_product == 1) {
                return true;
            }
        }

        return false;
    }
}

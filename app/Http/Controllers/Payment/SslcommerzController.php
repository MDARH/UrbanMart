<?php

namespace App\Http\Controllers\Payment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CombinedOrder;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\WalletController;
use App\Models\CustomerPackage;
use App\Models\SellerPackage;
use App\Http\Controllers\CustomerPackageController;
use App\Http\Controllers\SellerPackageController;
use App\Models\Order;
use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Session;
use Auth;
// Mohammad Hassan - Import the official SSLCommerz library
use App\Library\SslCommerz\SslCommerzNotification;

session_start();

class SslcommerzController extends Controller
{
   public function pay(Request $request)
    {
        # Here you have to receive all the order data to initate the payment.
        # Lets your oder trnsaction informations are saving in a table called "orders"
        # In orders table order uniq identity is "order_id","order_status" field contain status of the transaction, "grand_total" is the order amount to be paid and "currency" is for storing Site Currency which will be checked with paid currency.
        if (Session::has('payment_type')) {
            // Mohammad Hassan
            $user = Auth::user();
            if (!$user) {
                // Check if guest user ID is stored in session (created during checkout)
                if (Session::has('guest_user_id')) {
                    $user = User::find(Session::get('guest_user_id'));
                    if (!$user) {
                        return redirect()->route('user.login')->with('error', 'Please login to continue payment.');
                    }
                } else {
                    return redirect()->route('user.login')->with('error', 'Please login to continue payment.');
                }
            }
            $userID = $user->id;
            $paymentType = Session::get('payment_type');
            $paymentData = $request->session()->get('payment_data');
            $post_data = array();
            $post_data['currency'] = "BDT";
            $post_data['tran_id'] = substr(md5($userID), 0, 10); // tran_id must be unique
            $post_data['value_a'] = $post_data['tran_id'];
            if ($paymentType == 'cart_payment') {
                $combined_order = CombinedOrder::findOrFail($request->session()->get('combined_order_id'));
                $post_data['total_amount'] = $combined_order->grand_total; # You cant not pay less than 10
                $post_data['tran_id'] = substr(md5($request->session()->get('combined_order_id')), 0, 10); // tran_id must be unique
                $post_data['value_a'] = $post_data['tran_id'];
                $post_data['value_b'] = $request->session()->get('combined_order_id');
            } elseif ($paymentType == 'order_re_payment') {
                $order = Order::findOrFail($paymentData['order_id']);
                $post_data['total_amount'] = $order->grand_total; # You cant not pay less than 10
                $post_data['value_b'] = $paymentData['order_id'];
            } elseif ($paymentType == 'wallet_payment') {
                $post_data['total_amount'] = $paymentData['amount']; # You cant not pay less than 10
                $post_data['value_b'] = $paymentData['amount'];
            } elseif ($paymentType == 'customer_package_payment') {
                $customer_package = CustomerPackage::findOrFail($paymentData['customer_package_id']);
                $post_data['total_amount'] = $customer_package->amount; # You cant not pay less than 10
                $post_data['value_b'] = $paymentData['customer_package_id'];
            } elseif ($paymentType == 'seller_package_payment') {
                $seller_package = SellerPackage::findOrFail($paymentData['seller_package_id']);
                $post_data['total_amount'] = $seller_package->amount; # You cant not pay less than 10
                $post_data['value_b'] = $paymentData['seller_package_id'];
            }
            
            $post_data['value_c'] = $paymentType;
            $post_data['value_d'] = $userID;
            
            // Mohammad Hassan - Add required product_category to fix SSLCommerz error
            $post_data['product_category'] = 'general';
            $post_data['product_name'] = 'E-commerce Products';
            $post_data['product_profile'] = 'general';

            # CUSTOMER INFORMATION
            // Mohammad Hassan - Use the user object we already retrieved above
            $post_data['cus_name'] = $user->name;
            $post_data['cus_add1'] = $user->address ?? 'N/A';
            $post_data['cus_city'] = $user->city ?? 'N/A';
            $post_data['cus_postcode'] = $user->postal_code ?? '0000';
            $post_data['cus_country'] = $user->country ?? 'Bangladesh';
            $post_data['cus_phone'] = $user->phone;
            $post_data['cus_email'] = $user->email ?? 'guest@example.com';
        }

        $server_name = $request->root() . "/";
        $post_data['success_url'] = $server_name . "sslcommerz/success";
        $post_data['fail_url'] = $server_name . "sslcommerz/fail";
        $post_data['cancel_url'] = $server_name . "sslcommerz/cancel";
        //dd($post_data);
        # SHIPMENT INFORMATION
        // $post_data['ship_name'] = 'ship_name';
        // $post_data['ship_add1 '] = 'Ship_add1';
        // $post_data['ship_add2'] = "";
        // $post_data['ship_city'] = "";
        // $post_data['ship_state'] = "";
        // $post_data['ship_postcode'] = "";
        // $post_data['ship_country'] = "Bangladesh";

        # OPTIONAL PARAMETERS
        // $post_data['value_a'] = "ref001";
        // $post_data['value_b'] = "ref002";
        // $post_data['value_c'] = "ref003";
        // $post_data['value_d'] = "ref004";

        // Mohammad Hassan - Use the official SSLCommerz library
        $sslc = new SslCommerzNotification();

        # initiate payment with the official library
        $payment_options = $sslc->makePayment($post_data, 'hosted');
        
        if (!is_array($payment_options)) {
            print_r($payment_options);
            $payment_options = array();
        }
    }

    public function success(Request $request)
    {
        // Mohammad Hassan - Use official SSLCommerz validation with debugging
        $sslc = new SslCommerzNotification();
        
        // Mohammad Hassan - Add debugging logs
        \Log::info('SSLCommerz Success Callback', [
            'request_data' => $request->all(),
            'session_data' => [
                'payment_type' => Session::get('payment_type'),
                'combined_order_id' => Session::get('combined_order_id'),
                'payment_data' => Session::get('payment_data')
            ]
        ]);
        
        #Start to received these value from session. which was saved in index function.
        $tran_id = $request->value_a;
        #End to received these value from session. which was saved in index function.
        $payment = json_encode($request->all());

        # Validate the payment with official library
        $validation = $sslc->orderValidate($request->all(), $tran_id, 0, 'BDT');
        
        // Mohammad Hassan - Log validation result
        \Log::info('SSLCommerz Payment Validation', [
            'tran_id' => $tran_id,
            'validation_result' => $validation,
            'payment_status' => $request->status ?? 'unknown'
        ]);
        
        if ($validation == TRUE) {
            \Log::info('SSLCommerz Payment Validated Successfully', ['tran_id' => $tran_id]);
            
            if (isset($request->value_c)) {
                \Log::info('Processing payment type', ['payment_type' => $request->value_c]);
                
                if ($request->value_c == 'cart_payment') {
                    return (new CheckoutController)->checkout_done($request->value_b, $payment);
                } elseif ($request->value_c == 'order_re_payment') {
                    $data['order_id'] = $request->value_b;
                    $data['payment_method'] = 'sslcommerz';
                    Auth::login(User::find($request->value_d));
                    
                    return (new CheckoutController)->orderRePaymentDone($data, $payment);
                } elseif ($request->value_c == 'wallet_payment') {
                    $data['amount'] = $request->value_b;
                    $data['payment_method'] = 'sslcommerz';
                    Auth::login(User::find($request->value_d));

                    return (new WalletController)->wallet_payment_done($data, $payment);
                } elseif ($request->value_c == 'customer_package_payment') {
                    $data['customer_package_id'] = $request->value_b;
                    $data['payment_method'] = 'sslcommerz';
                    Auth::login(User::find($request->value_d));

                    return (new CustomerPackageController)->purchase_payment_done($data, $payment);
                } elseif ($request->value_c == 'seller_package_payment') {
                    $data['seller_package_id'] = $request->value_b;
                    $data['payment_method'] = 'sslcommerz';
                    Auth::login(User::find($request->value_d));

                    return (new SellerPackageController)->purchase_payment_done(json_decode($request->value_b), $payment);
                }
            } else {
                \Log::warning('SSLCommerz Success: No payment type found in request', ['request' => $request->all()]);
            }
        } else {
            // Payment validation failed
            \Log::error('SSLCommerz Payment Validation Failed', [
                'tran_id' => $tran_id,
                'request_data' => $request->all()
            ]);
            flash(translate('Payment validation failed'))->error();
            return redirect()->route('home');
        }
    }

    public function fail(Request $request)
    {
        // Mohammad Hassan - Add debugging for payment failures
        \Log::error('SSLCommerz Payment Failed', [
            'request_data' => $request->all(),
            'session_data' => [
                'order_id' => $request->session()->get('order_id'),
                'payment_data' => $request->session()->get('payment_data'),
                'combined_order_id' => $request->session()->get('combined_order_id')
            ]
        ]);
        
        $request->session()->forget('order_id');
        $request->session()->forget('payment_data');
        flash(translate('Payment Failed'))->warning();
        return redirect()->route('home');
    }

    public function cancel(Request $request)
    {
        // Mohammad Hassan - Add debugging for payment cancellations
        \Log::warning('SSLCommerz Payment Cancelled', [
            'request_data' => $request->all(),
            'session_data' => [
                'order_id' => $request->session()->get('order_id'),
                'payment_data' => $request->session()->get('payment_data'),
                'combined_order_id' => $request->session()->get('combined_order_id')
            ]
        ]);
        
        $request->session()->forget('order_id');
        $request->session()->forget('payment_data');
        flash(translate('Payment cancelled'))->error();
        return redirect()->route('home');
    }

    public function ipn(Request $request)
    {
        #Received all the payement information from the gateway
        if ($request->input('tran_id')) #Check transation id is posted or not.
        {

            $tran_id = $request->input('tran_id');

            #Check order status in order tabel against the transaction id or order id.
            $combined_order = CombinedOrder::findOrFail($request->session()->get('combined_order_id'));

            if ($order->payment_status == 'Pending') {
                $sslc = new SSLCommerz();
                $validation = $sslc->orderValidate($tran_id, $order->grand_total, 'BDT', $request->all());
                if ($validation == TRUE) {
                    /*
                        That means IPN worked. Here you need to update order status
                        in order table as Processing or Complete.
                        Here you can also sent sms or email for successfull transaction to customer
                        */
                    echo "Transaction is successfully Complete";
                } else {
                    /*
                        That means IPN worked, but Transation validation failed.
                        Here you need to update order status as Failed in order table.
                        */

                    echo "validation Fail";
                }
            }
        } else {
            echo "Inavalid Data";
        }
    }
}

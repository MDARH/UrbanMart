<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Cart;
use App\Notifications\EmailVerificationNotification;
use App\Traits\PreventDemoModeChanges;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, HasApiTokens, HasRoles;


    public function sendEmailVerificationNotification()
    {
        $this->notify(new EmailVerificationNotification());
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    //  protected $fillable = [
    //      'name', 'email', 'password', 'address', 'city', 'postal_code', 'phone', 'country', 'provider_id', 'email_verified_at', 'verification_code','user_type',           // NEW
    //     'facebook_link',       // NEW
    //     'website_link',        // NEW  
    //     'address',             // NEW
    //     'trade_license_number', // NEW
    //     'approved_at',         // NEW
    //     'approval_status'      // NEW
    //  ];
      protected $fillable = [
        'name',
        'email',
        'password',
        'user_type',
        'status',
        'phone',
        'business_name',
        'facebook_link',
        'website_link',
        'address',
        'trade_license',
        'verification_token',
        'is_verified',
        'profile_image',
        'preferences',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'verification_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'approved_at' => 'datetime',
        'password' => 'hashed',
        'preferences' => 'array',
    ];
     public function isWholesaler()
    {
        return $this->user_type === 'wholesaler';
    }
     public function isRegularUser()
    {
        return $this->user_type === 'user';
    }

    public function isApproved()
    {
        return $this->status === 'active';
    }

    public function isVerified()
    {
        return $this->is_verified === true;
    }

    public function scopeWholesalers($query)
    {
        return $query->where('user_type', 'wholesaler');
    }

    public function scopeRegularUsers($query)
    {
        return $query->where('user_type', 'user');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'active');
    }
    public function scopePendingApproval($query)
    {
        return $query->where('status', 'pending');
    }
//   The END For Normal user & Wholesaler Part





    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function customer()
    {
        return $this->hasOne(Customer::class);
    }

    public function affiliate_user()
    {
        return $this->hasOne(AffiliateUser::class);
    }

    public function affiliate_withdraw_request()
    {
        return $this->hasMany(AffiliateWithdrawRequest::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function shop()
    {
        return $this->hasOne(Shop::class);
    }
    public function seller()
    {
        return $this->hasOne(Seller::class);
    }


    public function staff()
    {
        return $this->hasOne(Staff::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function seller_orders()
    {
        return $this->hasMany(Order::class, "seller_id");
    }
    public function seller_sales()
    {
        return $this->hasMany(OrderDetail::class, "seller_id");
    }

    public function wallets()
    {
        return $this->hasMany(Wallet::class)->orderBy('created_at', 'desc');
    }

    public function club_point()
    {
        return $this->hasOne(ClubPoint::class);
    }

    public function customer_package()
    {
        return $this->belongsTo(CustomerPackage::class);
    }

    public function customer_package_payments()
    {
        return $this->hasMany(CustomerPackagePayment::class);
    }

    public function customer_products()
    {
        return $this->hasMany(CustomerProduct::class);
    }

    public function seller_package_payments()
    {
        return $this->hasMany(SellerPackagePayment::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function affiliate_log()
    {
        return $this->hasMany(AffiliateLog::class);
    }

    public function product_bids()
    {
        return $this->hasMany(AuctionProductBid::class);
    }

    public function product_queries(){
        return $this->hasMany(ProductQuery::class,'customer_id');
    }

    public function uploads(){
        return $this->hasMany(Upload::class);
    }

    public function userCoupon(){
        return $this->hasOne(UserCoupon::class);
    }

    public function preorderProducts()
    {
        return $this->hasMany(PreorderProduct::class);
    }
    public function preorders()
    {
        return $this->hasMany(Preorder::class);
    }
}

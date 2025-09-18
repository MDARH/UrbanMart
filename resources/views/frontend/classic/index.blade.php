@extends('frontend.layouts.app')

@section('content')
    <style>
        @media (max-width: 767px) {
            #flash_deal .flash-deals-baner {
                height: 203px !important;
            }
        }
    </style>
    @php $lang = get_system_language()->code; @endphp

    <!DOCTYPE html>
    <html lang="bn">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>UrbanMart - Online Shopping Bangladesh</title>
        <!-- Google Fonts for better icon support if needed -->
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab:wght@100..900&display=swap" rel="stylesheet">
    </head>

    <body>
        <!-- Main Container -->
        <div class="main-container" style="background-color: #F2F4F8;">
            <!-- Left Sidebar - Sticky (Desktop Only) -->
            @include('frontend.classic.partials.category_menu')

            <!-- Right Content Area -->
            <main class="right-content">
                <!-- Mobile-specific horizontal category scroll section (Now hidden on mobile per request) -->
                <section class="mobile-categories-scroll">
                    <div class="category-grid">
                        <a href="#" class="category-item">
                            <div class="category-icon">👜</div>
                            <div class="category-name">Bags</div>
                        </a>
                        <a href="#" class="category-item">
                            <div class="category-icon">💍</div>
                            <div class="category-name">Jewelry</div>
                        </a>
                        <a href="#" class="category-item">
                            <div class="category-icon">👟</div>
                            <div class="category-name">Shoes</div>
                        </a>
                        <a href="#" class="category-item">
                            <div class="category-icon">💄</div>
                            <div class="category-name">Beauty</div>
                        </a>
                        <a href="#" class="category-item">
                            <div class="category-icon">👔</div>
                            <div class="category-name">Men's Clothing</div>
                        </a>
                        <a href="#" class="category-item">
                            <div class="category-icon">👗</div>
                            <div class="category-name">Women's Clothing</div>
                        </a>
                        <a href="#" class="category-item">
                            <div class="category-icon">🍼</div>
                            <div class="category-name">Baby Items</div>
                        </a>
                        <a href="#" class="category-item">
                            <div class="category-icon">🕶️</div>
                            <div class="category-name">Eyewear</div>
                        </a>
                        <a href="#" class="category-item">
                            <div class="category-icon">📱</div>
                            <div class="category-name">Phone Accessories</div>
                        </a>
                        <a href="#" class="category-item">
                            <div class="category-icon">⚽</div>
                            <div class="category-name">Sports & Fitness</div>
                        </a>
                        <a href="#" class="category-item">
                            <div class="category-icon">🎮</div>
                            <div class="category-name">Entertainment</div>
                        </a>
                        <a href="#" class="category-item">
                            <div class="category-icon">⌚</div>
                            <div class="category-name">Watches</div>
                        </a>
                        <a href="#" class="category-item">
                            <div class="category-icon">🚗</div>
                            <div class="category-name">Auto Items</div>
                        </a>
                        <a href="#" class="category-item">
                            <div class="category-icon">🛒</div>
                            <div class="category-name">Groceries</div>
                        </a>
                        <a href="#" class="category-item">
                            <div class="category-icon">🏠</div>
                            <div class="category-name">Home & Living</div>
                        </a>
                        <a href="#" class="category-item">
                            <div class="category-icon">📚</div>
                            <div class="category-name">Books & Media</div>
                        </a>
                    </div>
                </section>

                <section class="hero-section">
                    <!-- Middle Slider Column Start-->
                    @if (get_setting('home_slider_images', null, $lang) != null)
                        <div class="slider-container">
                            <div class="slider" id="autoSlider">
                                @php
                                    $decoded_slider_images = json_decode(get_setting('home_slider_images', null, $lang), true);
                                    $sliders = get_slider_images($decoded_slider_images);
                                    $home_slider_links = json_decode(get_setting('home_slider_links', null, $lang), true) ?? [];
                                @endphp
                                @foreach ($sliders as $key => $slider)
                                    <div class="slide">
                                        <a href="{{ $home_slider_links[$key] ?? '#' }}">
                                            <img class="d-block mw-100 img-fit overflow-hidden h-180px h-md-320px h-lg-460px rounded w-100"
                                                src="{{ $slider ? my_asset($slider->file_name) : static_asset('assets/img/placeholder.jpg') }}"
                                                alt="{{ env('APP_NAME') }} promo"
                                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                                        </a>
                                    </div>
                                @endforeach
                            </div>

                            <div class="slider-nav">
                                @foreach ($sliders as $key => $slider)
                                    <div class="nav-dot @if ($key == 0) active @endif" onclick="changeSlide({{ $key }})"></div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    <style>
                        .slider-container {
                            position: relative;
                            overflow: hidden;
                            border-radius: 15px;
                        }

                        .slider {
                            display: flex;
                            transition: transform .8s ease-in-out;
                            will-change: transform;
                        }

                        .slide {
                            flex: 0 0 100%;
                            min-width: 100%;
                            height: 100%;
                        }

                        .slide img {
                            display: block;
                            width: 100%;
                            height: 180px;
                            object-fit: cover;
                            object-position: center;
                            border-radius: 15px;
                        }

                        @media (min-width: 768px) {
                            .slide img {
                                height: 320px;
                            }
                        }

                        @media (min-width: 992px) {
                            .slide img {
                                height: 460px;
                            }
                        }
                    </style>
                    <!-- Middle Slider Column End-->

                    <!-- Login Cards Container (Right Side) -->
                <div class="login-cards-container">
                        <div class="login-card user-login" onclick="openUserLogin()">
                            <div class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" viewBox="0 0 20 20"
                                    style="width: 80% !important;" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                            <h3>User Login</h3>
                            <p>Access your account, track orders, and manage your profile</p>
                        </div>
                        <div class="login-card wholesaler-login" onclick="openWholesalerLogin()">
                            <div class="icon">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24"
                                    style="width: 80% !important;" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <h3>Wholesaler Login</h3>
                            <p>Get wholesale prices, bulk discounts, and business solutions</p>
                        </div>
                    </div>

                </section>

                <!-- Categories Section Style Start-->

                <section class="categories-section">
                    <h2 class="section-title">Popular Categories</h2>

                    <div class="categories-grid" style="margin-top: 30px;">
                        @foreach (get_level_zero_categories()->take(18) as $category)
                            @php
                                $name = $category->getTranslation('name');
                                $slug = $category->slug;
                                $icon = isset($category->catIcon->file_name)
                                    ? my_asset($category->catIcon->file_name)
                                    : static_asset('assets/img/placeholder.jpg');
                            @endphp

                            <a class="category-card" href="{{ route('products.category', $slug) }}" title="{{ $name }}">
                                <span class="category-card-icon">
                                    <img src="{{ static_asset('assets/img/placeholder.jpg') }}" data-src="{{ $icon }}"
                                        class="lazyload cat-icon" alt="{{ $name }}">
                                </span>
                                <div class="category-card-name">{{ $name }}</div>
                            </a>
                        @endforeach
                    </div>
                </section>

                <style>
                    .category-card-icon {
                        width: 40px;
                        height: 40px;
                        display: inline-grid;
                        place-items: center;
                    }

                    .category-card-icon img {
                        max-width: 35px;
                        max-height: 35px;
                        width: auto;
                        height: auto;
                        object-fit: contain;
                        border-radius: 8px;
                    }

                    .category-card-name {
                        font-size: 14px;
                        font-weight: 600;
                        color: #0f172a;
                        max-width: 140px;
                        white-space: nowrap;
                        overflow: hidden;
                        text-overflow: ellipsis;
                        padding: 0px 4px;
                    }

                    .category-card-name {
                        display: -webkit-box;
                        -webkit-box-orient: vertical;
                        -webkit-line-clamp: 2;
                        overflow: hidden;
                    }

                    .categories-section {
                        background: white;
                        border-radius: 15px;
                        padding: 25px;
                        margin-bottom: 20px;
                        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
                    }

                    .section-title {
                        font-size: 24px;
                        font-weight: bold;
                        color: #1A365D;
                        margin-bottom: 20px;
                        text-align: center;
                    }

                    .categories-grid {
                        display: grid;
                        grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                        gap: 15px;
                    }

                    .category-card {
                        background: #f8f9fa;
                        border-radius: 12px;
                        /* padding: 20px; */
                        text-align: center;
                        cursor: pointer;
                        transition: all 0.3s ease;
                        border: 2px solid transparent;
                        display: flex;
                        justify-content: center;
                        align-items: center;
                    }

                    .category-card:hover {
                        background: linear-gradient(135deg, #E6FFFA, #B2F5EA);
                        border-color: #319795;
                        transform: translateY(-5px);
                        box-shadow: 0 10px 25px rgba(49, 151, 149, 0.2);
                    }

                    .category-card-icon {
                        font-size: 32px;
                        margin-bottom: 10px;
                        display: block;
                        margin-right: 8px;
                    }

                    .category-card-name {
                        font-size: 12px;
                        font-weight: bold;
                        color: #333;
                    }
                </style>
                <!-- Categories Section Style End-->

                {{-- Flash Sale Products Start--}}

                @php
                    $flash_deal = get_featured_flash_deal();
                @endphp

                @if ($flash_deal)
                    <section id="flash_deal" class="flash-section products-section">
                        <div class="container-full">
                            <!-- header -->
                            <div class="flash-head">
                                <h3 class="flash-title" style="color: var(--skybuy-blue);">
                                    {{ translate('Flash Sale') }}
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="24" viewBox="0 0 16 24"
                                        class="ml-1">
                                        <path
                                            d="M30.953,13.695a.474.474,0,0,0-.424-.25h-4.9l3.917-7.81a.423.423,0,0,0-.028-.428.477.477,0,0,0-.4-.207H21.588a.473.473,0,0,0-.429.263L15.041,18.151a.423.423,0,0,0,.034.423.478.478,0,0,0,.4.2h4.593l-2.229,9.683a.438.438,0,0,0,.259.5.489.489,0,0,0,.571-.127L30.9,14.164a.425.425,0,0,0,.054-.469Z"
                                            transform="translate(-15 -5)" fill="#fcc201" />
                                    </svg>
                                </h3>

                                <div class="flash-links">
                                    <a class="view-all" href="{{ route('flash-deal-details', $flash_deal->slug) }}">
                                        {{ translate('View All Flash Sale') }}
                                    </a>
                                </div>
                            </div>

                            <!-- layout -->
                            <div class="row gutters-5">
                                <!-- left: banner + countdown -->
                                {{-- <div class="col-12 col-lg-4">
                                    <a href="{{ route('flash-deal-details', $flash_deal->slug) }}" class="flash-banner">
                                        <div class="flash-banner-bg"
                                            style="background-image:url('{{ uploaded_asset($flash_deal->banner) }}')">
                                            <div class="cd-wrap d-none d-md-block">
                                                <div class="aiz-count-down-circle"
                                                    end-date="{{ date('Y/m/d H:i:s', $flash_deal->end_date) }}"></div>
                                            </div>
                                        </div>
                                    </a>

                                    <!-- mobile text countdown -->
                                    <div class="flash-count d-md-none"
                                        data-end-date="{{ date('Y/m/d H:i:s', $flash_deal->end_date) }}">
                                        {{ translate('Ends in:') }}
                                        <span id="simple-days">00</span> {{ translate('days') }}
                                        <span id="simple-hours">00</span> {{ translate('hrs') }}
                                        <span id="simple-mins">00</span> {{ translate('min') }}
                                        <span id="simple-secs">00</span> {{ translate('sec') }}
                                    </div>
                                </div> --}}

                                <!-- right: products grid -->
                                <div class="col-12 col-lg-12">
                                    @php
                                        $flash_deal_products = get_flash_deal_products($flash_deal->id);
                                    @endphp

                                    <div class="products-grid">
                                        @foreach ($flash_deal_products as $fd)
                                            @if ($fd->product && $fd->product->published)
                                                @php
                                                    $p = $fd->product;
                                                    $url = $p->auction_product ? route('auction-product', $p->slug) : route('product', $p->slug);
                                                    $name = $p->getTranslation('name');
                                                    $thumb = get_image($p->thumbnail) ?: static_asset('assets/img/placeholder.jpg');
                                                    $now = home_discounted_base_price($p);
                                                    $was = home_base_price($p);
                                                  @endphp

                                                <a href="{{ $url }}" class="fx-card" title="{{ $name }}">
                                                    <div class="fx-img">
                                                        <img src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                            data-src="{{ $thumb }}" class="lazyload" alt="{{ $name }}">
                                                    </div>
                                                    <h4 class="fx-title">{{ $name }}</h4>
                                                    <div class="fx-price">
                                                        <span class="now">{{ $now }}</span>
                                                        @if($now !== $was)
                                                            <span class="was">{{ $was }}</span>
                                                        @endif
                                                    </div>
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                @endif
                <style>
                    /* header */
                    .flash-section {
                        margin-top: 16px;
                        margin-bottom: 16px
                    }

                    .flash-head {
                        display: flex;
                        flex-wrap: wrap;
                        align-items: center;
                        justify-content: space-between;
                        margin-bottom: 12px;
                        padding-bottom: 10px;
                        border-bottom: 1.5px solid #e5e7eb
                    }

                    .flash-title {
                        font: 800 20px/1.2 ui-sans-serif, system-ui;
                        color: #0f172a;
                        margin: 0;
                        display: flex;
                        align-items: center;
                        gap: 6px
                    }

                    .flash-links {
                        display: flex;
                        gap: 16px
                    }

                    .flash-links .view-all {
                        background: var(--skybuy-blue);
                        color: #fff;
                        font-weight: 700;
                        font-size: 12.5px;
                        padding: 7px 12px;
                        border-radius: 8px;
                        text-decoration: none
                    }

                    .flash-links .view-all:hover {
                        background: var(--skybuy-blue);
                    }

                    /* banner */
                    .flash-banner {
                        display: block;
                        border-radius: 12px;
                        overflow: hidden
                    }

                    .flash-banner-bg {
                        height: 315px;
                        background-size: cover;
                        background-position: center
                    }

                    @media (max-width:991.98px) {
                        .flash-banner-bg {
                            height: 240px
                        }
                    }

                    @media (max-width:575.98px) {
                        .flash-banner-bg {
                            height: 208px
                        }
                    }

                    .cd-wrap {
                        background: #fff;
                        border-radius: 10px;
                        display: inline-block;
                        padding: 10px;
                        margin: 18px
                    }

                    /* mobile countdown text */
                    .flash-count {
                        margin-top: 10px;
                        text-align: center;
                        font-weight: 700
                    }

                    /* grid */
                    .flash-grid {
                        display: grid;
                        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                        gap: 16px
                    }

                    /* responsive RAM */
                    /* [1][2] */

                    /* card */
                    .fx-card {
                        display: block;
                        background: #fff;
                        border: 1px solid #e6e8ec;
                        border-radius: 12px;
                        overflow: hidden;
                        box-shadow: 0 1px 6px rgba(2, 6, 23, .05);
                        transition: transform .16s, box-shadow .16s, border-color .16s;
                        color: inherit;
                        text-decoration: none
                    }

                    .fx-card:hover {
                        transform: translateY(-2px);
                        box-shadow: 0 10px 20px rgba(2, 6, 23, .08);
                        border-color: #dfe3ea
                    }

                    .fx-img {
                        height: 180px;
                        background: #f6f7fb;
                        overflow: hidden
                    }

                    .fx-img img {
                        width: 100%;
                        height: 100%;
                        object-fit: cover;
                        display: block
                    }

                    /* uniform crop */
                    /* [3] */
                    .fx-title {
                        font: 600 13.5px/1.35 ui-sans-serif, system-ui;
                        color: #0f172a;
                        margin: 10px 12px 8px;
                        height: 36px;
                        display: -webkit-box;
                        -webkit-line-clamp: 2;
                        -webkit-box-orient: vertical;
                        overflow: hidden
                    }

                    .fx-price {
                        display: flex;
                        align-items: center;
                        gap: 8px;
                        margin: 0 12px 12px
                    }

                    .fx-price .now {
                        color: #e11d48;
                        font-weight: 800;
                        font-size: 14px
                    }

                    .fx-price .was {
                        color: #9aa3b2;
                        text-decoration: line-through;
                        font-size: 12.5px
                    }
                </style>
                {{-- Flash Sale Products End--}}

                {{-- Featured Products STart --}}
                <section id="section_featured">
                </section>
                {{-- Featured Products End --}}

                <section id="section_best_selling">
                </section>
                {{-- Featured Products End --}}

                {{-- New Products Start --}}
                <section id="section_newest">
                </section>
                {{-- New Products End --}}

                {{-- All Products Start --}}
                <section id="section_home_categories">
                </section>
                {{-- All Products End --}}

                <!-- Footer -->
                {{-- <footer class="footer">
                    <div class="footer-content">
                        <div class="footer-section">
                            <h4>About Urban Mart BD</h4>
                            <p>Your trusted online shopping partner in Bangladesh. We bring you quality products from
                                around
                                the world at competitive prices.</p>
                            <p>Established in 2020, serving customers nationwide with dedication.</p>
                        </div>
                        <div class="footer-section">
                            <h4>Customer Service</h4>
                            <a href="#">Help Center</a>
                            <a href="#">Shipping Info</a>
                            <a href="#">Returns & Refunds</a>
                            <a href="#">Size Guide</a>
                            <a href="#">Contact Us</a>
                        </div>
                        <div class="footer-section">
                            <h4>My Account</h4>
                            <a href="#" onclick="openUserLogin()">Sign In</a>
                            <a href="#">Order History</a>
                            <a href="#" onclick="showPage('wishlist')">Wishlist</a>
                            <a href="#">Track Order</a>
                        </div>
                        <div class="footer-section">
                            <h4>Connect With Us</h4>
                            <p>📧 support@urbanmart.com</p>
                            <p>📞 +880 1700-000000</p>
                            <p>📍 Dhaka, Bangladesh</p>
                            <a href="#">Facebook</a>
                            <a href="#">Instagram</a>
                        </div>
                    </div>
                    <div class="footer-bottom">
                        <p>&copy; 2025 Urban Mart BD. All rights reserved. | Privacy Policy | Terms of Service</p>
                    </div>
                </footer> --}}
                @include('frontend.inc.footer')

            </main>
        </div>


        <!-- NEW: Mobile Category Sidebar (positioned after main content but before bottom-nav) -->
        <div id="mobileCategorySidebar" class="mobile-category-sidebar">
            <div class="sidebar-header">
                <h3>Categories</h3>
                <span class="close-sidebar" onclick="closeMobileCategorySidebar()">&times;</span>
            </div>
            <div class="category-grid">
                <!-- These categories will be dynamically loaded or hardcoded, matching the desktop sidebar -->
                <a href="#" class="category-item" onclick="filterByCategory('bags', event); closeMobileCategorySidebar();">
                    <div class="category-icon">👜</div>
                    <div class="category-name">Bags</div>
                </a>
                <a href="#" class="category-item"
                    onclick="filterByCategory('jewelry', event); closeMobileCategorySidebar();">
                    <div class="category-icon">💍</div>
                    <div class="category-name">Jewelry</div>
                </a>
                <a href="#" class="category-item" onclick="filterByCategory('shoes', event); closeMobileCategorySidebar();">
                    <div class="category-icon">👟</div>
                    <div class="category-name">Shoes</div>
                </a>
                <a href="#" class="category-item"
                    onclick="filterByCategory('beauty', event); closeMobileCategorySidebar();">
                    <div class="category-icon">💄</div>
                    <div class="category-name">Beauty</div>
                </a>
                <a href="#" class="category-item"
                    onclick="filterByCategory('mens-clothing', event); closeMobileCategorySidebar();">
                    <div class="category-icon">👔</div>
                    <div class="category-name">Men's Clothing</div>
                </a>
                <a href="#" class="category-item"
                    onclick="filterByCategory('womens-clothing', event); closeMobileCategorySidebar();">
                    <div class="category-icon">👗</div>
                    <div class="category-name">Women's Clothing</div>
                </a>
                <a href="#" class="category-item" onclick="filterByCategory('baby', event); closeMobileCategorySidebar();">
                    <div class="category-icon">🍼</div>
                    <div class="category-name">Baby Items</div>
                </a>
                <a href="#" class="category-item"
                    onclick="filterByCategory('eyewear', event); closeMobileCategorySidebar();">
                    <div class="category-icon">🕶️</div>
                    <div class="category-name">Eyewear</div>
                </a>
                <a href="#" class="category-item"
                    onclick="filterByCategory('phone-accessories', event); closeMobileCategorySidebar();">
                    <div class="category-icon">📱</div>
                    <div class="category-name">Phone Accessories</div>
                </a>
                <a href="#" class="category-item"
                    onclick="filterByCategory('sports', event); closeMobileCategorySidebar();">
                    <div class="category-icon">⚽</div>
                    <div class="category-name">Sports & Fitness</div>
                </a>
                <a href="#" class="category-item"
                    onclick="filterByCategory('entertainment', event); closeMobileCategorySidebar();">
                    <div class="category-icon">🎮</div>
                    <div class="category-name">Entertainment</div>
                </a>
                <a href="#" class="category-item"
                    onclick="filterByCategory('watches', event); closeMobileCategorySidebar();">
                    <div class="category-icon">⌚</div>
                    <div class="category-name">Watches</div>
                </a>
                <a href="#" class="category-item"
                    onclick="filterByCategory('auto-items', event); closeMobileCategorySidebar();">
                    <div class="category-icon">🚗</div>
                    <div class="category-name">Auto Items</div>
                </a>
                <a href="#" class="category-item"
                    onclick="filterByCategory('groceries', event); closeMobileCategorySidebar();">
                    <div class="category-icon">🛒</div>
                    <div class="category-name">Groceries</div>
                </a>
                <a href="#" class="category-item"
                    onclick="filterByCategory('home-living', event); closeMobileCategorySidebar();">
                    <div class="category-icon">🏠</div>
                    <div class="category-name">Home & Living</div>
                </a>
                <a href="#" class="category-item"
                    onclick="filterByCategory('books-media', event); closeMobileCategorySidebar();">
                    <div class="category-icon">📚</div>
                    <div class="category-name">Books & Media</div>
                </a>
            </div>
        </div>


        <!-- NEW: Bottom Navigation Bar for Mobile -->
        <nav class="bottom-nav">
            <a href="#" class="bottom-nav-item active" onclick="scrollToTop(); return false;">
                <span class="icon">🏠</span>
                <span>Home</span>
            </a>
            <a href="#" class="bottom-nav-item" id="bottomNavCategoryBtn"
                onclick="openMobileCategorySidebar(); return false;">
                <span class="icon">🏷️</span>
                <span>Category</span>
            </a>
            <a href="#" class="bottom-nav-item parachute-button">
                <img src="https://skybuybd.com/_next/static/media/logo.2d8160b9.svg" alt="Skybuybd Logo">
            </a>
            <a href="#" class="bottom-nav-item" onclick="openUserLogin(); return false;">
                <span class="icon">👤</span>
                <span>Account</span>
            </a>
            <a href="#" class="bottom-nav-item" onclick="alert('Chat functionality coming soon!'); return false;">
                <span class="icon">💬</span>
                <span>Chat</span>
            </a>
        </nav>

        <!-- NEW: Authentication Modal (for both Login and Register) -->
   {{-- @includeIf('frontend.classic.partials.authentication') --}}
   @include('auth.modals')
        <!-- End NEW: Authentication Modal -->

        <script>
            // --- Slider JavaScript ---
            let currentSlideIndex = 0;
            const slides = document.querySelectorAll('.slide');
            const dots = document.querySelectorAll('.nav-dot');
            const totalSlides = slides.length;
            let autoSlideInterval;

            function showSlide(index) {
                currentSlideIndex = (index + totalSlides) % totalSlides;
                const slider = document.getElementById('autoSlider');
                slider.style.transform = `translateX(-${currentSlideIndex * 100}%)`;

                dots.forEach(dot => dot.classList.remove('active'));
                dots[currentSlideIndex].classList.add('active');
            }

            function nextSlide() {
                showSlide(currentSlideIndex + 1);
            }

            function changeSlide(index) {
                showSlide(index);
                resetAutoSlide();
            }

            function startAutoSlide() {
                autoSlideInterval = setInterval(nextSlide, 5000);
            }

            function resetAutoSlide() {
                clearInterval(autoSlideInterval);
                startAutoSlide();
            }

            // --- Generic Page Content Display (if still needed for wishlist) ---
            function showPage(pageId) {
                document.querySelectorAll('.page-content').forEach(page => {
                    page.style.display = 'none';
                });
                const targetPage = document.getElementById(pageId);
                if (targetPage) {
                    targetPage.style.display = 'block';
                }
            }

            // --- Other JavaScript (from previous iterations) ---
            function filterByCategory(category, event) {
                document.querySelectorAll('.category-item').forEach(item => {
                    item.classList.remove('active');
                });
                if (event && event.target) {
                    event.target.closest('.category-item').classList.add('active');
                }

                const products = document.querySelectorAll('.product-card');
                products.forEach(product => {
                    const productCategory = product.getAttribute('data-category');
                    if (category === 'all' || productCategory === category) {
                        product.style.display = 'block';
                    } else {
                        product.style.display = 'none';
                    }
                });

                const featuredSection = document.querySelector('.featured-section');
                if (featuredSection) {
                    featuredSection.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            }

            // Close modal if user clicks outside
            window.onclick = function (event) {
                const authModal = document.getElementById('authModal');
                const mobileCategorySidebar = document.getElementById('mobileCategorySidebar');
                const bottomNavCategoryBtn = document.getElementById('bottomNavCategoryBtn'); // The button that opens the sidebar

                if (event.target === authModal) {
                    closeAuthModal();
                }
                // Close mobile sidebar if clicked outside, but NOT if the click originated from the button that opened it
                if (mobileCategorySidebar.classList.contains('open') && !mobileCategorySidebar.contains(event.target) && (!bottomNavCategoryBtn || !bottomNavCategoryBtn.contains(event.target))) {
                    closeMobileCategorySidebar();
                }
            }

            // --- NEW: Mobile Category Sidebar JavaScript ---
            function openMobileCategorySidebar() {
                const mobileSidebar = document.getElementById('mobileCategorySidebar');
                mobileSidebar.classList.add('open');
            }

            function closeMobileCategorySidebar() {
                const mobileSidebar = document.getElementById('mobileCategorySidebar');
                mobileSidebar.classList.remove('open');
            }

            // --- NEW: Scroll to Top for Home button ---
            function scrollToTop() {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            }


            document.addEventListener('DOMContentLoaded', function () {
                const searchBox = document.querySelector('.search-box');
                if (searchBox) {
                    searchBox.addEventListener('keyup', function (e) {
                        if (e.key === 'Enter') {
                            const searchTerm = this.value.toLowerCase();
                            const products = document.querySelectorAll('.product-card');

                            products.forEach(product => {
                                const title = product.querySelector('.product-title');
                                if (title && title.textContent.toLowerCase().includes(searchTerm)) {
                                    product.style.display = 'block';
                                } else if (title) {
                                    product.style.display = 'none';
                                }
                            });
                        }
                    });
                }
                showSlide(0);
                startAutoSlide();
                dots.forEach((dot, index) => {
                    dot.onclick = () => changeSlide(index);
                });
            });
        </script>
    </body>

    </html>


    <!-- Today's deal -->
    {{-- <div id="todays_deal" class="mb-2 mb-md-3 mt-2 mt-md-3">

    </div> --}}

    <!-- Featured Categories -->
    {{-- @if (count($featured_categories) > 0)
    <section class="mb-2 mb-md-3 mt-2 mt-md-3">
        <div class="container">
            <div class="bg-white">
                <!-- Top Section -->
                <div class="d-flex mb-2 mb-md-3 align-items-baseline justify-content-between">
                    <!-- Title -->
                    <h3 class="fs-16 fs-md-20 fw-700 mb-2 mb-sm-0">
                        <span class="">{{ translate('Featured Categories') }}</span>
                    </h3>
                    <!-- Links -->
                    <div class="d-flex">
                        <a class="text-blue fs-10 fs-md-12 fw-700 hov-text-primary animate-underline-primary"
                            href="{{ route('categories.all') }}">{{ translate('View All Categories') }}</a>
                    </div>
                </div>
            </div>

            <!-- Categories Section -->
            <div class="bg-white px-3">
                <div class="mobile-category-slider row border-top border-left border-bottom">
                    @foreach ($featured_categories->take(6) as $key => $category)
                    @php
                    $category_name = $category->getTranslation('name');
                    @endphp
                    <div class="col-xl-4 col-md-6  py-3 py-md-2rem category-slide border-right border-bottom">
                        <div class="d-sm-flex text-center text-sm-left h-100 ">
                            <div class="mb-3">
                                <img src="{{ isset($category->bannerImage->file_name) ? my_asset($category->bannerImage->file_name) : static_asset('assets/img/placeholder.jpg') }}"
                                    class="lazyload w-150px h-auto mx-auto has-transition"
                                    alt="{{ $category->getTranslation('name') }}"
                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                            </div>
                            <div class="px-2 px-lg-4 flex-grow-1">
                                <h6 class="text-dark mb-0 text-truncate-2">
                                    <a class="text-reset fw-700 fs-14 hov-text-primary"
                                        href="{{ route('products.category', $category->slug) }}"
                                        title="{{ $category_name }}">
                                        {{ $category_name }}
                                    </a>
                                </h6>
                                <div class="category-children-container">
                                    @foreach ($category->childrenCategories->take(5) as $child_category)
                                    <p class="mb-0 mt-2">
                                        <a href="{{ route('products.category', $child_category->slug) }}"
                                            class="fs-13 fw-300 text-reset hov-text-primary animate-underline-primary">
                                            {{ $child_category->getTranslation('name') }}
                                        </a>
                                    </p>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>


        </div>
    </section>
    @endif --}}

    <!-- Banner section 1 -->
    {{-- @php $homeBanner1Images = get_setting('home_banner1_images', null, $lang); @endphp
    @if ($homeBanner1Images != null)
    <div class="mb-2 mb-md-3 mt-2 mt-md-3">
        <div class="container">
            @php
            $banner_1_imags = json_decode($homeBanner1Images);
            $data_md = count($banner_1_imags) >= 2 ? 2 : 1;
            $home_banner1_links = get_setting('home_banner1_links', null, $lang);
            @endphp
            <div class="w-100 w-100 pr-3 pr-md-0">
                <div class="aiz-carousel gutters-16 overflow-hidden arrow-inactive-none arrow-dark arrow-x-15 home-banner-1"
                    data-items="{{ count($banner_1_imags) }}" data-xxl-items="{{ count($banner_1_imags) }}"
                    data-xl-items="{{ count($banner_1_imags) }}" data-lg-items="{{ $data_md }}" data-md-items="2.5"
                    data-sm-items="2.5" data-xs-items="2.5" data-arrows="true" data-dots="false">
                    @foreach ($banner_1_imags as $key => $value)
                    <div class="carousel-box overflow-hidden hov-scale-img">
                        <a href="{{ isset(json_decode($home_banner1_links, true)[$key]) ? json_decode($home_banner1_links, true)[$key] : '' }}"
                            class="d-block text-reset overflow-hidden">
                            <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                data-src="{{ uploaded_asset($value) }}" alt="{{ env('APP_NAME') }} promo"
                                class="img-fluid lazyload w-100 has-transition"
                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif --}}

    <!-- Featured Products -->
    {{-- <div id="section_featured">

    </div> --}}


    {{-- @if (addon_is_activated('preorder'))

    <!-- Preorder Banner 1 -->
    @php $homepreorder_banner_1Images = get_setting('home_preorder_banner_1_images', null, $lang); @endphp
    @if ($homepreorder_banner_1Images != null)
    <div class="mb-2 mb-md-3 mt-2 mt-md-3">
        <div class="container">
            @php
            $banner_2_imags = json_decode($homepreorder_banner_1Images);
            $data_md = count($banner_2_imags) >= 2 ? 2 : 1;
            $home_preorder_banner_1_links = get_setting('home_preorder_banner_1_links', null, $lang);
            @endphp
            <div class="aiz-carousel gutters-16 overflow-hidden arrow-inactive-none arrow-dark arrow-x-15"
                data-items="{{ count($banner_2_imags) }}" data-xxl-items="{{ count($banner_2_imags) }}"
                data-xl-items="{{ count($banner_2_imags) }}" data-lg-items="{{ $data_md }}" data-md-items="{{ $data_md }}"
                data-sm-items="1" data-xs-items="1" data-arrows="true" data-dots="false">
                @foreach ($banner_2_imags as $key => $value)
                <div class="carousel-box overflow-hidden hov-scale-img">
                    <a href="{{ isset(json_decode($home_preorder_banner_1_links, true)[$key]) ? json_decode($home_preorder_banner_1_links, true)[$key] : '' }}"
                        class="d-block text-reset overflow-hidden">
                        <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                            data-src="{{ uploaded_asset($value) }}" alt="{{ env('APP_NAME') }} promo"
                            class="img-fluid lazyload w-100 has-transition"
                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif --}}


    <!-- Featured Preorder Products -->
    {{-- <div id="section_featured_preorder_products">

    </div>
    @endif --}}


    <!-- Banner Section 2 -->
    {{-- @php $homeBanner2Images = get_setting('home_banner2_images', null, $lang); @endphp
    @if ($homeBanner2Images != null)
    <div class="mb-2 mb-md-3 mt-2 mt-md-3">
        <div class="container">
            @php
            $banner_2_imags = json_decode($homeBanner2Images);
            $data_md = count($banner_2_imags) >= 2 ? 2 : 1;
            $home_banner2_links = get_setting('home_banner2_links', null, $lang);
            @endphp
            <div class="aiz-carousel gutters-16 overflow-hidden arrow-inactive-none arrow-dark arrow-x-15"
                data-items="{{ count($banner_2_imags) }}" data-xxl-items="{{ count($banner_2_imags) }}"
                data-xl-items="{{ count($banner_2_imags) }}" data-lg-items="{{ $data_md }}" data-md-items="{{ $data_md }}"
                data-sm-items="1" data-xs-items="1" data-arrows="true" data-dots="false">
                @foreach ($banner_2_imags as $key => $value)
                <div class="carousel-box overflow-hidden hov-scale-img">
                    <a href="{{ isset(json_decode($home_banner2_links, true)[$key]) ? json_decode($home_banner2_links, true)[$key] : '' }}"
                        class="d-block text-reset overflow-hidden">
                        <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                            data-src="{{ uploaded_asset($value) }}" alt="{{ env('APP_NAME') }} promo"
                            class="img-fluid lazyload w-100 has-transition"
                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif --}}

    <!-- Best Selling  -->
    {{-- <div id="section_best_selling">

    </div> --}}

    <!-- New Products -->
    {{-- <div id="section_newest">

    </div> --}}

    <!-- Banner Section 3 -->
    {{-- @php $homeBanner3Images = get_setting('home_banner3_images', null, $lang); @endphp
    @if ($homeBanner3Images != null)
    <div class="mb-2 mb-md-3 mt-2 mt-md-3">
        <div class="container">
            @php
            $banner_3_imags = json_decode($homeBanner3Images);
            $data_md = count($banner_3_imags) >= 2 ? 2 : 1;
            $home_banner3_links = get_setting('home_banner3_links', null, $lang);
            @endphp
            <div class="w-100 pr-3 pr-md-0">
                <div class="aiz-carousel gutters-16 overflow-hidden arrow-inactive-none arrow-dark arrow-x-15 home-banner-1"
                    data-items="{{ count($banner_3_imags) }}" data-xxl-items="{{ count($banner_3_imags) }}"
                    data-xl-items="{{ count($banner_3_imags) }}" data-lg-items="{{ $data_md }}" data-md-items="2.5"
                    data-sm-items="2.5" data-xs-items="2.5" data-arrows="true" data-dots="false">
                    @foreach ($banner_3_imags as $key => $value)
                    <div class="carousel-box overflow-hidden hov-scale-img">
                        <a href="{{ isset(json_decode($home_banner3_links, true)[$key]) ? json_decode($home_banner3_links, true)[$key] : '' }}"
                            class="d-block text-reset overflow-hidden">
                            <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                data-src="{{ uploaded_asset($value) }}" alt="{{ env('APP_NAME') }} promo"
                                class="img-fluid lazyload w-100 has-transition"
                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif --}}

    <!-- Auction Product -->
    {{-- @if (addon_is_activated('auction'))
    <div id="auction_products">

    </div>
    @endif --}}

    <!-- Cupon -->
    {{-- @if (get_setting('coupon_system') == 1)
    <div class="mb-2 mb-md-3 mt-2 mt-md-3" style="background-color: {{ get_setting('cupon_background_color', '#292933') }}">
        <div class="container">
            <div class="row py-3 py-md-5">
                <div class="col-xl-8 text-xl-left">
                    <div class="row no-gutters d-lg-flex">
                        <div class="col-2 d-flex justify-content-center align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                width="109.602" height="93.34" viewBox="0 0 109.602 93.34">
                                <defs>
                                    <clipPath id="clip-pathcup">
                                        <path id="Union_10" data-name="Union 10" d="M12263,13778v-15h64v-41h12v56Z"
                                            transform="translate(-11966 -8442.865)" fill="none" stroke="#fff"
                                            stroke-width="2" />
                                    </clipPath>
                                </defs>
                                <g id="Group_24326" data-name="Group 24326" transform="translate(-274.201 -5254.611)">
                                    <g id="Mask_Group_23" data-name="Mask Group 23"
                                        transform="translate(-3652.459 1785.452) rotate(-45)"
                                        clip-path="url(#clip-pathcup)">
                                        <g id="Group_24322" data-name="Group 24322" transform="translate(207 18.136)">
                                            <g id="Subtraction_167" data-name="Subtraction 167"
                                                transform="translate(-12177 -8458)" fill="none">
                                                <path
                                                    d="M12335,13770h-56a8.009,8.009,0,0,1-8-8v-8a8,8,0,0,0,0-16v-8a8.009,8.009,0,0,1,8-8h56a8.009,8.009,0,0,1,8,8v8a8,8,0,0,0,0,16v8A8.009,8.009,0,0,1,12335,13770Z"
                                                    stroke="none" />
                                                <path
                                                    d="M 12335.0009765625 13768.0009765625 C 12338.3095703125 13768.0009765625 12341.0009765625 13765.30859375 12341.0009765625 13762 L 12341.0009765625 13755.798828125 C 12336.4423828125 13754.8701171875 12333.0009765625 13750.8291015625 12333.0009765625 13746 C 12333.0009765625 13741.171875 12336.4423828125 13737.130859375 12341.0009765625 13736.201171875 L 12341.0009765625 13729.9990234375 C 12341.0009765625 13726.6904296875 12338.3095703125 13723.9990234375 12335.0009765625 13723.9990234375 L 12278.9990234375 13723.9990234375 C 12275.6904296875 13723.9990234375 12272.9990234375 13726.6904296875 12272.9990234375 13729.9990234375 L 12272.9990234375 13736.201171875 C 12277.5576171875 13737.1298828125 12280.9990234375 13741.1708984375 12280.9990234375 13746 C 12280.9990234375 13750.828125 12277.5576171875 13754.869140625 12272.9990234375 13755.798828125 L 12272.9990234375 13762 C 12272.9990234375 13765.30859375 12275.6904296875 13768.0009765625 12278.9990234375 13768.0009765625 L 12335.0009765625 13768.0009765625 M 12335.0009765625 13770.0009765625 L 12278.9990234375 13770.0009765625 C 12274.587890625 13770.0009765625 12270.9990234375 13766.412109375 12270.9990234375 13762 L 12270.9990234375 13754 C 12275.4111328125 13753.9990234375 12278.9990234375 13750.4111328125 12278.9990234375 13746 C 12278.9990234375 13741.5888671875 12275.41015625 13738 12270.9990234375 13738 L 12270.9990234375 13729.9990234375 C 12270.9990234375 13725.587890625 12274.587890625 13721.9990234375 12278.9990234375 13721.9990234375 L 12335.0009765625 13721.9990234375 C 12339.412109375 13721.9990234375 12343.0009765625 13725.587890625 12343.0009765625 13729.9990234375 L 12343.0009765625 13738 C 12338.5888671875 13738.0009765625 12335.0009765625 13741.5888671875 12335.0009765625 13746 C 12335.0009765625 13750.4111328125 12338.58984375 13754 12343.0009765625 13754 L 12343.0009765625 13762 C 12343.0009765625 13766.412109375 12339.412109375 13770.0009765625 12335.0009765625 13770.0009765625 Z"
                                                    stroke="none" fill="#fff" />
                                            </g>
                                        </g>
                                    </g>
                                    <g id="Group_24321" data-name="Group 24321"
                                        transform="translate(-3514.477 1653.317) rotate(-45)">
                                        <g id="Subtraction_167-2" data-name="Subtraction 167"
                                            transform="translate(-12177 -8458)" fill="none">
                                            <path
                                                d="M12335,13770h-56a8.009,8.009,0,0,1-8-8v-8a8,8,0,0,0,0-16v-8a8.009,8.009,0,0,1,8-8h56a8.009,8.009,0,0,1,8,8v8a8,8,0,0,0,0,16v8A8.009,8.009,0,0,1,12335,13770Z"
                                                stroke="none" />
                                            <path
                                                d="M 12335.0009765625 13768.0009765625 C 12338.3095703125 13768.0009765625 12341.0009765625 13765.30859375 12341.0009765625 13762 L 12341.0009765625 13755.798828125 C 12336.4423828125 13754.8701171875 12333.0009765625 13750.8291015625 12333.0009765625 13746 C 12333.0009765625 13741.171875 12336.4423828125 13737.130859375 12341.0009765625 13736.201171875 L 12341.0009765625 13729.9990234375 C 12341.0009765625 13726.6904296875 12338.3095703125 13723.9990234375 12335.0009765625 13723.9990234375 L 12278.9990234375 13723.9990234375 C 12275.6904296875 13723.9990234375 12272.9990234375 13726.6904296875 12272.9990234375 13729.9990234375 L 12272.9990234375 13736.201171875 C 12277.5576171875 13737.1298828125 12280.9990234375 13741.1708984375 12280.9990234375 13746 C 12280.9990234375 13750.828125 12277.5576171875 13754.869140625 12272.9990234375 13755.798828125 L 12272.9990234375 13762 C 12272.9990234375 13765.30859375 12275.6904296875 13768.0009765625 12278.9990234375 13768.0009765625 L 12335.0009765625 13768.0009765625 M 12335.0009765625 13770.0009765625 L 12278.9990234375 13770.0009765625 C 12274.587890625 13770.0009765625 12270.9990234375 13766.412109375 12270.9990234375 13762 L 12270.9990234375 13754 C 12275.4111328125 13753.9990234375 12278.9990234375 13750.4111328125 12278.9990234375 13746 C 12278.9990234375 13741.5888671875 12275.41015625 13738 12270.9990234375 13738 L 12270.9990234375 13729.9990234375 C 12270.9990234375 13725.587890625 12274.587890625 13721.9990234375 12278.9990234375 13721.9990234375 L 12335.0009765625 13721.9990234375 C 12339.412109375 13721.9990234375 12343.0009765625 13725.587890625 12343.0009765625 13729.9990234375 L 12343.0009765625 13738 C 12338.5888671875 13738.0009765625 12335.0009765625 13741.5888671875 12335.0009765625 13746 C 12335.0009765625 13750.4111328125 12338.58984375 13754 12343.0009765625 13754 L 12343.0009765625 13762 C 12343.0009765625 13766.412109375 12339.412109375 13770.0009765625 12335.0009765625 13770.0009765625 Z"
                                                stroke="none" fill="#fff" />
                                        </g>
                                        <g id="Group_24325" data-name="Group 24325">
                                            <rect id="Rectangle_18578" data-name="Rectangle 18578" width="8" height="2"
                                                transform="translate(120 5287)" fill="#fff" />
                                            <rect id="Rectangle_18579" data-name="Rectangle 18579" width="8" height="2"
                                                transform="translate(132 5287)" fill="#fff" />
                                            <rect id="Rectangle_18581" data-name="Rectangle 18581" width="8" height="2"
                                                transform="translate(144 5287)" fill="#fff" />
                                            <rect id="Rectangle_18580" data-name="Rectangle 18580" width="8" height="2"
                                                transform="translate(108 5287)" fill="#fff" />
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </div>

                        <div class="col-10 d-flex flex-column justify-content-center text-left">
                            <div class="ml-lg-3">
                                <h5 class="fs-20 fs-md-36 fw-400 text-white mb-3">{{ translate(get_setting('cupon_title'))
                                    }}</h5>
                                <h5 class="fs-15 fs-md-20 fw-400 text-gray mb-3">{{ translate(get_setting('cupon_subtitle'))
                                    }}</h5>
                                <a href="{{ route('coupons.all') }}"
                                    class="btn text-white hov-bg-white hov-text-dark border border-width-2 fs-12 fs-md-16 px-4 d-xl-none"
                                    style="border-radius: 28px;background: rgba(255, 255, 255, 0.2);box-shadow: 0px 20px 30px rgba(0, 0, 0, 0.16);">{{
                                    translate('View All Coupons') }}</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-4 d-none d-xl-flex justify-content-end align-items-center mt-2 mt-md-4">
                    <a href="{{ route('coupons.all') }}"
                        class="btn text-white hov-bg-white hov-text-dark border border-width-2 fs-12 fs-md-16 px-4"
                        style="border-radius: 28px;background: rgba(255, 255, 255, 0.2);box-shadow: 0px 20px 30px rgba(0, 0, 0, 0.16);">{{
                        translate('View All Coupons') }}</a>
                </div>
            </div>
        </div>
    </div>
    @endif --}}

    <!-- Category wise Products/home-category -->
    {{-- <div id="section_home_categories" class="mb-2 mb-md-3 mt-2 mt-md-3">

    </div> --}}

    {{-- @if (addon_is_activated('preorder'))
    <!-- Newest Preorder Products -->
    @include('preorder.frontend.home_page.newest_preorder')
    @endif --}}

    <!-- Classified Product -->
    {{-- @if (get_setting('classified_product') == 1)
    @php
    $classified_products = get_home_page_classified_products(6);
    @endphp
    @if (count($classified_products) > 0)
    <section class="mb-2 mb-md-3 mt-3 mt-md-3">
        <div class="container">
            <!-- Top Section -->
            <div class="d-flex mb-2 mb-md-3 align-items-baseline justify-content-between">
                <!-- Title -->
                <h3 class="fs-16 fs-md-20 fw-700 mb-2 mb-sm-0">
                    <span class="">{{ translate('Classified Ads') }}</span>
                </h3>
                <!-- Links -->
                <div class="d-flex">
                    <a class="text-blue fs-10 fs-md-12 fw-700 hov-text-primary animate-underline-primary"
                        href="{{ route('customer.products') }}">{{ translate('View All Products') }}</a>
                </div>
            </div>
            <!-- Banner -->
            @php
            $classifiedBannerImage = get_setting('classified_banner_image', null, $lang);
            $classifiedBannerImageSmall = get_setting('classified_banner_image_small', null, $lang);
            @endphp
            @if ($classifiedBannerImage != null || $classifiedBannerImageSmall != null)
            <div class="mb-3 overflow-hidden hov-scale-img d-none d-md-block">
                <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                    data-src="{{ uploaded_asset($classifiedBannerImage) }}" alt="{{ env('APP_NAME') }} promo"
                    class="lazyload img-fit h-100 has-transition"
                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
            </div>
            <div class="mb-3 overflow-hidden hov-scale-img d-md-none">
                <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                    data-src="{{ $classifiedBannerImageSmall != null ? uploaded_asset($classifiedBannerImageSmall) : uploaded_asset($classifiedBannerImage) }}"
                    alt="{{ env('APP_NAME') }} promo" class="lazyload img-fit h-100 has-transition"
                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
            </div>
            @endif
            <!-- Desktop Grid -->
            <div class="bg-white d-none d-sm-block">
                <div class="row no-gutters border-top border-left">
                    @foreach ($classified_products as $key => $classified_product)
                    <div class="col-xl-4 col-md-6 border-right border-bottom has-transition hov-shadow-out z-1">
                        <div class="aiz-card-box p-2 has-transition bg-white">
                            <div class="row hov-scale-img">
                                <div class="col-4 col-md-5 mb-3 mb-md-0">
                                    <a href="{{ route('customer.product', $classified_product->slug) }}"
                                        class="d-block overflow-hidden h-auto h-md-150px text-center">
                                        <img class="img-fluid lazyload mx-auto has-transition"
                                            src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                            data-src="{{ isset($classified_product->thumbnail->file_name) ? my_asset($classified_product->thumbnail->file_name) : static_asset('assets/img/placeholder.jpg') }}"
                                            alt="{{ $classified_product->getTranslation('name') }}"
                                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                    </a>
                                </div>
                                <div class="col">
                                    <h3 class="fw-400 fs-14 text-dark text-truncate-2 lh-1-4 mb-3 h-35px d-none d-sm-block">
                                        <a href="{{ route('customer.product', $classified_product->slug) }}"
                                            class="d-block text-reset hov-text-primary">{{
                                            $classified_product->getTranslation('name') }}</a>
                                    </h3>
                                    <div class="fs-14 mb-3">
                                        <span class="text-secondary">{{ $classified_product->user ?
                                            $classified_product->user->name : '' }}</span><br>
                                        <span class="fw-700 text-primary">{{ single_price($classified_product->unit_price)
                                            }}</span>
                                    </div>
                                    @if ($classified_product->conditon == 'new')
                                    <span
                                        class="badge badge-inline badge-soft-info fs-13 fw-700 p-3 text-info border-radius-20px">
                                        {{ translate('New') }}</span>
                                    @elseif($classified_product->conditon == 'used')
                                    <span
                                        class="badge badge-inline badge-soft-danger fs-13 fw-700 p-3 text-danger border-radius-20px">{{
                                        translate('Used') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Mobile Slider -->
            <div class="bg-white d-sm-none ">
                <div class="aiz-carousel @if (count($classified_products) <= 8) arrow-inactive-none arrow-x-0 @endif"
                    data-items="1.5" data-sm-items="1.5" data-arrows="true" data-dots="false" data-dots="false"
                    data-autoplay="true" data-autoplay-speed="3000" data-rows="2">
                    @foreach ($classified_products as $key => $classified_product)
                    <div class="carousel-box classified-slider border has-transition hov-shadow-out bg-white ">
                        <div class="d-flex flex-row align-items-start p-2">
                            <!-- Image Section (Left) -->
                            <a href="{{ route('customer.product', $classified_product->slug) }}"
                                class="flex-shrink-0 w-100px">
                                <img class="img-fluid lazyload has-transition w-100"
                                    src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                    data-src="{{ isset($classified_product->thumbnail->file_name) ? my_asset($classified_product->thumbnail->file_name) : static_asset('assets/img/placeholder.jpg') }}"
                                    alt="{{ $classified_product->getTranslation('name') }}"
                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                            </a>

                            <!-- Details Section (Right) -->
                            <div class="flex-grow-1">
                                <h3 class="fw-400 fs-13 text-dark text-truncate-1 lh-1-4 mb-1">
                                    <a href="{{ route('customer.product', $classified_product->slug) }}"
                                        class="text-reset hov-text-primary">{{ $classified_product->getTranslation('name')
                                        }}</a>
                                </h3>
                                <div class="fs-12 text-muted mb-1">
                                    {{ $classified_product->user ? $classified_product->user->name : '' }}
                                </div>
                                <div class="fw-700 text-primary fs-13 mb-1">
                                    {{ single_price($classified_product->unit_price) }}
                                </div>
                                <div>
                                    @if ($classified_product->conditon == 'new')
                                    <span class="badge-sm badge-soft-info fs-11 fw-600 px-2 py-1 text-info rounded-pill">{{
                                        translate('New') }}</span>
                                    @elseif($classified_product->conditon == 'used')
                                    <span
                                        class="badge-sm badge-soft-danger fs-11 fw-600 px-2 py-1 text-danger rounded-pill">{{
                                        translate('Used') }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                    </div>
                    @endforeach
                </div>
            </div>


        </div>
    </section>
    @endif
    @endif --}}

    <!-- Top Sellers -->
    {{-- @if (get_setting('vendor_system_activation') == 1)
    @php
    $best_selers = get_best_sellers(10);
    @endphp
    @if (count($best_selers) > 0)
    <section class="mb-2 mb-md-3 mt-2 mt-md-3">
        <div class="container">
            <!-- Top Section -->
            <div class="d-flex mb-2 mb-md-3 align-items-baseline justify-content-between">
                <!-- Title -->
                <h3 class="fs-16 fs-md-20 fw-700 mb-2 mb-sm-0">
                    <span class="pb-3">{{ translate('Top Sellers') }}</span>
                </h3>
                <!-- Links -->
                <div class="d-flex">
                    <a class="text-blue fs-10 fs-md-12 fw-700 hov-text-primary animate-underline-primary"
                        href="{{ route('sellers') }}">{{ translate('View All Sellers') }}</a>
                </div>
            </div>
            <!-- Sellers Section -->
            <div class="aiz-carousel arrow-x-0 arrow-inactive-none" data-items="5" data-xxl-items="5" data-xl-items="4"
                data-lg-items="3.4" data-md-items="2.5" data-sm-items="2" data-xs-items="2.5" data-arrows="true"
                data-dots="false">
                @foreach ($best_selers as $key => $seller)
                @if ($seller->user != null)
                <div
                    class="carousel-box h-100 position-relative text-center border-right border-top border-bottom @if ($key == 0) border-left @endif has-transition hov-animate-outline">
                    <div class="position-relative px-3 pt-3 pt-md-4 pb-1 pb-md-4">
                        <!-- Shop logo & Verification Status -->
                        <div class="position-relative mx-auto size-100px size-md-120px">
                            <a href="{{ route('shop.visit', $seller->slug) }}"
                                class="d-flex mx-auto justify-content-center align-item-center size-100px size-md-120px border overflow-hidden hov-scale-img"
                                tabindex="0"
                                style="border: 1px solid #e5e5e5; border-radius: 50%; box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.06);">
                                <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                    data-src="{{ uploaded_asset($seller->logo) }}" alt="{{ $seller->name }}"
                                    class="img-fit lazyload has-transition"
                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                            </a>
                            <div class="absolute-top-right z-1 mr-md-2 mt-1 rounded-content bg-white">
                                @if ($seller->verification_status == 1)
                                <svg xmlns="http://www.w3.org/2000/svg" width="24.001" height="24" viewBox="0 0 24.001 24">
                                    <g id="Group_25929" data-name="Group 25929" transform="translate(-480 -345)">
                                        <circle id="Ellipse_637" data-name="Ellipse 637" cx="12" cy="12" r="12"
                                            transform="translate(480 345)" fill="#fff" />
                                        <g id="Group_25927" data-name="Group 25927" transform="translate(480 345)">
                                            <path id="Union_5" data-name="Union 5"
                                                d="M0,12A12,12,0,1,1,12,24,12,12,0,0,1,0,12Zm1.2,0A10.8,10.8,0,1,0,12,1.2,10.812,10.812,0,0,0,1.2,12Zm1.2,0A9.6,9.6,0,1,1,12,21.6,9.611,9.611,0,0,1,2.4,12Zm5.115-1.244a1.083,1.083,0,0,0,0,1.529l3.059,3.059a1.081,1.081,0,0,0,1.529,0l5.1-5.1a1.084,1.084,0,0,0,0-1.53,1.081,1.081,0,0,0-1.529,0L11.339,13.05,9.045,10.756a1.082,1.082,0,0,0-1.53,0Z"
                                                transform="translate(0 0)" fill="#3490f3" />
                                        </g>
                                    </g>
                                </svg>
                                @else
                                <svg xmlns="http://www.w3.org/2000/svg" width="24.001" height="24" viewBox="0 0 24.001 24">
                                    <g id="Group_25929" data-name="Group 25929" transform="translate(-480 -345)">
                                        <circle id="Ellipse_637" data-name="Ellipse 637" cx="12" cy="12" r="12"
                                            transform="translate(480 345)" fill="#fff" />
                                        <g id="Group_25927" data-name="Group 25927" transform="translate(480 345)">
                                            <path id="Union_5" data-name="Union 5"
                                                d="M0,12A12,12,0,1,1,12,24,12,12,0,0,1,0,12Zm1.2,0A10.8,10.8,0,1,0,12,1.2,10.812,10.812,0,0,0,1.2,12Zm1.2,0A9.6,9.6,0,1,1,12,21.6,9.611,9.611,0,0,1,2.4,12Zm5.115-1.244a1.083,1.083,0,0,0,0,1.529l3.059,3.059a1.081,1.081,0,0,0,1.529,0l5.1-5.1a1.084,1.084,0,0,0,0-1.53,1.081,1.081,0,0,0-1.529,0L11.339,13.05,9.045,10.756a1.082,1.082,0,0,0-1.53,0Z"
                                                transform="translate(0 0)" fill="red" />
                                        </g>
                                    </g>
                                </svg>
                                @endif
                            </div>
                        </div>
                        <!-- Shop name -->
                        <h2 class="fs-14 fw-700 text-dark text-truncate-2 h-40px mt-3 mt-md-4 mb-0 mb-md-3">
                            <a href="{{ route('shop.visit', $seller->slug) }}" class="text-reset hov-text-primary"
                                tabindex="0">{{ $seller->name }}</a>
                        </h2>
                        <!-- Shop Rating -->
                        <div class="rating rating-mr-2 text-dark mb-3">
                            {{ renderStarRating($seller->rating) }}
                            <span class="opacity-60 fs-14 d-block d-sm-inline">
                                ({{ $seller->num_of_reviews }} {{ translate('Reviews') }})
                            </span>
                        </div>

                        <!-- Desktop Button with Icon (shown on md and up) -->
                        <a href="{{ route('shop.visit', $seller->slug) }}" class="btn-visit d-none d-sm-inline-flex">
                            <span class="circle" aria-hidden="true">
                                <span class="icon arrow"></span>
                            </span>
                            <span class="button-text">{{ translate('Visit Store') }}</span>
                        </a>

                        <!-- Mobile Button without Icon (shown on sm and below) -->
                        <a href="{{ route('shop.visit', $seller->slug) }}"
                            class="btn fw-700 btn-visit-mobile fs-14 d-inline-block d-sm-none text-nowrap">
                            {{ translate('Visit Store') }}
                        </a>

                    </div>
                </div>
                @endif
                @endforeach
            </div>
        </div>
    </section>
    @endif
    @endif --}}

    <!-- Top Brands -->
   {{-- @if (get_setting('top_brands') != null)
    <section class="mb-2 mb-md-3 mt-2 mt-md-3">
        <div class="container">
            <!-- Top Section -->
            <div class="d-flex mb-2 mb-md-3 align-items-baseline justify-content-between">
                <!-- Title -->
                <h3 class="fs-16 fs-md-20 fw-700 mb-2 mb-sm-0">{{ translate('Top Brands') }}</h3>
                <!-- Links -->
                <div class="d-flex">
                    <a class="text-blue fs-10 fs-md-12 fw-700 hov-text-primary animate-underline-primary"
                        href="{{ route('brands.all') }}">{{ translate('View All Brands') }}</a>
                </div>
            </div>
            <!-- Brands Section -->
            <div class="bg-white px-sm-3 px-0">
                <!-- Desktop Grid (hidden on mobile) -->
                <div
                    class="row row-cols-xxl-6 row-cols-xl-6 row-cols-lg-4 row-cols-md-4 row-cols-3 gutters-16 border-top border-left d-none d-sm-flex">
                    @php
                    $top_brands = json_decode(get_setting('top_brands'));
                    $brands = get_brands($top_brands);
                    @endphp
                    @foreach ($brands as $brand)
                    <div class="col text-center border-right border-bottom hov-scale-img has-transition hov-shadow-out z-1">
                        <a href="{{ route('products.brand', $brand->slug) }}" class="d-block p-sm-3">
                            <img src="{{ $brand->logo != null ? uploaded_asset($brand->logo) : static_asset('assets/img/placeholder.jpg') }}"
                                class="lazyload h-100 h-md-100px mx-auto has-transition p-2 p-sm-4 mw-100"
                                alt="{{ $brand->getTranslation('name') }}"
                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                            <p class="text-center text-dark fs-12 fs-md-14 fw-700 mt-2">
                                {{ $brand->getTranslation('name') }}
                            </p>
                        </a>
                    </div>
                    @endforeach
                </div>

                <!-- Mobile Slider (hidden on desktop) -->
                <div class="d-sm-none aiz-carousel arrow-x-0 arrow-inactive-none" data-items="5" data-xxl-items="5"
                    data-xl-items="4" data-lg-items="3.4" data-md-items="2.5" data-sm-items="2" data-xs-items="2.5"
                    data-arrows="true" data-dots="false">
                    @foreach ($brands as $brand)
                    <div class="carousel-box text-center border hov-scale-img has-transition hov-shadow-out z-1">
                        <a href="{{ route('products.brand', $brand->slug) }}" class="d-block p-2 p-sm-3">
                            <img src="{{ $brand->logo != null ? uploaded_asset($brand->logo) : static_asset('assets/img/placeholder.jpg') }}"
                                class="lazyload h-100 h-md-100px mx-auto has-transition p-1 p-sm-2 mw-100"
                                alt="{{ $brand->getTranslation('name') }}"
                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                            <p class="text-center text-dark fs-12 fs-md-14 fw-700 mt-1 mt-sm-2">
                                {{ $brand->getTranslation('name') }}
                            </p>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    @endif  --}}

@endsection

@section('script')
    <script>
        // Countdown for mobile view
        function startSimpleCountdown(endDate) {
            function update() {
                const now = new Date();
                const diff = endDate - now;
                if (diff > 0) {
                    const totalSeconds = Math.floor(diff / 1000);
                    const days = Math.floor(totalSeconds / (60 * 60 * 24));
                    const hours = Math.floor((totalSeconds % (60 * 60 * 24)) / (60 * 60));
                    const mins = Math.floor((totalSeconds % (60 * 60)) / 60);
                    const secs = totalSeconds % 60;

                    document.getElementById("simple-days").textContent = days.toString().padStart(2, '0');
                    document.getElementById("simple-hours").textContent = hours.toString().padStart(2, '0');
                    document.getElementById("simple-mins").textContent = mins.toString().padStart(2, '0');
                    document.getElementById("simple-secs").textContent = secs.toString().padStart(2, '0');
                } else {
                    document.querySelector(".mobile-countdown-simple").textContent = "Sale ended";
                    clearInterval(timer);
                }
            }

            update();
            const timer = setInterval(update, 1000);
        }

        document.addEventListener("DOMContentLoaded", function () {
            const countdownEl = document.querySelector('.mobile-countdown-simple');
            if (!countdownEl) return;

            const endDateStr = countdownEl.dataset.endDate;
            if (endDateStr) {
                const parsedEndDate = new Date(endDateStr.replace(/-/g, '/'));
                startSimpleCountdown(parsedEndDate);
            }
        });
    </script>
@endsection

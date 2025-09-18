
@extends('frontend.layouts.app')

@section('content')
    {{-- NEW: Wrap all homepage specific content in this div --}}
    <div id="dynamic-content-wrapper">
        <style>
            @media (max-width: 767px) {
                #flash_deal .flash-deals-baner {
                    height: 203px !important;
                }
            }
        </style>
        @php $lang = get_system_language()->code; @endphp

        <!-- Main Container -->
        <div class="main-container" style="background-color: #F2F4F8;">
            <!-- Left Sidebar - Sticky (Desktop Only) -->
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

                {{-- NEW: Countdown added here, inside flash-head --}}
                <div class="flash-countdown" data-end-date="{{ date('Y/m/d H:i:s', $flash_deal->end_date) }}">
                    <span class="mr-1">{{ translate('Ends in:') }}</span>
                    <span id="flash-days">00</span> {{ translate('days') }}
                    <span id="flash-hours">00</span> {{ translate('hrs') }}
                    <span id="flash-mins">00</span> {{ translate('min') }}
                    <span id="flash-secs">00</span> {{ translate('sec') }}
                </div>
                {{-- NEW: End Countdown --}}

                <div class="flash-links">
                    <a class="view-all" href="{{ route('flash-deal-details', $flash_deal->slug) }}">
                        {{ translate('View All Flash Sale') }}
                    </a>
                </div>
            </div>

            <!-- layout -->
            <div class="row gutters-5">
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

                                <a href="{{ $url }}" class="fx-card product-ajax-link" title="{{ $name }}">
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

                {{-- @if ($flash_deal)
                    <section id="flash_deal" class="flash-section products-section">
                        <div class="container-full">
                          
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

                          
                            <div class="row gutters-5">
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

                                                <a href="{{ $url }}" class="fx-card product-ajax-link" title="{{ $name }}">
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
                @endif --}}
                <style>
                    .flash-head {
                        display: flex;
                        flex-wrap: wrap; /* Allow items to wrap on smaller screens */
                        align-items: center;
                        justify-content: space-between; /* Space out title, countdown, and link */
                        margin-bottom: 12px;
                        padding-bottom: 10px;
                        border-bottom: 1.5px solid #e5e7eb;
                    }

                    .flash-countdown {
                        background-color: #ff4d00; /* Example: Orange background for countdown */
                        color: #fff;
                        padding: 5px 10px;
                        border-radius: 5px;
                        font-weight: 700;
                        font-size: 14px;
                        display: flex;
                        align-items: center;
                        margin: 0 15px; /* Adjust spacing around countdown */
                        flex-shrink: 0; /* Prevent it from shrinking */
                    }

                    .flash-countdown span {
                        margin-right: 3px;
                    }

                    .flash-title {
                        /* Existing styles */
                        flex-shrink: 0; /* Prevent it from shrinking */
                    }

                    .flash-links {
                        /* Existing styles */
                        margin-left: auto; /* Push it to the far right if space allows */
                        flex-shrink: 0;
                    }

                    /* Responsive adjustments for flash-head */
                    @media (max-width: 768px) {
                        .flash-head {
                            flex-direction: column; /* Stack items vertically on very small screens */
                            align-items: flex-start;
                            gap: 10px;
                        }
                        .flash-countdown {
                            width: 100%;
                            justify-content: center;
                            margin: 0;
                        }
                        .flash-links {
                            width: 100%;
                            text-align: center;
                            margin-left: 0;
                        }
                        .flash-links .view-all {
                            width: 100%;
                            display: block;
                        }
                    }
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


<script>
    // In app.blade.php, within the <script> tags for homepage scripts
function initHomepageScripts() {
    // ... (existing AJAX calls for featured, todays_deal, etc.) ...

    // NEW: Initialize flash sale countdown if present
    const flashCountdownEl = document.querySelector('.flash-countdown');
    if (flashCountdownEl) {
        const endDateStr = flashCountdownEl.dataset.endDate;
        if (endDateStr) {
            const parsedEndDate = new Date(endDateStr.replace(/-/g, '/'));
            startFlashSaleCountdown(parsedEndDate); // Call new countdown function
        }
    }
    // ... (other existing homepage scripts like slider init) ...
}

// NEW: Modified startSimpleCountdown specifically for Flash Sale
function startFlashSaleCountdown(endDate) {
    function updateFlashCountdown() {
        const now = new Date();
        const diff = endDate - now;
        if (diff > 0) {
            const totalSeconds = Math.floor(diff / 1000);
            const days = Math.floor(totalSeconds / (60 * 60 * 24));
            const hours = Math.floor((totalSeconds % (60 * 60 * 24)) / (60 * 60));
            const mins = Math.floor((totalSeconds % (60 * 60)) / 60);
            const secs = totalSeconds % 60;

            document.getElementById("flash-days").textContent = days.toString().padStart(2, '0');
            document.getElementById("flash-hours").textContent = hours.toString().padStart(2, '0');
            document.getElementById("flash-mins").textContent = mins.toString().padStart(2, '0');
            document.getElementById("flash-secs").textContent = secs.toString().padStart(2, '0');
        } else {
            const flashCountdownEl = document.querySelector('.flash-countdown');
            if (flashCountdownEl) {
                flashCountdownEl.innerHTML = `<span>${'Sale Ended'}</span>`; // Or any "Ended" message
                flashCountdownEl.style.backgroundColor = '#cccccc'; // Grey out if ended
            }
            clearInterval(flashTimer);
        }
    }

    updateFlashCountdown();
    const flashTimer = setInterval(updateFlashCountdown, 1000);
}
</script>
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
            <a href="#" class="bottom-nav-item active" onclick="loadHomePageContent(true); return false;">
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
    </div> {{-- NEW: End of dynamic-content-wrapper --}}

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


        // ===========================================
        // NEW: AJAX for Single Product Page Loading
        // ===========================================

        let currentProductScripts = []; // To keep track of dynamically loaded product-specific scripts

        // Function to load product details via AJAX
        function loadProductDetails(url, pushState = true) {
            AIZ.loader.show(); // Show your global loader

            // Remove any previously loaded product-specific scripts
            currentProductScripts.forEach(script => script.remove());
            currentProductScripts = [];
            // Clear product-specific modals
            $('#product-modals-container').empty();

            $.ajax({
                url: url,
                type: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest' // Important for Laravel to detect AJAX
                },
                success: function(response) {
                    // Replace the content of the dynamic wrapper with product HTML
                    $('#dynamic-content-wrapper').html(response.html);
                    // Add product-specific modals to their container
                    $('#product-modals-container').html(response.modals);

                    // Execute product-specific scripts
                    const scriptElement = document.createElement('script');
                    scriptElement.type = 'text/javascript';
                    scriptElement.textContent = response.scripts;
                    document.body.appendChild(scriptElement);
                    currentProductScripts.push(scriptElement); // Store reference for later removal

                    // Update browser URL and history
                    if (pushState) {
                        history.pushState({ path: response.url }, response.meta_title, response.url);
                    }
                    document.title = response.meta_title; // Update page title

                    // Reinitialize any necessary JS for the new content (e.g., lazyload, variant price, sliders etc.)
                    // You might need to call specific functions here based on your product details page structure
                    AIZ.plugins.init(); // Assuming AIZ has a general reinitialization function for elements like lazyload
                    AIZ.extra.inputRating(); // If product reviews use AIZ ratings
                    // Call getVariantPrice() from product_scripts.blade.php if it's there
                    // Check if getVariantPrice function exists (it should be loaded by product_scripts)
                    if (typeof getVariantPrice === 'function') {
                        getVariantPrice();
                    }

                    // Scroll to top of the new content for better UX
                    $('#dynamic-content-wrapper').get(0).scrollIntoView({ behavior: 'smooth' });

                },
                error: function(xhr, status, error) {
                    console.error("Error loading product details:", error);
                    AIZ.plugins.notify('danger', '{{ translate('Could not load product details. Redirecting to full page...') }}');
                    window.location.href = url; // Fallback to full page load
                },
                complete: function() {
                    AIZ.loader.hide(); // Hide your global loader
                }
            });
        }

        // Function to load initial homepage content via AJAX (used for back button navigation)
        function loadHomePageContent(pushState = true) {
            AIZ.loader.show();

            // Clear any product-specific scripts/modals
            currentProductScripts.forEach(script => script.remove());
            currentProductScripts = [];
            $('#product-modals-container').empty();

            $.ajax({
                url: '{{ route('home') }}', // Your homepage URL
                type: 'GET',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                success: function(response) {
                    $('#dynamic-content-wrapper').html(response.html);
                    // Reinitialize homepage specific scripts if needed (like the slider)
                    // You might need to make 'showSlide' and 'startAutoSlide' global or re-define them here
                    // if they are only within the DOMContentLoaded of index.blade.php
                    if (typeof showSlide === 'function' && typeof startAutoSlide === 'function') {
                        showSlide(0); // Reinit your homepage slider
                        startAutoSlide(); // Reinit auto slide
                    } else {
                        // Fallback if functions aren't globally accessible (which they should be if defined in parent script tag)
                        console.warn("Slider functions not found, consider making them global or re-defining.");
                    }
                    AIZ.plugins.init(); // Reinitialize lazyload etc. on homepage content

                    if (pushState) {
                        history.pushState({ path: '{{ route('home') }}' }, '{{ get_setting('meta_title') }}', '{{ route('home') }}');
                    }
                    document.title = '{{ get_setting('meta_title') }}';

                    // Scroll to top
                    $('#dynamic-content-wrapper').get(0).scrollIntoView({ behavior: 'smooth' });
                },
                error: function() {
                    AIZ.plugins.notify('danger', '{{ translate('Could not load home page content. Redirecting to full page...') }}');
                    window.location.href = '{{ route('home') }}';
                },
                complete: function() {
                    AIZ.loader.hide();
                }
            });
        }

        // Event listener for product clicks
        // Using event delegation so it works for dynamically loaded products too
        $(document).on('click', '.product-ajax-link', function(e) {
            e.preventDefault(); // Prevent default link navigation
            const productUrl = $(this).attr('href');
            loadProductDetails(productUrl);
        });

        // Handle browser back/forward buttons
        window.addEventListener('popstate', function(event) {
            if (event.state && event.state.path) {
                // Check if it's a product page URL (adjust regex based on your URL structure)
                if (event.state.path.includes('/product/')) {
                    loadProductDetails(event.state.path, false); // false because we don't want to push a new state
                } else if (event.state.path === '{{ route('home') }}' || event.state.path === '{{ url('/') }}') {
                    loadHomePageContent(false);
                } else {
                    // If it's another page that we don't handle via AJAX history,
                    // let the browser handle it fully or define more specific logic.
                    window.location.reload();
                }
            } else {
                // This usually means initial page load or a state without a defined path
                // If current URL is home, and state is null, then it's effectively homepage.
                if (window.location.pathname === '/' || window.location.pathname === '{{ url('/') }}') {
                    loadHomePageContent(false);
                } else {
                     window.location.reload(); // Or load the current page content via AJAX if it's supported
                }
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize history state for the initial page load
            // This is important so popstate works correctly when user navigates back to the initial page
            history.replaceState({ path: window.location.href }, document.title, window.location.href);

            // Your existing DOMContentLoaded scripts (from original index.blade.php)
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
            // Ensure these global functions are called after the DOM is ready on initial load
            // showSlide(0); // Already called in app.blade.php if it's a global script. Avoid double call.
            // startAutoSlide(); // Already called in app.blade.php if it's a global script. Avoid double call.
            // dots.forEach((dot, index) => {
            //     dot.onclick = () => changeSlide(index);
            // });
        });
    </script>


<style>
    /* NEW: Ensure this specific style is applied to ALL product grids you want 6 columns on */
/* Adjust minmax(150px, 1fr) to control item width, e.g., for smaller items */
.products-grid {
    display: grid;
    grid-template-columns: repeat(6, 1fr); /* এখানে 6টি কলাম সেট করা হলো */
    gap: 20px; /* আপনার প্রয়োজন অনুযায়ী গ্যাপ দিন */
}

/* যদি আপনি responsive grid চান, তাহলে: */
@media (min-width: 1200px) {
    .products-grid {
        grid-template-columns: repeat(6, 1fr);
    }
}
@media (min-width: 992px) and (max-width: 1199px) {
    .products-grid {
        grid-template-columns: repeat(5, 1fr);
    }
}
@media (min-width: 768px) and (max-width: 991px) {
    .products-grid {
        grid-template-columns: repeat(4, 1fr);
    }
}
@media (max-width: 767px) {
    .products-grid {
        grid-template-columns: repeat(2, 1fr); /* মোবাইলে 2টি কলাম */
        gap: 10px; /* মোবাইলে কম গ্যাপ */
    }
}
/* আপনার existing .products-section .products-grid style (app.blade.php এ) */
.products-section .products-grid {
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); /* এটা আগের মতই রাখতে পারেন, অথবা উপরেরটা ব্যবহার করতে পারেন */
    gap: 20px;
}
</style>
@endsection
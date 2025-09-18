<!-- Main Header Container -->
<header class="@if (get_setting('header_stikcy') == 'on') sticky-top @endif z-1020 shadow-sm" >
    <!-- Changed header background to a clean white with a subtle shadow -->
    <div class="bg-white py-3" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
        <div class="container d-flex align-items-center justify-content-between">

            <!-- Left Section: Mobile Menu Toggle & Logo -->
            <div class="d-flex align-items-center">
                <!-- Mobile Menu Button (Hamburger) -->
                <button type="button" class="btn d-lg-none mr-3 mr-sm-4 p-0 active" data-toggle="class-toggle"
                    data-target=".aiz-top-menu-sidebar">
                    <svg id="Component_43_1" data-name="Component 43 – 1" xmlns="http://www.w3.org/2000/svg"
                        width="18" height="18" viewBox="0 0 16 16">
                        <rect id="Rectangle_19062" data-name="Rectangle 19062" width="16" height="2"
                            transform="translate(0 7)" fill="#333" />
                        <rect id="Rectangle_19063" data-name="Rectangle 19063" width="16" height="2"
                            fill="#333" />
                        <rect id="Rectangle_19064" data-name="Rectangle 19064" width="16" height="2"
                            transform="translate(0 14)" fill="#333" />
                    </svg>
                </button>

                <!-- Logo -->
                <a class="d-block py-0" href="{{ route('home') }}">
                    @php
                        $header_logo = get_setting('header_logo');
                    @endphp
                    @if ($header_logo != null)
                        <img src="{{ uploaded_asset($header_logo) }}" alt="{{ env('APP_NAME') }}"
                            class="mw-100 h-40px h-md-50px" height="50">
                    @else
                        <img src="{{ static_asset('assets/img/logo.png') }}" alt="{{ env('APP_NAME') }}"
                            class="mw-100 h-40px h-md-50px" height="50">
                    @endif
                </a>
                <style>
                    img.mw-100.h-40px.h-md-50px {
                                height: 55px;
                        }
                .container, .container-xxl {
                        max-width: 1500px;
                    }
                    
                    /* User Dropdown Menu - Click Only */
                     .hover-user-top-menu {
                         opacity: 0;
                         visibility: hidden;
                         transition: all 0.3s ease;
                         pointer-events: none;
                         position: absolute;
                         top: 100%;
                         right: 0;
                         z-index: 1000;
                         min-width: 220px;
                         transform: translateY(-10px);
                     }
                     
                     .user-dropdown-container {
                         position: relative;
                     }
                     
                     .hover-user-top-menu.show-dropdown {
                         opacity: 1 !important;
                         visibility: visible !important;
                         pointer-events: auto !important;
                         transform: translateY(0);
                     }
                     
                     .nav-user-info {
                         border-radius: 8px;
                         transition: all 0.2s ease;
                     }
                     
                     /* Remove hover effects - click only */
                     .nav-user-info:hover {
                         background-color: rgba(255,255,255,0.05);
                     }
                     
                     .cursor-pointer {
                         cursor: pointer;
                     }
                    
                    .aiz-user-top-menu {
                        border: 1px solid #e1e5e9 !important;
                        box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
                        border-radius: 8px !important;
                        background: #ffffff !important;
                        overflow: hidden;
                    }
                    
                    .user-top-nav-element a {
                        color: #333 !important;
                        text-decoration: none;
                        transition: all 0.2s ease;
                        padding: 12px 16px !important;
                    }
                    
                    .user-top-nav-element:hover a {
                        background-color: #f8f9fa !important;
                        color: #3498db !important;
                    }
                    
                    .user-top-nav-element:hover svg path {
                        fill: #3498db !important;
                    }
                    
                    .user-top-menu-name {
                        color: inherit !important;
                    }
                </style>
            </div>

            <div class="flex-grow-1 front-header-search d-none d-sm-flex align-items-center mx-3 mx-lg-5">
                <div class="position-relative flex-grow-1">
                    <form action="{{ route('search') }}" method="GET" class="stop-propagation">
                        <div class="d-flex position-relative align-items-center">
                            <!-- Mobile Search Back Button -->
                            <div class="d-lg-none" data-toggle="class-toggle" data-target=".front-header-search">
                                <button class="btn px-2" type="button"><i class="la la-2x la-long-arrow-left"></i></button>
                            </div>
                            <!-- Search Input -->
                            <div class="search-input-box w-100">
                                <input type="text"
                                    class="form-control fs-14 h-40px rounded-pill pl-4 pr-5 border-0 "
                                    id="search" name="keyword"
                                    @isset($query)
                                        value="{{ $query }}"
                                    @endisset
                                    placeholder="{{ translate('I am shopping for...') }}" autocomplete="off">
                                <!-- Search Icon - now a submit button -->
                                <button type="submit" class="search-icon position-absolute border-0 bg-transparent p-2" style="top: 50%; right: 8px; transform: translateY(-50%); z-index: 10;">
                                    <svg id="Group_723" data-name="Group 723" xmlns="http://www.w3.org/2000/svg"
                                        width="18" height="18" viewBox="0 0 20.001 20">
                                        <path id="Path_3090" data-name="Path 3090"
                                            d="M9.847,17.839a7.993,7.993,0,1,1,7.993-7.993A8,8,0,0,1,9.847,17.839Zm0-14.387a6.394,6.394,0,1,0,6.394,6.394A6.4,6.4,0,0,0,9.847,3.453Z"
                                            transform="translate(-1.854 -1.854)" fill="#91919b" />
                                        <path id="Path_3091" data-name="Path 3091"
                                            d="M24.4,25.2a.8.8,0,0,1-.565-.234l-6.15-6.15a.8.8,0,0,1,1.13-1.13l6.15,6.15A.8.8,0,0,1,24.4,25.2Z"
                                            transform="translate(-5.2 -5.2)" fill="#91919b" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </form>
                    <!-- Search Results Dropdown -->
                    <div class="typed-search-box stop-propagation document-click-d-none d-none bg-white rounded shadow-lg position-absolute left-0 top-100 w-100"
                        style="min-height: 200px">
                        <div class="search-preloader absolute-top-center">
                            <div class="dot-loader">
                                <div></div>
                                <div></div>
                                <div></div>
                            </div>
                        </div>
                        <div class="search-nothing d-none p-3 text-center fs-16"></div>
                        <div id="search-content" class="text-left"></div>
                    </div>
                </div>
            </div>

            <!-- Right Section: Utility Links & User Actions -->
            <div class="d-flex align-items-center ml-auto">
                <!-- Mobile Search Icon (only visible on small screens to activate search bar) -->
                <div class="d-sm-none mr-2">
                    <a class="p-2 d-block text-reset" href="javascript:void(0);" data-toggle="class-toggle"
                        data-target=".front-header-search">
                        <i class="las la-search la-flip-horizontal la-2x"></i>
                    </a>
                </div>

                @if (Auth::check() && auth()->user()->user_type == 'customer')
                    <!-- Compare -->
                    <div class="d-none d-lg-block mr-3">
                        <div id="compare">
                            @include('frontend.partials.compare')
                        </div>
                    </div>

                    <!-- Wishlist -->
                    <div class="d-none d-lg-block mr-3">
                        <div id="wishlist">
                            @include('frontend.partials.wishlist')
                        </div>
                    </div>

                    <!-- Notifications -->
                    <ul class="list-inline mb-0 h-100 d-none d-xl-flex justify-content-end align-items-center">
                        <li class="list-inline-item mr-3 pr-3 pl-0 dropdown">
                            <a class="dropdown-toggle no-arrow text-secondary fs-12" data-toggle="dropdown"
                                href="javascript:void(0);" role="button" aria-haspopup="false"
                                aria-expanded="false" onclick="nonLinkableNotificationRead()">
                                <span class="position-relative d-inline-block">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18"
                                        viewBox="0 0 14.668 16">
                                        <path id="_26._Notification" data-name="26. Notification"
                                            d="M8.333,16A3.34,3.34,0,0,0,11,14.667H5.666A3.34,3.34,0,0,0,8.333,16ZM15.06,9.78a2.457,2.457,0,0,1-.727-1.747V6a6,6,0,1,0-12,0V8.033A2.457,2.457,0,0,1,1.606,9.78,2.083,2.083,0,0,0,3.08,13.333H13.586A2.083,2.083,0,0,0,15.06,9.78Z"
                                            transform="translate(-0.999)" fill="#91919b" />
                                    </svg>
                                    @if (Auth::check() && count($user->unreadNotifications) > 0)
                                        <span class="badge badge-primary badge-inline badge-pill absolute-top-right--10px unread-notification-count">{{ count($user->unreadNotifications) }}</span>
                                    @endif
                                </span>
                            </a>
                            @auth
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg py-0 rounded-0">
                                    <div class="p-3 bg-light border-bottom">
                                        <h6 class="mb-0">{{ translate('Notifications') }}</h6>
                                    </div>
                                    <div class="c-scrollbar-light overflow-auto" style="max-height:300px;">
                                        <ul class="list-group list-group-flush">
                                            @forelse($user->unreadNotifications as $notification)
                                                @php
                                                    $showNotification = true;
                                                    if (($notification->type == 'App\Notifications\PreorderNotification') && !addon_is_activated('preorder'))
                                                    {
                                                        $showNotification = false;
                                                    }
                                                @endphp
                                                @if($showNotification)
                                                    @php
                                                        $isLinkable = true;
                                                        $notificationType = get_notification_type($notification->notification_type_id, 'id');
                                                        $notifyContent = $notificationType->getTranslation('default_text');
                                                        $notificationShowDesign = get_setting('notification_show_type');
                                                        if($notification->type == 'App\Notifications\customNotification' && $notification->data['link'] == null){
                                                            $isLinkable = false;
                                                        }
                                                    @endphp
                                                    <li class="list-group-item">
                                                        <div class="d-flex">
                                                            @if($notificationShowDesign != 'only_text')
                                                                <div class="size-35px mr-2">
                                                                    @php
                                                                        $notifyImageDesign = '';
                                                                        if($notificationShowDesign == 'design_2'){
                                                                            $notifyImageDesign = 'rounded-1';
                                                                        }
                                                                        elseif($notificationShowDesign == 'design_3'){
                                                                            $notifyImageDesign = 'rounded-circle';
                                                                        }
                                                                    @endphp
                                                                    <img
                                                                        src="{{ uploaded_asset($notificationType->image) }}"
                                                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/notification.png') }}';"
                                                                        class="img-fit h-100 {{ $notifyImageDesign }}" >
                                                                </div>
                                                            @endif
                                                            <div>
                                                                @if ($notification->type == 'App\Notifications\OrderNotification')
                                                                    @php
                                                                        $orderCode  = $notification->data['order_code'];
                                                                        $route = route('purchase_history.details', encrypt($notification->data['order_id']));
                                                                            $orderCode = "<span class='text-blue'>".$orderCode."</span>";
                                                                        $notifyContent = str_replace('[[order_code]]', $orderCode, $notifyContent);
                                                                    @endphp
                                                                @elseif($notification->type == 'App\Notifications\PreorderNotification')
                                                                    @php
                                                                        $orderCode  = $notification->data['order_code'];
                                                                        $route = route('preorder.order_details', encrypt($notification->data['preorder_id']));
                                                                            $orderCode = "<span class='text-blue'>".$orderCode."</span>";
                                                                        $notifyContent = str_replace('[[order_code]]', $orderCode, $notifyContent);
                                                                    @endphp
                                                                @endif

                                                                @if($isLinkable = true)
                                                                    <a href="{{ route('notification.read-and-redirect', encrypt($notification->id)) }}">
                                                                @endif
                                                                    <span class="fs-12 text-dark text-truncate-2">{!! $notifyContent !!}</span>
                                                                @if($isLinkable = true)
                                                                    </a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endif
                                            @empty
                                                <li class="list-group-item">
                                                    <div class="py-4 text-center fs-16">
                                                        {{ translate('No notification found') }}
                                                    </div>
                                                </li>
                                            @endforelse
                                        </ul>
                                    </div>
                                    <div class="text-center border-top">
                                        <a href="{{ route('customer.all-notifications') }}"
                                            class="text-secondary fs-12 d-block py-2">
                                            {{ translate('View All Notifications') }}
                                        </a>
                                    </div>
                                </div>
                            @endauth
                        </li>
                    </ul>
                @endif

                <!-- Cart Icon -->
                <div class="d-none d-xl-block has-transition" data-hover="dropdown">
                    <div class="nav-cart-box dropdown h-100" id="cart_items">
                        @include('frontend.partials.cart.cart')
                    </div>
                </div>
                
                <!-- User Account/Auth Links - Show for all users -->
                <div class="ml-3 d-none d-xl-flex align-items-center position-relative user-dropdown-container">
                    @auth
                        <span class="d-flex align-items-center nav-user-info py-20px px-3 cursor-pointer" id="nav-user-info" onclick="toggleUserDropdown()">
                            <span class="size-40px rounded-circle overflow-hidden border border-transparent nav-user-img">
                                @if (auth()->user()->avatar_original != null)
                                    <img src="{{ uploaded_asset(auth()->user()->avatar_original) }}" class="img-fit h-100" alt="{{ translate('avatar') }}"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">                                @else
                                    <img src="{{ static_asset('assets/img/avatar-place.png') }}" class="image"
                                        alt="{{ translate('avatar') }}"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">                                @endif
                            </span>
                            <div class="ml-2">
                                @if(auth()->user()->name && auth()->user()->name != auth()->user()->email)
                                    <h4 class="h5 fs-14 fw-700 text-white mb-0">{{ auth()->user()->name }}</h4>
                                    <small class="fs-12 text-white-50">{{ auth()->user()->email }}</small>
                                @else
                                    <h4 class="h5 fs-14 fw-700 text-white mb-0">{{ auth()->user()->email }}</h4>
                                @endif
                            </div>
                        </span>
                    @else
                        <span class="d-flex align-items-center nav-user-info">
                            <span class="size-40px rounded-circle overflow-hidden border d-flex align-items-center justify-content-center nav-user-img bg-light">
                                <svg xmlns="http://www.w3.org/2000/svg" width="19.902" height="20.012"
                                    viewBox="0 0 19.902 20.012">
                                    <path id="fe2df171891038b33e9624c27e96e367"
                                        d="M15.71,12.71a6,6,0,1,0-7.42,0,10,10,0,0,0-6.22,8.18,1.006,1.006,0,1,0,2,.22,8,8,0,0,1,15.9,0,1,1,0,0,0,1,.89h.11a1,1,0,0,0,.88-1.1,10,10,0,0,0-6.25-8.19ZM12,12a4,4,0,1,1,4-4A4,4,0,0,1,12,12Z"
                                        transform="translate(-2.064 -1.995)" fill="#91919b" />
                                </svg>
                            </span>
                            <a href="javascript:void(0)" onclick="openUserLogin()"
                                class="text-reset hov-text-primary fs-14 d-inline-block  border-soft-light border-width-2 pr-2 ml-3" style="color: #FFFFFF!important;">{{ translate('Login') }}</a>
                        </span>
                    @endauth
                </div>
            </div>
        </div>
    </div>

    <!-- User Dropdown Menu on Click - For authenticated users -->
    @if (Auth::check())
        <div class="hover-user-top-menu position-absolute top-100 left-0 right-0 z-3">
            <div class="container">
                <div class="position-static float-right">
                    <div class="aiz-user-top-menu bg-white rounded-0 border-top shadow-sm" style="width:220px;">
                        <ul class="list-unstyled no-scrollbar mb-0 text-left">
                            <li class="user-top-nav-element border border-top-0" data-id="1">
                                @php
                                    $dashboardRoute = 'dashboard'; // Default customer dashboard
                                    if(auth()->user()->user_type == 'admin') {
                                        $dashboardRoute = 'admin.dashboard';
                                    } elseif(auth()->user()->user_type == 'seller') {
                                        $dashboardRoute = 'seller.dashboard';
                                    }
                                @endphp
                                <a href="{{ route($dashboardRoute) }}"
                                    class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        viewBox="0 0 16 16">
                                        <path id="Path_2916" data-name="Path 2916"
                                            d="M15.3,5.4,9.561.481A2,2,0,0,0,8.26,0H7.74a2,2,0,0,0-1.3.481L.7,5.4A2,2,0,0,0,0,6.92V14a2,2,0,0,0,2,2H14a2,2,0,0,0,2-2V6.92A2,2,0,0,0,15.3,5.4M10,15H6V9A1,1,0,0,1,7,8H9a1,1,0,0,1,1,1Zm5-1a1,1,0,0,1-1,1H11V9A2,2,0,0,0,9,7H7A2,2,0,0,0,5,9v6H2a1,1,0,0,1-1-1V6.92a1,1,0,0,1,.349-.76l5.74-4.92A1,1,0,0,1,7.74,1h.52a1,1,0,0,1,.651.24l5.74,4.92A1,1,0,0,1,15,6.92Z"
                                            fill="#b5b5c0" />
                                    </svg>
                                    <span
                                        class="user-top-menu-name has-transition ml-3">{{ translate('Dashboard') }}</span>
                                </a>
                            </li>

                            <li class="user-top-nav-element border border-top-0" data-id="1">
                                <a href="{{ route('purchase_history.index') }}"
                                    class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                        viewBox="0 0 16 16">
                                        <g id="Group_25261" data-name="Group 25261"
                                            transform="translate(-27.466 -542.963)">
                                            <path id="Path_2953" data-name="Path 2953"
                                                d="M14.5,5.963h-4a1.5,1.5,0,0,0,0,3h4a1.5,1.5,0,0,0,0-3m0,2h-4a.5.5,0,0,1,0-1h4a.5.5,0,0,1,0,1"
                                                transform="translate(22.966 537)" fill="#b5b5bf" />
                                            <path id="Path_2954" data-name="Path 2954"
                                                d="M12.991,8.963a.5.5,0,0,1,0-1H13.5a2.5,2.5,0,0,1,2.5,2.5v10a2.5,2.5,0,0,1-2.5,2.5H2.5a2.5,2.5,0,0,1-2.5-2.5v-10a2.5,2.5,0,0,1,2.5-2.5h.509a.5.5,0,0,1,0,1H2.5a1.5,1.5,0,0,0-1.5,1.5v10a1.5,1.5,0,0,0,1.5,1.5h11a1.5,1.5,0,0,0,1.5-1.5v-10a1.5,1.5,0,0,0-1.5-1.5Z"
                                                transform="translate(27.466 536)" fill="#b5b5bf" />
                                            <path id="Path_2955" data-name="Path 2955"
                                                d="M7.5,15.963h1a.5.5,0,0,1,.5.5v1a.5.5,0,0,1-.5.5h-1a.5.5,0,0,1-.5-.5v-1a.5.5,0,0,1,.5-.5"
                                                transform="translate(23.966 532)" fill="#b5b5bf" />
                                            <path id="Path_2956" data-name="Path 2956"
                                                d="M7.5,21.963h1a.5.5,0,0,1,.5.5v1a.5.5,0,0,1-.5.5h-1a.5.5,0,0,1-.5-.5v-1a.5.5,0,0,1,.5-.5"
                                                transform="translate(23.966 529)" fill="#b5b5bf" />
                                            <path id="Path_2957" data-name="Path 2957"
                                                d="M7.5,27.963h1a.5.5,0,0,1,.5.5v1a.5.5,0,0,1-.5.5h-1a.5.5,0,0,1-.5-.5v-1a.5.5,0,0,1-.5-.5"
                                                transform="translate(23.966 526)" fill="#b5b5bf" />
                                            <path id="Path_2958" data-name="Path 2958"
                                                d="M13.5,16.963h5a.5.5,0,0,1,0,1h-5a.5.5,0,0,1,0-1"
                                                transform="translate(20.966 531.5)" fill="#b5b5bf" />
                                            <path id="Path_2959" data-name="Path 2959"
                                                d="M13.5,22.963h5a.5.5,0,0,1,0,1h-5a.5.5,0,0,1,0-1"
                                                transform="translate(20.966 528.5)" fill="#b5b5bf" />
                                            <path id="Path_2960" data-name="Path 2960"
                                                d="M13.5,28.963h5a.5.5,0,0,1,0,1h-5a.5.5,0,0,1,0-1"
                                                transform="translate(20.966 525.5)" fill="#b5b5bf" />
                                        </g>
                                    </svg>
                                    <span
                                        class="user-top-menu-name has-transition ml-3">{{ translate('Purchase History') }}</span>
                                </a>
                            </li>

                            @if (addon_is_activated('preorder'))
                                <li class="user-top-nav-element border border-top-0" data-id="1">
                                    <a href="{{ route('preorder.order_list') }}"
                                        class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16.002" viewBox="0 0 16 16.002">
                                            <path id="Union_63" data-name="Union 63" d="M14072,894a8,8,0,1,1,8,8A8.011,8.011,0,0,1,14072,894Zm1,0a7,7,0,1,0,7-7A7.007,7.007,0,0,0,14073,894Zm10.652,3.674-3.2-2.781a1,1,0,0,1-.953-1.756V889.5a.5.5,0,1,1,1,0v3.634a1,1,0,0,1,.5.863c0,.015,0,.029,0,.044l3.311,2.876a.5.5,0,0,1,.05.7.5.5,0,0,1-.708.049Z" transform="translate(-14072 -885.998)" fill="#b5b5bf"/>
                                        </svg>
                                        <span
                                            class="user-top-menu-name has-transition ml-3">{{ translate('Preorder List') }}</span>
                                    </a>
                                </li>
                            @endif

                            <li class="user-top-nav-element border border-top-0" data-id="1">
                                <a href="{{ route('digital_purchase_history.index') }}"
                                    class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16.001" height="16"
                                        viewBox="0 0 16.001 16">
                                        <g id="Group_25262" data-name="Group 25262"
                                            transform="translate(-1388.154 -562.604)">
                                            <path id="Path_2963" data-name="Path 2963"
                                                d="M77.864,98.69V92.1a.5.5,0,1,0-1,0V98.69l-1.437-1.437a.5.5,0,0,0-.707.707l1.851,1.852a1,1,0,0,0,.707.293h.172a1,1,0,0,0,.707-.293l1.851-1.852a.5.5,0,0,0-.7-.713Z"
                                                transform="translate(1318.79 478.5)" fill="#b5b5bf" />
                                            <path id="Path_2964" data-name="Path 2964"
                                                d="M67.155,88.6a3,3,0,0,1-.474-5.963q-.009-.089-.015-.179a5.5,5.5,0,0,1,10.977-.718,3.5,3.5,0,0,1-.989,6.859h-1.5a.5.5,0,0,1,0-1l1.5,0a2.5,2.5,0,0,0,.417-4.967.5.5,0,0,1-.417-.5,4.5,4.5,0,1,0-8.908.866.512.512,0,0,1,.009.121.5.5,0,0,1-.52.479,2,2,0,1,0-.162,4l.081,0h2a.5.5,0,0,1,0,1Z"
                                                transform="translate(1324 486)" fill="#b5b5bf" />
                                        </g>
                                    </svg>
                                    <span
                                        class="user-top-menu-name has-transition ml-3">{{ translate('Downloads') }}</span>
                                </a>
                            </li>
                            @if (get_setting('conversation_system') == 1)
                                <li class="user-top-nav-element border border-top-0" data-id="1">
                                    <a href="{{ route('conversations.index') }}"
                                        class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            viewBox="0 0 16 16">
                                            <g id="Group_25263" data-name="Group 25263"
                                                transform="translate(1053.151 256.688)">
                                                <path id="Path_3012" data-name="Path 3012"
                                                    d="M134.849,88.312h-8a2,2,0,0,0-2,2v5a2,2,0,0,0,2,2v3l2.4-3h5.6a2,2,0,0,0,2-2v-5a2,2,0,0,0-2-2m1,7a1,1,0,0,1-1,1h-8a1,1,0,0,1-1-1v-5a1,1,0,0,1,1-1h8a1,1,0,0,1,1,1Z"
                                                    transform="translate(-1178 -341)" fill="#b5b5bf" />
                                                <path id="Path_3013" data-name="Path 3013"
                                                    d="M134.849,81.312h8a1,1,0,0,1,1,1v5a1,1,0,0,1-1,1h-.5a.5.5,0,0,0,0,1h.5a2,2,0,0,0,2-2v-5a2,2,0,0,0-2-2h-8a2,2,0,0,0-2,2v.5a.5.5,0,0,0,1,0v-.5a1,1,0,0,1,1-1"
                                                    transform="translate(-1182 -337)" fill="#b5b5bf" />
                                                <path id="Path_3014" data-name="Path 3014"
                                                    d="M131.349,93.312h5a.5.5,0,0,1,0,1h-5a.5.5,0,0,1,0-1"
                                                    transform="translate(-1181 -343.5)" fill="#b5b5bf" />
                                                <path id="Path_3015" data-name="Path 3015"
                                                    d="M131.349,99.312h5a.5.5,0,1,1,0,1h-5a.5.5,0,1,1,0-1"
                                                    transform="translate(-1181 -346.5)" fill="#b5b5bf" />
                                            </g>
                                        </svg>
                                        <span
                                            class="user-top-menu-name has-transition ml-3">{{ translate('Conversations') }}</span>
                                    </a>
                                </li>
                            @endif

                            @if (get_setting('wallet_system') == 1)
                                <li class="user-top-nav-element border border-top-0" data-id="1">
                                    <a href="{{ route('wallet.index') }}"
                                        class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" width="16"
                                            height="16" viewBox="0 0 16 16">
                                            <defs>
                                                <clipPath id="clip-path1">
                                                    <rect id="Rectangle_1386" data-name="Rectangle 1386"
                                                        width="16" height="16" fill="#b5b5bf" />
                                                </clipPath>
                                            </defs>
                                            <g id="Group_8102" data-name="Group 8102"
                                                clip-path="url(#clip-path1)">
                                                <path id="Path_2936" data-name="Path 2936"
                                                    d="M13.5,4H13V2.5A2.5,2.5,0,0,0,10.5,0h-8A2.5,2.5,0,0,0,0,2.5v11A2.5,2.5,0,0,0,2.5,16h11A2.5,2.5,0,0,0,16,13.5v-7A2.5,2.5,0,0,0,13.5,4M2.5,1h8A1.5,1.5,0,0,1,12,2.5V4H2.5a1.5,1.5,0,0,1,0-3M15,11H10a1,1,0,0,1,0-2h5Zm0-3H10a2,2,0,0,0,0,4h5v1.5A1.5,1.5,0,0,1,13.5,15H2.5A1.5,1.5,0,0,1,1,13.5v-9A2.5,2.5,0,0,0,2.5,5h11A1.5,1.5,0,0,1,15,6.5Z"
                                                    fill="#b5b5bf" />
                                            </g>
                                        </svg>
                                        <span
                                            class="user-top-menu-name has-transition ml-3">{{ translate('My Wallet') }}</span>
                                    </a>
                                </li>
                            @endif
                            <li class="user-top-nav-element border border-top-0" data-id="1">
                                <a href="{{ route('support_ticket.index') }}"
                                    class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16.001"
                                        viewBox="0 0 16 16.001">
                                        <g id="Group_25259" data-name="Group 25259"
                                            transform="translate(-316 -1066)">
                                            <path id="Subtraction_184" data-name="Subtraction 184"
                                                d="M16427.109,902H16420a8.015,8.015,0,1,1,8-8,8.278,8.278,0,0,1-1.422,4.535l1.244,2.132a.81.81,0,0,1,0,.891A.791.791,0,0,1,16427.109,902ZM16420,887a7,7,0,1,0,0,14h6.283c.275,0,.414,0,.549-.111s-.209-.574-.34-.748l0,0-.018-.022-1.064-1.6A6.829,6.829,0,0,0,16427,894a6.964,6.964,0,0,0-7-7Z"
                                                transform="translate(-16096 180)" fill="#b5b5bf" />
                                            <path id="Union_12" data-name="Union 12"
                                                d="M16414,895a1,1,0,1,1,1,1A1,1,0,0,1,16414,895Zm.5-2.5V891h.5a2,2,0,1,0-2-2h-1a3,3,0,1,1,3.5,2.958v.54a.5.5,0,1,1-1,0Zm-2.5-3.5h1a.5.5,0,1,1-1,0Z"
                                                transform="translate(-16090.998 183.001)" fill="#b5b5bf" />
                                        </g>
                                    </svg>
                                    <span
                                        class="user-top-menu-name has-transition ml-3">{{ translate('Support Ticket') }}</span>
                                </a>
                            </li>
                            <li class="user-top-nav-element border border-top-0" data-id="1">
                                <a href="{{ route('logout') }}"
                                    class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="15.999"
                                        viewBox="0 0 16 15.999">
                                        <g id="Group_25503" data-name="Group 25503"
                                            transform="translate(-24.002 -377)">
                                            <g id="Group_25265" data-name="Group 25265"
                                                transform="translate(-216.534 -160)">
                                                <path id="Subtraction_192" data-name="Subtraction 192"
                                                    d="M12052.535,2920a8,8,0,0,1-4.569-14.567l.721.72a7,7,0,1,0,7.7,0l.721-.72a8,8,0,0,1-4.567,14.567Z"
                                                    transform="translate(-11803.999 -2367)" fill="#d43533" />
                                            </g>
                                            <rect id="Rectangle_19022" data-name="Rectangle 19022" width="1"
                                                height="8" rx="0.5" transform="translate(31.5 377)"
                                                fill="#d43533" />
                                        </g>
                                    </svg>
                                    <span
                                        class="user-top-menu-name text-primary has-transition ml-3">{{ translate('Logout') }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif
</header>

<!-- Mobile Off-canvas Sidebar -->
<div class="aiz-top-menu-sidebar collapse-sidebar-wrap sidebar-xl sidebar-left d-lg-none z-1035">
    <div class="overlay overlay-fixed dark c-pointer" data-toggle="class-toggle"
        data-target=".aiz-top-menu-sidebar" data-same=".hide-top-menu-bar"></div>
    <div class="collapse-sidebar c-scrollbar-light text-left">
        <button type="button" class="btn btn-sm p-4 hide-top-menu-bar" data-toggle="class-toggle"
            data-target=".aiz-top-menu-sidebar">
            <i class="las la-times la-2x text-primary"></i>
        </button>
        <!-- User Info / Auth Links for Mobile Sidebar - For all users -->
        <div class="py-3 px-4 bg-light mb-3">
            @auth
                <span class="d-flex align-items-center nav-user-info">
                    <span class="size-40px rounded-circle overflow-hidden border border-transparent nav-user-img">
                        @if (auth()->user()->avatar_original != null)
                            <img src="{{ uploaded_asset(auth()->user()->avatar_original) }}" class="img-fit h-100" alt="{{ translate('avatar') }}"
                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">                        @else
                            <img src="{{ static_asset('assets/img/avatar-place.png') }}" class="image" alt="{{ translate('avatar') }}"
                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">                        @endif
                    </span>
                    <div class="ml-2">
                        @if(auth()->user()->name && auth()->user()->name != auth()->user()->email)
                            <h4 class="h5 fs-14 fw-700 text-dark mb-0">{{ auth()->user()->name }}</h4>
                            <small class="fs-12 text-muted">{{ auth()->user()->email }}</small>
                        @else
                            <h4 class="h5 fs-14 fw-700 text-dark mb-0">{{ auth()->user()->email }}</h4>
                        @endif
                    </div>
                </span>
                @else
                    <span class="d-flex align-items-center nav-user-info">
                        <span class="size-40px rounded-circle overflow-hidden border d-flex align-items-center justify-content-center nav-user-img bg-white">
                            <svg xmlns="http://www.w3.org/2000/svg" width="19.902" height="20.012"
                                viewBox="0 0 19.902 20.012">
                                <path id="fe2df171891038b33e9624c27e96e367"
                                    d="M15.71,12.71a6,6,0,1,0-7.42,0,10,10,0,0,0-6.22,8.18,1.006,1.006,0,1,0,2,.22,8,8,0,0,1,15.9,0,1,1,0,0,0,1,.89h.11a1,1,0,0,0,.88-1.1,10,10,0,0,0-6.25-8.19ZM12,12a4,4,0,1,1,4-4A4,4,0,0,1,12,12Z"
                                    transform="translate(-2.064 -1.995)" fill="#91919b" />
                            </svg>
                        </span>
                        <a href="javascript:void(0)" onclick="openUserLogin()"
                            class="text-reset opacity-60 hov-opacity-100 hov-text-primary fs-12 d-inline-block border-right border-soft-light border-width-2 pr-2 ml-3">{{ translate('Login') }}</a>
                        <a href="{{ route(get_setting('customer_registration_verify') === '1' ? 'registration.verification' : 'user.registration') }}"
                            class="text-reset opacity-60 hov-opacity-100 hov-text-primary fs-12 d-inline-block py-2 pl-2">{{ translate('Registration') }}</a>

                        @if (get_setting('show_language_switcher') == 'on')
                            <div class="dropdown ml-5" id="lang-change-mobile">
                                <a href="javascript:void(0)" class="text-secondary p-2" data-toggle="dropdown">
                                    <img src="{{ static_asset('assets/img/flags/' . $system_language->code . '.png') }}" 
                                         alt="{{ $system_language->name }}" height="16">
                                </a>
                                <ul class="dropdown-menu dropdown-menu-left">
                                    @foreach (get_all_active_language() as $key => $language)
                                        <li>
                                            <a href="javascript:void(0)" data-flag="{{ $language->code }}"
                                                class="dropdown-item @if ($system_language->code == $language->code) active @endif">
                                                <img src="{{ static_asset('assets/img/flags/' . $language->code . '.png') }}"
                                                    class="mr-1" alt="{{ $language->name }}" height="11">
                                                <span class="language">{{ $language->name }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (get_setting('show_currency_switcher') == 'on')
                            <div class="dropdown mr-2" id="currency-change-mobile">
                                @php
                                    $system_currency = get_system_currency();
                                @endphp
                                <a href="javascript:void(0)" class="text-secondary p-2" data-toggle="dropdown">
                                    {{ $system_currency->symbol }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-left">
                                    @foreach (get_all_active_currency() as $key => $currency)
                                        <li>
                                            <a class="dropdown-item @if ($system_currency->code == $currency->code) active @endif"
                                                href="javascript:void(0)"
                                                data-currency="{{ $currency->code }}">
                                                {{ $currency->symbol }} ({{ $currency->code }})
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </span>
                @endauth
        </div>
        <hr>
        <ul class="mb-0 pl-3 pb-3 h-100">
            @if (get_setting('header_menu_labels') != null)
                @foreach (json_decode(get_setting('header_menu_labels'), true) as $key => $value)
                    <li class="mr-0">
                        <a href="{{ json_decode(get_setting('header_menu_links'), true)[$key] }}"
                            class="fs-13 px-3 py-3 w-100 d-inline-block fw-700 text-dark header_menu_links
                        @if (url()->current() == json_decode(get_setting('header_menu_links'), true)[$key]) active @endif">
                            {{ translate($value) }}
                        </a>
                    </li>
                @endforeach
            @endif
            @if (Auth::check())
                <hr>
                <li class="mr-0">
                    @php
                        $dashboardRoute = 'dashboard'; // Default customer dashboard
                        $accountLabel = 'My Account';
                        if(auth()->user()->user_type == 'admin') {
                            $dashboardRoute = 'admin.dashboard';
                            $accountLabel = 'Admin Dashboard';
                        } elseif(auth()->user()->user_type == 'seller') {
                            $dashboardRoute = 'seller.dashboard';
                            $accountLabel = 'Seller Dashboard';
                        }
                    @endphp
                    <a href="{{ route($dashboardRoute) }}"
                        class="fs-13 px-3 py-3 w-100 d-inline-block fw-700 text-dark header_menu_links
                        {{ areActiveRoutes([$dashboardRoute], ' active') }}">
                        {{ translate($accountLabel) }}
                    </a>
                </li>
                @if(auth()->user()->user_type == 'customer')
                    <li class="mr-0">
                        <a href="{{ route('customer.all-notifications') }}"
                            class="fs-13 px-3 py-3 w-100 d-inline-block fw-700 text-dark header_menu_links
                            {{ areActiveRoutes(['customer.all-notifications'], ' active') }}">
                            {{ translate('Notifications') }}
                        </a>
                    </li>
                    <li class="mr-0">
                        <a href="{{ route('wishlists.index') }}"
                            class="fs-13 px-3 py-3 w-100 d-inline-block fw-700 text-dark header_menu_links
                            {{ areActiveRoutes(['wishlists.index'], ' active') }}">
                            {{ translate('Wishlist') }}
                        </a>
                    </li>
                    <li class="mr-0">
                        <a href="{{ route('compare') }}"
                            class="fs-13 px-3 py-3 w-100 d-inline-block fw-700 text-dark header_menu_links
                            {{ areActiveRoutes(['compare'], ' active') }}">
                            {{ translate('Compare') }}
                        </a>
                    </li>
                @endif
                <hr>
                <li class="mr-0">
                    <a href="{{ route('logout') }}"
                        class="fs-13 px-3 py-3 w-100 d-inline-block fw-700 text-primary header_menu_links">
                        {{ translate('Logout') }}
                    </a>
                </li>
            @endif
        </ul>
        <br>
        <br>
    </div>
</div>

<div class="modal fade" id="order_details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div id="order-details-modal-body">

            </div>
        </div>
    </div>
</div>

<!-- Include Auth Modals -->
@include('auth.modals')

@section('script')
    <script type="text/javascript">
        function show_order_details(order_id) {
            $('#order-details-modal-body').html(null);

            if (!$('#modal-size').hasClass('modal-lg')) {
                $('#modal-size').addClass('modal-lg');
            }

            $.post('{{ route('orders.details') }}', {
                _token: AIZ.data.csrf,
                order_id: order_id
            }, function(data) {
                $('#order-details-modal-body').html(data);
                $('#order_details').modal();
                $('.c-preloader').hide();
                AIZ.plugins.bootstrapSelect('refresh');
            });
        }

        // Close dropdown when clicking outside
        $(document).ready(function() {
            $(document).on('click', function(event) {
                if (!$(event.target).closest('.user-dropdown-container').length) {
                    $('.hover-user-top-menu').removeClass('show-dropdown');
                }
            });
            
            // Prevent dropdown from closing when clicking inside it
            $('.hover-user-top-menu').on('click', function(event) {
                event.stopPropagation();
            });
        });
    </script>
@endsection
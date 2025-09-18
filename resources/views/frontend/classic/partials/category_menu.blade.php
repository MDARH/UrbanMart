{{-- <div class="aiz-category-menu bg-white rounded-0 border-top" id="category-sidebar" style="width:270px;">
    <ul class="list-unstyled categories no-scrollbar mb-0 text-left">
        @foreach (get_level_zero_categories()->take(10) as $key => $category)
            @php
                $category_name = $category->getTranslation('name');
            @endphp
            <li class="category-nav-element border border-top-0" data-id="{{ $category->id }}">
                <a href="{{ route('products.category', $category->slug) }}"
                    class="text-truncate text-dark px-4 fs-14 d-block hov-column-gap-1">
                    <img class="cat-image lazyload mr-2 opacity-60" src="{{ static_asset('assets/img/placeholder.jpg') }}"
                        data-src="{{ isset($category->catIcon->file_name) ? my_asset($category->catIcon->file_name) : static_asset('assets/img/placeholder.jpg') }}" width="16" alt="{{ $category_name }}"
                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                    <span class="cat-name has-transition">{{ $category_name }}</span>
                </a>
                
                <div class="sub-cat-menu c-scrollbar-light border p-4 shadow-none">
                    <div class="c-preloader text-center absolute-center">
                        <i class="las la-spinner la-spin la-3x opacity-70"></i>
                    </div>
                </div>

            </li>
        @endforeach
    </ul>
</div> --}}
{{-- {{ route('products.category', $category->slug) }} --}}

<aside class="left-sidebar">
    <h3 class="sidebar-title" style="font-size: 20px;margin-top:-12px;color:var(--skybuy-blue);">Categories</h3>
    <div class="category-grid">
        @foreach (get_level_zero_categories()->take(20) as $key => $category)
            @php
                $category_name = $category->getTranslation('name');
                $category_icon_src = isset($category->catIcon->file_name) ? my_asset($category->catIcon->file_name) : static_asset('assets/img/placeholder.jpg');
            @endphp
            <a href="{{ route('products.category', $category->slug) }}" 
               class="category-item" 
               onclick="filterByCategory('{{ $category->slug }}', event)"> 
                <div class="category-icon">
                    <img class="cat-image lazyload" 
                         src="{{ static_asset('assets/img/placeholder.jpg') }}" 
                         data-src="{{ $category_icon_src }}" 
                         width="24" 
                         alt="{{ $category_name }}"
                         onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                </div>
                <div class="category-name">{{ $category_name }}</div>
                
            
            </a>
        @endforeach
    </div>
</aside>

<style>
    .cat-image{
        width: 56px;
    }
    .left-sidebar {
            width: 320px;
            background: white;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 80px;
            height: calc(100vh - 80px);
            padding: 20px;
            overflow-y: auto; 
            -webkit-overflow-scrolling: touch;
            -ms-overflow-style: none; 
            scrollbar-width: none; 
            }

        .left-sidebar::-webkit-scrollbar {
            display: none; 
            width: 0; 
            height: 0;
        }

        .sidebar-title {
            font-size: 18px;
            font-weight: bold;
            color: var(--skybuy-blue);
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #e0e0e0;
            text-align: center;
        }

        .category-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .category-item {
            display: flex;
            flex-direction: column;
            text-decoration: none;
            align-items: center;
            padding: 10px 10px;
            cursor: pointer;
            transition: all 0.3s;
            color: #333;
            border-radius: 12px;
            background: #f9f9f9;
        }

        .category-item:hover {
            background: #e8f4f8;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .category-icon {
            font-size: 24px;
            margin-bottom: 8px;
            width: 60px; 
            height: 60px; 
            display: flex; 
            align-items: center;
            justify-content: center;
        }

        .category-name {
            font-size: 13px;
            text-align: center;
            font-weight: 600;
        }
</style>


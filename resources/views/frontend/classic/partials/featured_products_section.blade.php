{{-- @if (count(get_featured_products()) > 0)
    <section class="mb-2 mb-md-3 mt-2 mt-md-3">
        <div class="container">
            <!-- Top Section -->
            <div class="d-flex mb-2 mb-md-3 align-items-baseline justify-content-between">
                <!-- Title -->
                <h3 class="fs-16 fs-md-20 fw-700 mb-2 mb-sm-0">
                    <span class="">{{ translate('Featured Products') }}</span>
                </h3>
                <!-- Links -->
                <div class="d-flex">
                    <a type="button" class="arrow-prev slide-arrow link-disable text-secondary mr-2" onclick="clickToSlide('slick-prev','section_featured')"><i class="las la-angle-left fs-20 fw-600"></i></a>
                    <a type="button" class="arrow-next slide-arrow text-secondary ml-2" onclick="clickToSlide('slick-next','section_featured')"><i class="las la-angle-right fs-20 fw-600"></i></a>
                </div>
            </div>
            <!-- Products Section -->
            <div class="px-sm-3 ">
                <div class="aiz-carousel sm-gutters-16 arrow-none" data-items="6" data-xl-items="5" data-lg-items="4"  data-md-items="3" data-sm-items="2.5" data-xs-items="2.5" data-arrows='true' data-infinite='false'>
                    @foreach (get_featured_products() as $key => $product)
                    <div class="carousel-box position-relative px-0 has-transition hov-animate-outline border-right border-top border-bottom @if($key == 0) border-left @endif">
                        <div class="px-3">
                            @include('frontend.'.get_setting('homepage_select').'.partials.product_box_1',['product' => $product])
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>   
@endif --}}


@if (count(get_featured_products()) > 0)
<section class="products-section section_featured">
  <div class="container-full">
    <div class="section-header">
      <h2 class="section-title">{{ translate('Featured Products') }}</h2>
      <a href="{{ route('search') }}?featured=1" class="view-more-btn">{{ translate('View More') }}</a>
    </div>

    <div class="products-grid">
      @foreach (get_featured_products() as $product)
        @php
          $name = $product->getTranslation('name');
          $url  = route('product', $product->slug);
          $thumb = uploaded_asset($product->thumbnail_img) ?: static_asset('assets/img/placeholder.jpg');

          // If your project exposes helpers like home_discounted_base_price()
          $current = home_discounted_base_price($product);   // formatted string
          $original = home_base_price($product);             // formatted string

          // Derive percent off if both prices are numeric
          $discPct = null;
          try {
            $c = (float) preg_replace('/[^\d.]/', '', $current);
            $o = (float) preg_replace('/[^\d.]/', '', $original);
            if ($o > 0 && $c < $o) $discPct = round((($o - $c) / $o) * 100);
          } catch (\Throwable $e) {}
        @endphp

        <a href="{{ $url }}" class="product-card" title="{{ $name }}">
          <div class="product-image">
            <img
              src="{{ static_asset('assets/img/placeholder.jpg') }}"
              data-src="{{ $thumb }}"
              class="lazyload"
              alt="{{ $name }}">
            @if(!is_null($discPct) && $discPct > 0)
              <div class="discount-badge">{{ $discPct }}% {{ translate('Off') }}</div>
            @endif
          </div>

          <div class="product-name">{{ $name }}</div>

          <div class="product-price">
            <span class="current-price">{{ $current }}</span>
            @if($current !== $original)
              <span class="original-price">{{ $original }}</span>
            @endif
          </div>

          <div class="product-meta">
            <span>{{ translate('SOLD') }}: {{ (int)($product->num_of_sale ?? 0) }}</span>
          </div>
        </a>
      @endforeach
    </div>
  </div>
</section>
@endif

<style>
    .products-section{ background:#fff; border-radius:15px; padding:30px; margin-top:20px; box-shadow:0 4px 15px rgba(0,0,0,.1); }
.section-header{ display:flex; justify-content:space-between; align-items:center; margin-bottom:24px; padding-bottom:12px; border-bottom:2px solid #e5e7eb; }
.section-title{ font-size:22px; font-weight:800; color:var(--skybuy-blue); margin:0; }
.view-more-btn{ background:var(--skybuy-blue); color:#fff; padding:10px 16px; border-radius:8px; text-decoration:none; }
.view-more-btn:hover{ background:#1a4a54; }

/* responsive auto grid */
.products-grid{
  display:grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));  /* responsive tracks */  /* [19][9] */
  gap:20px;
}

/* card */
.product-card{ background:#f9f9f9; border-radius:12px; color:inherit; text-decoration:none; transition:transform .2s, box-shadow .2s; overflow:hidden; display:block; }
.product-card:hover{ transform:translateY(-4px); box-shadow:0 10px 25px rgba(0,0,0,.12); }

/* image box */
.product-image{ position:relative; width:100%; height:210px; background:#f2f2f2; overflow:hidden; }
.product-image img{ width:100%; height:100%; object-fit:cover; }  /* no distortion */   /* [12] */
.discount-badge{ position:absolute; top:10px; right:10px; background:#ff4757; color:#fff; padding:4px 8px; border-radius:14px; font-size:12px; font-weight:700; }

/* text */
.product-name{
  font-size:14px; font-weight:600; line-height:1.35; margin:10px 0 8px; padding:0 12px;
  display:-webkit-box; -webkit-box-orient:vertical; -webkit-line-clamp:2; overflow:hidden; /* clamp 2 lines */  /* [16] */
}
.product-price{ display:flex; align-items:center; gap:8px; padding:0 12px 8px; }
.current-price{ color:#e74c3c; font-weight:800; }
.original-price{ color:#95a5a6; text-decoration:line-through; }
.product-meta{ display:flex; justify-content:space-between; color:#64748b; font-size:12px; padding:0 12px 14px 12px; }

</style>
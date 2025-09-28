
<div class="professional-details-container">
    <!-- Professional Navigation Tabs -->
    <div class="professional-nav-tabs">
        <button class="professional-tab-btn active" data-tab="specifications">
            <i class="las la-list-alt"></i>
            {{ translate('Specifications') }}
        </button>
        <button class="professional-tab-btn" data-tab="description">
            <i class="las la-file-alt"></i>
            {{ translate('Description') }}
        </button>
        @if ($detailedProduct->video_link != null)
            <button class="professional-tab-btn" data-tab="video">
                <i class="las la-play-circle"></i>
                {{ translate('Video') }}
            </button>
        @endif
        @if ($detailedProduct->pdf != null)
            <button class="professional-tab-btn" data-tab="downloads">
                <i class="las la-download"></i>
                {{ translate('Downloads') }}
            </button>
        @endif
        <button class="professional-tab-btn" data-tab="reviews">
            <i class="las la-star"></i>
            {{ translate('Original Images') }}
        </button>
    </div>

    <!-- Professional Tab Content -->
    <div class="professional-tab-content">
        <!-- Specifications Tab -->
        <div class="professional-tab-pane active" id="specifications-tab">
            <div class="professional-specifications-table">
                <div class="specifications-header">
                    <h3>{{ translate('Product Specifications') }}</h3>
                    <p>{{ translate('Detailed technical specifications and features') }}</p>
                </div>
                
                <div class="specifications-grid">
                    <div class="spec-row">
                        <div class="spec-label">{{ translate('Material Function') }}</div>
                        <div class="spec-value">{{ $detailedProduct->material_function ?? 'Anti Tear' }}</div>
                    </div>
                    
                    <div class="spec-row">
                        <div class="spec-label">{{ translate('Brand Category') }}</div>
                        <div class="spec-value">
                            @if($detailedProduct->brand)
                                {{ $detailedProduct->brand->getTranslation('name') }}
                            @else
                                {{ translate('In Stock') }}
                            @endif
                        </div>
                    </div>
                    
                    <div class="spec-row">
                        <div class="spec-label">{{ translate('Brand') }}</div>
                        <div class="spec-value">
                            @if($detailedProduct->brand)
                                {{ $detailedProduct->brand->getTranslation('name') }}
                            @else
                                {{ translate('AirPing') }}
                            @endif
                        </div>
                    </div>
                    
                    <div class="spec-row">
                        <div class="spec-label">{{ translate('Product Category') }}</div>
                        <div class="spec-value">
                            @if($detailedProduct->category)
                                {{ $detailedProduct->category->getTranslation('name') }}
                            @else
                                {{ translate('Bag') }}
                            @endif
                        </div>
                    </div>
                    
                    <div class="spec-row">
                        <div class="spec-label">{{ translate('Item Number') }}</div>
                        <div class="spec-value">{{ $detailedProduct->sku ?? '123456' }}</div>
                    </div>
                    
                    <div class="spec-row">
                        <div class="spec-label">{{ translate('SKU') }}</div>
                        <div class="spec-value">{{ $detailedProduct->sku ?? 'SKU-' . $detailedProduct->id }}</div>
                    </div>
                    
                    <div class="spec-row">
                        <div class="spec-label">{{ translate('Technical Category') }}</div>
                        <div class="spec-value">{{ translate('Automobile Car Seat New (NB)') }}</div>
                    </div>
                 
                    <div class="spec-row">
                        <div class="spec-label">{{ translate('Product Of Year') }}</div>
                        <div class="spec-value">{{ $detailedProduct->created_at ? $detailedProduct->created_at->format('Y') : date('Y') }}</div>
                    </div>
                    
                    <div class="spec-row">
                        <div class="spec-label">{{ translate('Thickness') }}</div>
                        <div class="spec-value">{{ $detailedProduct->thickness ?? 'Normal' }}</div>
                    </div>
                    
                    <div class="spec-row">
                        <div class="spec-label">{{ translate('Pattern') }}</div>
                        <div class="spec-value">{{ $detailedProduct->pattern ?? 'Solid Color' }}</div>
                    </div>
                    
                    <div class="spec-row">
                        <div class="spec-label">{{ translate('Pocket') }}</div>
                        <div class="spec-value">{{ $detailedProduct->pocket ?? 'Zipper' }}</div>
                    </div>
                    
                    <div class="spec-row">
                        <div class="spec-label">{{ translate('Closure Of Bag') }}</div>
                        <div class="spec-value">{{ $detailedProduct->closure ?? 'Zip Closure' }}</div>
                    </div>
                    
                    <div class="spec-row">
                        <div class="spec-label">{{ translate('Strap Range') }}</div>
                        <div class="spec-value">{{ $detailedProduct->strap_range ?? '1 Inch' }}</div>
                    </div>
                    
                    <div class="spec-row">
                        <div class="spec-label">{{ translate('Suitable For The Season') }}</div>
                        <div class="spec-value">{{ $detailedProduct->season ?? 'All Season' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Description Tab -->
        <div class="professional-tab-pane" id="description-tab">
            <div class="professional-description-content">
                <div class="description-header">
                    <h3>{{ translate('Product Description') }}</h3>
                    <p>{{ translate('Comprehensive product information and details') }}</p>
                </div>
                <div class="description-body">
                    <div class="aiz-editor-data">
                        {!! $detailedProduct->getTranslation('description') !!}
                    </div>
                </div>
            </div>
        </div>

        <!-- Video Tab -->
        @if ($detailedProduct->video_link != null)
            <div class="professional-tab-pane" id="video-tab">
                <div class="professional-video-content">
                    <div class="video-header">
                        <h3>{{ translate('Product Video') }}</h3>
                        <p>{{ translate('Watch our product demonstration video') }}</p>
                    </div>
                    <div class="video-container">
                        <div class="embed-responsive embed-responsive-16by9">
                            @if ($detailedProduct->video_provider == 'youtube' && isset(explode('=', $detailedProduct->video_link)[1]))
                                <iframe class="embed-responsive-item"
                                    src="https://www.youtube.com/embed/{{ get_url_params($detailedProduct->video_link, 'v') }}"></iframe>
                            @elseif ($detailedProduct->video_provider == 'dailymotion' && isset(explode('video/', $detailedProduct->video_link)[1]))
                                <iframe class="embed-responsive-item"
                                    src="https://www.dailymotion.com/embed/video/{{ explode('video/', $detailedProduct->video_link)[1] }}"></iframe>
                            @elseif ($detailedProduct->video_provider == 'vimeo' && isset(explode('vimeo.com/', $detailedProduct->video_link)[1]))
                                <iframe
                                    src="https://player.vimeo.com/video/{{ explode('vimeo.com/', $detailedProduct->video_link)[1] }}"
                                    width="500" height="281" frameborder="0" webkitallowfullscreen
                                    mozallowfullscreen allowfullscreen></iframe>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif
        
        <!-- Downloads Tab -->
        @if ($detailedProduct->pdf != null)
            <div class="professional-tab-pane" id="downloads-tab">
                <div class="professional-downloads-content">
                    <div class="downloads-header">
                        <h3>{{ translate('Downloads') }}</h3>
                        <p>{{ translate('Download product documentation and resources') }}</p>
                    </div>
                    <div class="downloads-body">
                        <div class="download-item">
                            <div class="download-icon">
                                <i class="las la-file-pdf"></i>
                            </div>
                            <div class="download-info">
                                <h4>{{ translate('Product Manual') }}</h4>
                                <p>{{ translate('Complete product documentation') }}</p>
                            </div>
                            <a href="{{ uploaded_asset($detailedProduct->pdf) }}" 
                               class="professional-download-btn">
                                <i class="las la-download"></i>
                                {{ translate('Download') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Reviews/Original Images Tab -->
        <div class="professional-tab-pane" id="reviews-tab">
            <div class="professional-reviews-content">
                <div class="reviews-header">
                    <h3>{{ translate('Original Images') }}</h3>
                    <p>{{ translate('High-quality product images and gallery') }}</p>
                </div>
                <div class="original-images-gallery">
                    {{-- @if($detailedProduct->photos && count($detailedProduct->photos) > 0)
                        <div class="images-grid">
                            @foreach($detailedProduct->photos as $photo)
                                <div class="image-item">
                                    <img src="{{ uploaded_asset($photo) }}" 
                                         alt="{{ $detailedProduct->getTranslation('name') }}"
                                         class="original-image">
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="no-images">
                            <i class="las la-image"></i>
                            <p>{{ translate('No additional images available') }}</p>
                        </div>
                    @endif --}}
                </div>
            </div>
        </div>
    </div>
</div>
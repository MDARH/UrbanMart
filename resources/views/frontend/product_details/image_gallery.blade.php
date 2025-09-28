<div class="sticky-top z-3 row gutters-10">
    @php
        // Completely safe photo handling
        $photos = [];
        
        try {
            if (isset($detailedProduct->photos) && $detailedProduct->photos !== null) {
                if (is_array($detailedProduct->photos)) {
                    $photos = $detailedProduct->photos;
                } elseif (is_string($detailedProduct->photos) && !empty($detailedProduct->photos)) {
                    // Handle both comma-separated and single photo strings
                    if (strpos($detailedProduct->photos, ',') !== false) {
                        $photos = explode(',', $detailedProduct->photos);
                    } else {
                        $photos = [$detailedProduct->photos];
                    }
                }
            }
            
            // Clean up array - remove empty, null, or invalid entries
            $photos = array_filter($photos, function($photo) {
                return !empty($photo) && is_string($photo);
            });
            
            // Re-index array to avoid gaps
            $photos = array_values($photos);
            
        } catch (Exception $e) {
            // Log error and set empty array as fallback
            \Log::error('Product photos error: ' . $e->getMessage());
            $photos = [];
        }
        
        // Get stock images safely
        $stockImages = [];
        try {
            if ($detailedProduct->digital == 0 && isset($detailedProduct->stocks)) {
                foreach ($detailedProduct->stocks as $stock) {
                    if (isset($stock->image) && !empty($stock->image)) {
                        $stockImages[] = $stock;
                    }
                }
            }
        } catch (Exception $e) {
            \Log::error('Stock images error: ' . $e->getMessage());
            $stockImages = [];
        }
    @endphp
    
    <!-- Gallery Images -->
    <div class="col-12">
        <div class="modern-product-gallery">
            <div class="main-image-container">
                @php $totalImages = count($photos) + count($stockImages); @endphp
                
                @if ($totalImages > 0)
                    <div class="aiz-carousel product-gallery arrow-inactive-transparent arrow-lg-none"
                        data-nav-for='.product-gallery-thumb' 
                        data-fade='true' 
                        data-auto-height='true' 
                        data-arrows='true'>
                        
                        <!-- Stock Images First -->
                        @if (!empty($stockImages))
                            @foreach ($stockImages as $index => $stock)
                                @php
                                    // Mohammad Hassan
                                    // Extract color from variant string to match with color selection
                                    $stockColor = '';
                                    if (isset($stock->variant)) {
                                        // Try to match variant with available colors
                                        if ($detailedProduct->colors != null && count(json_decode($detailedProduct->colors)) > 0) {
                                            foreach (json_decode($detailedProduct->colors) as $color_value) {
                                                $color_name = get_single_color_name($color_value);
                                                if (stripos($stock->variant, $color_name) !== false) { // Match color name in variant string
                                                    $stockColor = $color_value; // Store hex color value
                                                    break;
                                                }
                                            }
                                        }
                                    }
                                @endphp
                                <div class="carousel-box img-zoom rounded-0 gallery-image" 
                                     data-color="{{ $stockColor }}" 
                                     data-variant="{{ $stock->variant ?? '' }}">
                                    <div class="image-wrapper">
                                        <img class="img-fluid h-auto lazyload mx-auto main-product-image"
                                            src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                            data-src="{{ uploaded_asset($stock->image) }}"
                                            alt="{{ $detailedProduct->getTranslation('name') }} - {{ $stock->variant ?? 'Variant' }}"
                                            data-zoom="{{ uploaded_asset($stock->image) }}"
                                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                        
                                        <!-- Image overlay info -->
                                        @if (isset($stock->variant))
                                            <div class="image-overlay">
                                                <span class="variant-label">{{ $stock->variant }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @endif
                        
                        <!-- Regular Product Photos -->
                        @if (!empty($photos))
                            @php
                                // Mohammad Hassan
                                // Assign first available color to regular photos for color synchronization
                                $defaultColor = '';
                                if ($detailedProduct->colors != null && count(json_decode($detailedProduct->colors)) > 0) {
                                    $defaultColor = json_decode($detailedProduct->colors)[0];
                                }
                            @endphp
                            @foreach ($photos as $index => $photo)
                                <div class="carousel-box img-zoom rounded-0 gallery-image" 
                                     data-color="{{ $defaultColor }}" 
                                     data-variant="general">
                                    <div class="image-wrapper">
                                        <img class="img-fluid h-auto lazyload mx-auto main-product-image"
                                            src="{{ static_asset('assets/img/placeholder.jpg') }}" 
                                            data-src="{{ uploaded_asset($photo) }}"
                                            alt="{{ $detailedProduct->getTranslation('name') }} - Image {{ $index + 1 }}"
                                            data-zoom="{{ uploaded_asset($photo) }}"
                                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    
                    <!-- Image Counter -->
                    <div class="image-counter">
                        <span class="current-image">1</span> / <span class="total-images">{{ $totalImages }}</span>
                    </div>
                    
                @else
                    <!-- No Images Available -->
                    <div class="no-image-container">
                        <img class="img-fluid h-auto mx-auto"
                            src="{{ static_asset('assets/img/placeholder.jpg') }}"
                            alt="{{ $detailedProduct->getTranslation('name') }}">
                        <p class="text-muted text-center mt-2">{{ translate('No images available') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Thumbnail Images -->
    <div class="col-12 mt-3 d-none d-lg-block">
        @if ($totalImages > 1)
            <div class="thumbnail-gallery-container">
                <div class="aiz-carousel half-outside-arrow product-gallery-thumb" 
                     data-items='{{ min($totalImages, 7) }}' 
                     data-nav-for='.product-gallery'
                     data-focus-select='true' 
                     data-arrows='{{ $totalImages > 7 ? "true" : "false" }}' 
                     data-vertical='false' 
                     data-auto-height='true'>

                    <!-- Stock Thumbnails -->
                    @if (!empty($stockImages))
                        @foreach ($stockImages as $index => $stock)
                            @php
                                // Mohammad Hassan
                                // Extract color from variant string to match with color selection
                                $thumbColor = '';
                                if (isset($stock->variant)) {
                                    // Try to match variant with available colors
                                    if ($detailedProduct->colors != null && count(json_decode($detailedProduct->colors)) > 0) {
                                        foreach (json_decode($detailedProduct->colors) as $color_value) {
                                            $color_name = get_single_color_name($color_value);
                                            if (stripos($stock->variant, $color_name) !== false) { // Match color name in variant string
                                                $thumbColor = $color_value; // Store hex color value
                                                break;
                                            }
                                        }
                                    }
                                }
                            @endphp
                            <div class="carousel-box c-pointer rounded-0 thumbnail-item gallery-thumbnail" 
                                 data-variation="{{ $stock->variant ?? '' }}"
                                 data-color="{{ $thumbColor }}" {{-- This holds the hex color for sync --}}
                                 data-index="{{ $index }}"
                                 title="{{ $stock->variant ?? 'Variant ' . ($index + 1) }}">
                                <div class="thumbnail-wrapper">
                                    <img class="lazyload mw-100 size-60px mx-auto border p-1 thumbnail-image"
                                        src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                        data-src="{{ uploaded_asset($stock->image) }}"
                                        alt="Thumbnail {{ $index + 1 }}"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                    
                                    @if (isset($stock->variant))
                                        <div class="thumbnail-label">{{ Str::limit($stock->variant, 10) }}</div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @endif

                    <!-- Regular Photo Thumbnails -->
                    @if (!empty($photos))
                        @php
                            // Mohammad Hassan
                            // Use the same default color for thumbnails as main images
                            $thumbnailDefaultColor = '';
                            if ($detailedProduct->colors != null && count(json_decode($detailedProduct->colors)) > 0) {
                                $thumbnailDefaultColor = json_decode($detailedProduct->colors)[0];
                            }
                        @endphp
                        @foreach ($photos as $index => $photo)
                            <div class="carousel-box c-pointer rounded-0 thumbnail-item gallery-thumbnail" 
                                 data-index="{{ count($stockImages) + $index }}"
                                 data-color="{{ $thumbnailDefaultColor }}" {{-- This holds the hex color for sync --}}
                                 data-variant="general"
                                 title="Image {{ $index + 1 }}">
                                <div class="thumbnail-wrapper">
                                    <img class="lazyload mw-100 size-60px mx-auto border p-1 thumbnail-image"
                                        src="{{ static_asset('assets/img/placeholder.jpg') }}" 
                                        data-src="{{ uploaded_asset($photo) }}"
                                        alt="Thumbnail {{ $index + 1 }}"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>

<style>
/* Enhanced Gallery Styles */
.modern-product-gallery {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.main-image-container {
    position: relative;
}

.image-wrapper {
    position: relative;
    overflow: hidden;
    border-radius: 8px;
}

.main-product-image {
    transition: transform 0.3s ease;
    cursor: zoom-in;
}

.main-product-image:hover {
    transform: scale(1.05);
}

.image-overlay {
    position: absolute;
    bottom: 10px;
    left: 10px;
    background: rgba(0, 0, 0, 0.7);
    color: white;
    padding: 5px 10px;
    border-radius: 4px;
    font-size: 12px;
}

.image-counter {
    position: absolute;
    top: 15px;
    right: 15px;
    background: rgba(0, 0, 0, 0.7);
    color: white;
    padding: 5px 10px;
    border-radius: 15px;
    font-size: 12px;
    font-weight: 600;
}

.no-image-container {
    padding: 40px 20px;
    text-align: center;
    background: #f8f9fa;
}

.thumbnail-gallery-container {
    background: white;
    padding: 15px;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

.thumbnail-item {
    transition: transform 0.3s ease;
}

.thumbnail-item:hover {
    transform: translateY(-3px);
}

.thumbnail-wrapper {
    position: relative;
}

.thumbnail-image {
    border-radius: 6px;
    transition: all 0.3s ease;
}

.thumbnail-item:hover .thumbnail-image {
    border-color: #3498db !important;
}

.thumbnail-label {
    position: absolute;
    bottom: -15px;
    left: 50%;
    transform: translateX(-50%);
    font-size: 10px;
    color: #666;
    white-space: nowrap;
}

/* Slick carousel active state */
.slick-current .thumbnail-image {
    border-color: #3498db !important;
    box-shadow: 0 0 10px rgba(52, 152, 219, 0.3);
}

/* Error handling styles */
.image-error {
    background: #f8f9fa;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 300px;
    color: #999;
    flex-direction: column;
    border-radius: 8px;
}

.image-error i {
    font-size: 48px;
    margin-bottom: 10px;
}
</style>

<script>
$(document).ready(function() {
    // Mohammad Hassan
    // Enhanced color-based image filtering functionality
    function filterImagesByColor(selectedColor) {
        console.log('Filtering images by color:', selectedColor);
        
        const galleryImages = $('.product-gallery .carousel-box.gallery-image');
        const galleryThumbnails = $('.product-gallery-thumb .carousel-box.gallery-thumbnail');
        let firstMatchingIndex = -1;
        
        // Hide all images and thumbnails initially
        galleryImages.hide();
        galleryThumbnails.hide();

        if (!selectedColor || selectedColor === '') {
            // If no color selected (or default), show all images/thumbnails
            galleryImages.show();
            galleryThumbnails.show();
            firstMatchingIndex = 0; // Select the very first image
        } else {
            // Show images that match the selected color
            galleryImages.each(function(index) {
                const imageColor = $(this).data('color');
                if (imageColor === selectedColor) {
                    $(this).show();
                    if (firstMatchingIndex === -1) {
                        firstMatchingIndex = index;
                    }
                }
            });

            // If no images specifically tagged with the selected color, show general images
            if (firstMatchingIndex === -1) {
                galleryImages.each(function(index) {
                    const imageVariant = $(this).data('variant'); // Assuming 'general' for images not tied to a specific color variant
                    if (imageVariant === 'general') {
                        $(this).show();
                        if (firstMatchingIndex === -1) { // Set first general image as the target
                            firstMatchingIndex = index;
                        }
                    }
                });
            }

            // Show thumbnails that match the selected color
            galleryThumbnails.each(function(index) {
                const thumbColor = $(this).data('color');
                if (thumbColor === selectedColor) {
                    $(this).show();
                }
            });

            // If no thumbnails specifically tagged with the selected color, show general thumbnails
            // This is important to ensure users can still navigate if a color has no specific thumbnail.
            galleryThumbnails.each(function() {
                const thumbVariant = $(this).data('variant');
                if (thumbVariant === 'general' && $(this).css('display') === 'none') {
                    $(this).show();
                }
            });
        }
        
        // Refresh carousel and navigate to first matching image
        setTimeout(function() {
            $('.product-gallery').slick('refresh');
            $('.product-gallery-thumb').slick('refresh');
            
            // Navigate to first matching image if found
            if (firstMatchingIndex >= 0) {
                $('.product-gallery').slick('slickGoTo', firstMatchingIndex);
            } else {
                // If no images are left after filtering (unlikely but safe fallback), go to first slide.
                $('.product-gallery').slick('slickGoTo', 0);
            }
        }, 100);
    }
    
    // Mohammad Hassan
    // Listen for color selection changes from details.blade.php
    $(document).on('colorChanged', function(event, colorValue) {
        filterImagesByColor(colorValue);
    });
    
    // Mohammad Hassan
    // Enhanced thumbnail click functionality using event delegation - ROBUST SOLUTION
    $(document).on('click', '.gallery-thumbnail', function() {
        const index = $(this).data('index');
        const clickedThumbnailColorHex = $(this).data('color'); // This is the hex color value from the thumbnail
        const variant = $(this).data('variation'); // Get variant information
        
        console.log('Thumbnail clicked - Index:', index, 'Color:', clickedThumbnailColorHex, 'Variant:', variant);
        
        // Go to specific main image slide
        $('.product-gallery').slick('slickGoTo', index);
        
        // Synchronize color selection in the details section if selectColor function exists
        if (typeof selectColor === 'function') {
            let matchingColorOption = null;
            
            // Method 1: Try to match by exact hex color value
            if (clickedThumbnailColorHex && clickedThumbnailColorHex !== '') {
                matchingColorOption = $('#color-options .color-option[data-color-value="' + clickedThumbnailColorHex + '"]');
                console.log('Method 1 - Exact hex match for:', clickedThumbnailColorHex, 'Found:', matchingColorOption.length > 0);
            }
            
            // Method 2: If no exact match, try to match by color name in variant
            if ((!matchingColorOption || matchingColorOption.length === 0) && variant) {
                $('#color-options .color-option').each(function() {
                    const colorName = $(this).data('color');
                    if (colorName && variant.toLowerCase().includes(colorName.toLowerCase())) {
                        matchingColorOption = $(this);
                        console.log('Method 2 - Color name match:', colorName, 'in variant:', variant);
                        return false; // Break the loop
                    }
                });
            }
            
            // Method 3: If still no match, try case-insensitive hex comparison
            if ((!matchingColorOption || matchingColorOption.length === 0) && clickedThumbnailColorHex) {
                $('#color-options .color-option').each(function() {
                    const optionColorValue = $(this).data('color-value');
                    if (optionColorValue && optionColorValue.toLowerCase() === clickedThumbnailColorHex.toLowerCase()) {
                        matchingColorOption = $(this);
                        console.log('Method 3 - Case-insensitive hex match:', optionColorValue);
                        return false; // Break the loop
                    }
                });
            }
            
            // If we found a matching color option, select it
            if (matchingColorOption && matchingColorOption.length > 0) {
                const colorName = matchingColorOption.data('color');
                const colorValue = matchingColorOption.data('color-value');
                
                console.log('Selecting color:', colorName, 'with value:', colorValue);
                selectColor(matchingColorOption[0], colorName, colorValue);
            } else {
                console.log('No matching color option found for thumbnail. Available colors:');
                $('#color-options .color-option').each(function() {
                    console.log('- Color:', $(this).data('color'), 'Value:', $(this).data('color-value'));
                });
            }
        } else {
            console.log('selectColor function not available');
        }
    });
    
    // Mohammad Hassan
    // Main gallery image change event (using slick's afterChange) - ROBUST SOLUTION
    $('.product-gallery').on('afterChange', function(event, slick, currentSlide) {
        const $currentSlideElement = $(slick.$slides[currentSlide]);
        const mainImageColorHex = $currentSlideElement.data('color');
        const variant = $currentSlideElement.data('variant');
        
        console.log('Main gallery slide changed - Color:', mainImageColorHex, 'Variant:', variant);
        
        // Update color selection if selectColor function exists
        if (typeof selectColor === 'function') {
            let matchingColorOption = null;
            
            // Method 1: Try to match by exact hex color value
            if (mainImageColorHex && mainImageColorHex !== '') {
                matchingColorOption = $('#color-options .color-option[data-color-value="' + mainImageColorHex + '"]');
                console.log('Main image - Method 1 exact hex match for:', mainImageColorHex, 'Found:', matchingColorOption.length > 0);
            }
            
            // Method 2: If no exact match, try to match by color name in variant
            if ((!matchingColorOption || matchingColorOption.length === 0) && variant) {
                $('#color-options .color-option').each(function() {
                    const colorName = $(this).data('color');
                    if (colorName && variant.toLowerCase().includes(colorName.toLowerCase())) {
                        matchingColorOption = $(this);
                        console.log('Main image - Method 2 color name match:', colorName, 'in variant:', variant);
                        return false; // Break the loop
                    }
                });
            }
            
            // Method 3: If still no match, try case-insensitive hex comparison
            if ((!matchingColorOption || matchingColorOption.length === 0) && mainImageColorHex) {
                $('#color-options .color-option').each(function() {
                    const optionColorValue = $(this).data('color-value');
                    if (optionColorValue && optionColorValue.toLowerCase() === mainImageColorHex.toLowerCase()) {
                        matchingColorOption = $(this);
                        console.log('Main image - Method 3 case-insensitive hex match:', optionColorValue);
                        return false; // Break the loop
                    }
                });
            }
            
            // If we found a matching color option, select it
            if (matchingColorOption && matchingColorOption.length > 0) {
                const colorName = matchingColorOption.data('color');
                const colorValue = matchingColorOption.data('color-value');
                console.log('Main image - Selecting color:', colorName, 'with value:', colorValue);
                selectColor(matchingColorOption[0], colorName, colorValue);
            } else {
                console.log('Main image - No matching color option found');
            }
        }

        // Update image counter
        const totalImages = slick.slideCount;
        const currentImage = currentSlide + 1;
        $('.image-counter .current-image').text(currentImage);
        $('.image-counter .total-images').text(totalImages);
    });


    // Mohammad Hassan
    // Initialize color assignments for images without color data
    function initializeImageColors() {
        const availableColors = [];
        $('#color-options .color-option').each(function() {
            availableColors.push($(this).data('color-value'));
        });
        
        if (availableColors.length > 0) {
            // Assign colors to gallery images that don't have color data
            $('.gallery-image').each(function(index) {
                const currentColor = $(this).data('color');
                if (!currentColor || currentColor === '') {
                    const assignedColor = availableColors[index % availableColors.length];
                    $(this).attr('data-color', assignedColor);
                    console.log('Assigned color', assignedColor, 'to gallery image', index);
                }
            });
            
            // Assign colors to thumbnail images that don't have color data
            $('.gallery-thumbnail').each(function(index) {
                const currentColor = $(this).data('color');
                if (!currentColor || currentColor === '') {
                    const assignedColor = availableColors[index % availableColors.length];
                    $(this).attr('data-color', assignedColor);
                    console.log('Assigned color', assignedColor, 'to thumbnail', index);
                }
            });
        }
    }
    
    // Initialize color assignments on page load
    initializeImageColors();

    // Initialize image counter on load for the first slide
    if ($('.product-gallery').hasClass('slick-initialized')) {
        const totalImages = $('.product-gallery').slick('getSlick').slideCount;
        $('.image-counter .current-image').text(1);
        $('.image-counter .total-images').text(totalImages);
    }
});
</script>
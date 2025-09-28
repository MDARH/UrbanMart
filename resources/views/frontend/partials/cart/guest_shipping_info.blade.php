<div class="p-3">
    <!-- Name -->
    <div class="row">
        <div class="col-md-2 mt-md-2">
            <label>{{ translate('Name')}} <span class="text-danger">*</span></label>
        </div>
        <div class="col-md-10">
            <input type="text" class="form-control mb-3 rounded-0" placeholder="{{ translate('Your Full Name')}}" name="name" required>
        </div>
    </div>

    <!-- Phone Number with Country Code -->
    <div class="row">
        <div class="col-md-2 mt-md-2">
            <label>{{ translate('Phone Number')}} <span class="text-danger">*</span></label>
        </div>
        <div class="col-md-10">
            <input type="tel" id="phone-code-guest" class="form-control mb-3 rounded-0" placeholder="{{ translate('Your Phone Number')}}" name="phone" required>
            <input type="hidden" name="country_code" value="">
        </div>
    </div>

    <!-- Email (Optional) -->
    <div class="row">
        <div class="col-md-2 mt-md-2">
            <label>{{ translate('Email')}} <span class="text-muted">({{ translate('Optional') }})</span></label>
        </div>
        <div class="col-md-10">
            <input type="email" class="form-control mb-3 rounded-0" placeholder="{{ translate('Your Email Address')}}" name="email" value="">
        </div>
    </div>

    <!-- Country - Mohammad Hassan: Set Bangladesh as default -->
    <input type="hidden" name="country_id" value="18">

    <!-- City - Mohammad Hassan: Renamed from District, loads all Bangladesh cities -->
    <div class="row">
        <div class="col-md-2 mt-md-2">
            <label>{{ translate('City')}} <span class="text-danger">*</span></label>
        </div>
        <div class="col-md-10">
            <select class="form-control mb-3 aiz-selectpicker rounded-0" data-live-search="true" name="city_id" id="guest_city" required>
                <option value="">{{ translate('Select City') }}</option>
            </select>
        </div>
    </div>

    <!-- Address -->
    <div class="row">
        <div class="col-md-2 mt-md-2">
            <label>{{ translate('Address')}} <span class="text-danger">*</span></label>
        </div>
        <div class="col-md-10">
            <textarea class="form-control mb-3 rounded-0" placeholder="{{ translate('Your Full Address')}}" rows="3" name="address" required></textarea>
        </div>
    </div>

    <!--Area-->
    <div class="row area-field d-none">
        <div class="col-md-2">
            <label>{{ translate('Area')}}<span class="text-danger">*</span></label>
        </div>
        <div class="col-md-10">
            <select class="form-control mb-3 aiz-selectpicker rounded-0 guest-checkout" data-live-search="true" name="area_id">

            </select>
        </div>
    </div>

    @if (get_setting('google_map') == 1)
        <!-- Google Map -->
        <div class="row mt-3 mb-3">
            <input id="searchInput" class="controls" type="text" placeholder="{{translate('Enter a location')}}">
            <div id="map"></div>
            <ul id="geoData">
                <li style="display: none;">Full Address: <span id="location"></span></li>
                <li style="display: none;">Postal Code: <span id="postal_code"></span></li>
                <li style="display: none;">Country: <span id="country"></span></li>
                <li style="display: none;">Latitude: <span id="lat"></span></li>
                <li style="display: none;">Longitude: <span id="lon"></span></li>
            </ul>
        </div>
        <!-- Longitude -->
        <div class="row">
            <div class="col-md-2" id="">
                <label for="exampleInputuname">{{ translate('Longitude')}}</label>
            </div>
            <div class="col-md-10" id="">
                <input type="text" class="form-control mb-3 rounded-0" id="longitude" name="longitude" readonly="">
            </div>
        </div>
        <!-- Latitude -->
        <div class="row">
            <div class="col-md-2" id="">
                <label for="exampleInputuname">{{ translate('Latitude')}}</label>
            </div>
            <div class="col-md-10" id="">
                <input type="text" class="form-control mb-3 rounded-0" id="latitude" name="latitude" readonly="">
            </div>
        </div>
    @endif

    <!-- Postal code (Optional) -->
    <div class="row">
        <div class="col-md-2 mt-md-2">
            <label>{{ translate('Postal Code')}} <span class="text-muted">({{ translate('Optional') }})</span></label>
        </div>
        <div class="col-md-10">
            <input type="text" class="form-control mb-3 rounded-0" placeholder="{{ translate('Your Postal Code')}}" name="postal_code" value="">
        </div>
    </div>
</div>

<div class="px-3 pt-3 pb-4 row">
    <div class="col-md-2 mt-md-2"></div>
    <div class="col-md-10">
        <div class="bg-soft-info p-2">
            {{ translate('If you have already used the same email address or phone number before, please ') }}
            <a href="javascript:void(0);" onclick="openUserLogin()" class="fw-700 animate-underline-primary">{{ translate('Login') }}</a>
            {{ translate(' first to continue') }}
        </div>
    </div>
</div>

<script>
// Mohammad Hassan - Guest checkout JavaScript for Bangladesh cities only (no divisions)
// Mohammad Hassan
$(document).ready(function() {
    // Load cities by country for Bangladesh since cities exist at country level
    get_guest_cities_by_country(18);
    
    // Handle city change to load areas
    $('#guest_city').on('change', function() {
        var city_id = $(this).val();
        if (city_id) {
            get_guest_areas(city_id);
        }
    });
});

function get_guest_areas(city_id) {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: "{{route('get-area')}}",
        type: 'POST',
        data: {
            city_id: city_id
        },
        success: function (response) {
            var obj = JSON.parse(response);
            $('[name="area_id"]').html(obj);
            AIZ.plugins.bootstrapSelect('refresh');
            if (obj.includes('<option') && !obj.includes('disabled selected')) {
                $('.area-field').removeClass('d-none'); 
            } else {
                $('[name="area_id"]').removeAttr('required');
                $('.area-field').addClass('d-none');
            }
        }
    });
}

// Mohammad Hassan - Function to load cities by country_id for Bangladesh
function get_guest_cities_by_country(country_id) {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: "{{route('get-city-by-country')}}",
        type: 'POST',
        data: {
            country_id: country_id
        },
        success: function (response) {
            var obj = JSON.parse(response);
            if(obj != '' && $('<select></select>').html(obj).find('option').length > 1) {
                $('#guest_city').attr('disabled', false);
                $('#guest_city').html(obj);
                AIZ.plugins.bootstrapSelect('refresh');
            } else {
                $('#guest_city').html('<option value="">{{ translate("No cities are available.") }}</option>');
                $('#guest_city').attr('disabled', true);
                AIZ.plugins.bootstrapSelect('refresh');
            }
        }
    });
}
</script>

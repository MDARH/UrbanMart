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

    <!-- Address -->
    <div class="row">
        <div class="col-md-2 mt-md-2">
            <label>{{ translate('Address')}} <span class="text-danger">*</span></label>
        </div>
        <div class="col-md-10">
            <textarea class="form-control mb-3 rounded-0" placeholder="{{ translate('Your Full Address')}}" rows="3" name="address" required></textarea>
        </div>
    </div>

    <!-- Country -->
    @if (get_active_countries()->count() > 1)
    <div class="row">
        <div class="col-md-2 mt-md-2">
            <label>{{ translate('Country')}} <span class="text-danger">*</span></label>
        </div>
        <div class="col-md-10">
            <div class="mb-3">
                <select class="form-control aiz-selectpicker rounded-0" @if (get_setting('shipping_type') == 'carrier_wise_shipping') onchange="updateDeliveryAddress(this.value)" @endif
                    data-live-search="true" data-placeholder="{{ translate('Select your country') }}" name="country_id" required>
                    <option value="">{{ translate('Select your country') }}</option>
                    @foreach (get_active_countries() as $key => $country)
                        <option value="{{ $country->id }}" {{ $country->name == 'Bangladesh' ? 'selected' : '' }}>{{ $country->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    @elseif(get_active_countries()->count() == 1)
    <input type="hidden" name="country_id" value="{{get_active_countries()[0]->id }}">
    @endif

    @if(get_setting('has_state') == 1)
    <!-- State -->
    <div class="row">
        <div class="col-md-2 mt-md-2">
            <label>{{ translate('State')}} <span class="text-danger">*</span></label>
        </div>
        <div class="col-md-10">
            <select class="form-control mb-3 aiz-selectpicker rounded-0" data-live-search="true" name="state_id" required>

            </select>
        </div>
    </div>
    @endif

    <!-- City (Optional) -->
    <div class="row">
        <div class="col-md-2 mt-md-2">
            <label>{{ translate('City')}} <span class="text-muted">({{ translate('Optional') }})</span></label>
        </div>
        <div class="col-md-10">
            <input type="text" class="form-control mb-3 rounded-0" placeholder="{{ translate('Your City')}}" name="city" value="">
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

<form class="form-default" role="form" action="{{ route('addresses.update', $address_data->id) }}" method="POST">
    @csrf
    <div class="p-3">
        <!-- Address -->
        <div class="row">
            <div class="col-md-2">
                <label>{{ translate('Address')}}</label>
            </div>
            <div class="col-md-10">
                <textarea class="form-control mb-3 rounded-0" placeholder="{{ translate('Your Address')}}" rows="2" name="address" required>{{ $address_data->address }}</textarea>
            </div>
        </div>

        @if (get_active_countries()->count() > 1)
        <!-- Country -->
        <div class="row">
            <div class="col-md-2">
                <label>{{ translate('Country')}}</label>
            </div>
            <div class="col-md-10">
                <div class="mb-3">
                    <select class="form-control aiz-selectpicker rounded-0" data-live-search="true" data-placeholder="{{ translate('Select your country')}}" name="country_id" id="edit_country" required>
                        <option value="">{{ translate('Select your country') }}</option>
                        @foreach (get_active_countries() as $key => $country)
                        <option value="{{ $country->id }}" @if($address_data->country_id == $country->id) selected @endif>
                            {{ $country->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        @elseif(get_active_countries()->count() == 1)
        <input type="hidden" name="country_id" value="{{get_active_countries()[0]->id }}">
        @endif

        @if (get_setting('has_state') == 1)
        <!-- State -->
        <div class="row">
            <div class="col-md-2">
                <label>{{ translate('State')}}</label>
            </div>
            <div class="col-md-10">
                <select class="form-control mb-3 aiz-selectpicker rounded-0" name="state_id" id="edit_state"  data-live-search="true" required>
                <option value="" disabled>{{ translate('Select State') }}</option>
                @foreach ($states as $key => $state)
                        <option value="{{ $state->id }}" @if($address_data->state_id == $state->id) selected @endif>
                            {{ $state->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        @endif

        <!-- City (Optional) -->
        <div class="row">
            <div class="col-md-2">
                <label>{{ translate('City')}} <span class="text-muted">({{ translate('Optional') }})</span></label>
            </div>
            <div class="col-md-10">
                <input type="text" class="form-control mb-3 rounded-0" placeholder="{{ translate('Your City')}}" name="city" value="{{ optional($address_data->city)->name }}">
            </div>
        </div>

       
        <div class="row area-field {{ ($areas->count() == 0) ? 'd-none' : '' }}">
            <div class="col-md-2">
                <label>{{ translate('Area')}}</label>
            </div>
            <div class="col-md-10">
                <select class="form-control mb-3 aiz-selectpicker rounded-0" data-live-search="true" name="area_id">
                    @foreach ($areas as $key => $area)
                        <option value="{{ $area->id }}" @if($address_data->area_id == $area->id) selected @endif>
                            {{ $area->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        
        @if (get_setting('google_map') == 1)
            <!-- Google Map -->
            <div class="row mt-3 mb-3">
                <input id="edit_searchInput" class="controls" type="text" placeholder="Enter a location">
                <div id="edit_map"></div>
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
                    <input type="text" class="form-control mb-3 rounded-0" id="edit_longitude" name="longitude" value="{{ $address_data->longitude }}" readonly="">
                </div>
            </div>
            <!-- Latitude -->
            <div class="row">
                <div class="col-md-2" id="">
                    <label for="exampleInputuname">{{ translate('Latitude')}}</label>
                </div>
                <div class="col-md-10" id="">
                    <input type="text" class="form-control mb-3 rounded-0" id="edit_latitude" name="latitude" value="{{ $address_data->latitude }}" readonly="">
                </div>
            </div>
        @endif

        <!-- Postal code (Optional) -->
        <div class="row">
            <div class="col-md-2">
                <label>{{ translate('Postal Code')}} <span class="text-muted">({{ translate('Optional') }})</span></label>
            </div>
            <div class="col-md-10">
                <input type="text" class="form-control mb-3 rounded-0" placeholder="{{ translate('Your Postal Code')}}" value="{{ $address_data->postal_code }}" name="postal_code">
            </div>
        </div>

        <!-- Phone -->
        <div class="row">
            <div class="col-md-2">
                <label>{{ translate('Phone')}}</label>
            </div>
            <div class="col-md-10">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <select class="form-control rounded-0" name="country_code" style="max-width: 120px;">
                            @php
                                $phone = $address_data->phone;
                                $country_code = '+880'; // Default
                                $phone_number = $phone;
                                
                                // Extract country code from existing phone
                                if (preg_match('/^(\+\d{1,4})(.*)$/', $phone, $matches)) {
                                    $country_code = $matches[1];
                                    $phone_number = ltrim($matches[2], '0');
                                }
                            @endphp
                            <option value="+880" {{ $country_code == '+880' ? 'selected' : '' }}>🇧🇩 +880</option>
                            <option value="+1" {{ $country_code == '+1' ? 'selected' : '' }}>🇺🇸 +1</option>
                            <option value="+44" {{ $country_code == '+44' ? 'selected' : '' }}>🇬🇧 +44</option>
                            <option value="+91" {{ $country_code == '+91' ? 'selected' : '' }}>🇮🇳 +91</option>
                            <option value="+86" {{ $country_code == '+86' ? 'selected' : '' }}>🇨🇳 +86</option>
                            <option value="+81" {{ $country_code == '+81' ? 'selected' : '' }}>🇯🇵 +81</option>
                            <option value="+49" {{ $country_code == '+49' ? 'selected' : '' }}>🇩🇪 +49</option>
                            <option value="+33" {{ $country_code == '+33' ? 'selected' : '' }}>🇫🇷 +33</option>
                            <option value="+39" {{ $country_code == '+39' ? 'selected' : '' }}>🇮🇹 +39</option>
                            <option value="+34" {{ $country_code == '+34' ? 'selected' : '' }}>🇪🇸 +34</option>
                            <option value="+7" {{ $country_code == '+7' ? 'selected' : '' }}>🇷🇺 +7</option>
                            <option value="+55" {{ $country_code == '+55' ? 'selected' : '' }}>🇧🇷 +55</option>
                            <option value="+52" {{ $country_code == '+52' ? 'selected' : '' }}>🇲🇽 +52</option>
                            <option value="+61" {{ $country_code == '+61' ? 'selected' : '' }}>🇦🇺 +61</option>
                            <option value="+82" {{ $country_code == '+82' ? 'selected' : '' }}>🇰🇷 +82</option>
                            <option value="+65" {{ $country_code == '+65' ? 'selected' : '' }}>🇸🇬 +65</option>
                            <option value="+60" {{ $country_code == '+60' ? 'selected' : '' }}>🇲🇾 +60</option>
                            <option value="+66" {{ $country_code == '+66' ? 'selected' : '' }}>🇹🇭 +66</option>
                            <option value="+84" {{ $country_code == '+84' ? 'selected' : '' }}>🇻🇳 +84</option>
                            <option value="+62" {{ $country_code == '+62' ? 'selected' : '' }}>🇮🇩 +62</option>
                            <option value="+63" {{ $country_code == '+63' ? 'selected' : '' }}>🇵🇭 +63</option>
                        </select>
                    </div>
                    <input type="tel" class="form-control rounded-0" placeholder="{{ translate('Your Phone Number')}}" value="{{ $phone_number }}" name="phone" pattern="[0-9]{10,11}" required>
                </div>
            </div>
        </div>

        <!-- Save button -->
        <div class="form-group text-right">
            <button type="submit" class="btn btn-primary rounded-0 w-150px">{{translate('Save')}}</button>
        </div>
    </div>
</form>
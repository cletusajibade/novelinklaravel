{{-- @props(['countries']) --}}

<form action="{{ route('consultation.store') }}" method="post" class="wpcf7-form">
    @csrf

    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 form-group">
            <span class="wpcf7-form-control-wrap" data-name="first-name">
                <span class="form-el-label">First Name<span style="color: red"> *</span></span>
                <input type="text" name="first_name" value="{{ old('first_name') }}" size="40"
                    class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" aria-required="true"
                    aria-invalid="false">
                @error('first_name')
                    <div class="text-danger error-message">{{ $message }}</div>
                @enderror
            </span>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 form-group">
            <span class="wpcf7-form-control-wrap" data-name="last-name">
                <span class="form-el-label">Last Name<span style="color: red"> *</span></span>
                <input type="text" name="last_name" value="{{ old('last_name') }}" size="40"
                    class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" aria-required="true"
                    aria-invalid="false">
                @error('last_name')
                    <div class="text-danger error-message">{{ $message }}</div>
                @enderror
            </span>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 form-group">
            <span class="wpcf7-form-control-wrap" data-name="your-email">
                <span class="form-el-label">Email Address<span style="color: red"> *</span></span>
                <input type="email" name="email" value="{{ old('email') }}" size="40"
                    class="wpcf7-form-control wpcf7-text wpcf7-email wpcf7-validates-as-required wpcf7-validates-as-email"
                    aria-required="true" aria-invalid="false">
                @error('email')
                    <div class="text-danger error-message">{{ $message }}</div>
                @enderror
            </span>
        </div>
        {{-- TODO: Country Code may be implement --}}
        {{-- <div class="col-lg-3 col-md-12 col-sm-12 form-group" style="padding-right: 0;">
            <span class="wpcf7-form-control-wrap" data-name="phone">
                <span class="form-el-label">Country Code<span style="color: red"> *</span></span>
                <input id="country_code" type="text" onkeypress="return /[0-9+]/i.test(event.key)"
                    name="country_code" value="{{ old('country_code') }}" size="40"
                    class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" aria-required="true"
                    aria-invalid="false" placeholder="+1">
                @error('country_code')
                    <div class="text-danger error-message">{{ $message }}</div>
                @enderror
            </span>
        </div> --}}
        <div class="col-lg-12 col-md-12 col-sm-12 form-group">
            <span class="wpcf7-form-control-wrap" data-name="phone">
                <span class="form-el-label">Phone Number<span style="color: red"> *</span></span>
                <input id="phone" type="text" onkeypress="return /[0-9 ]/i.test(event.key)" name="phone"
                    value="{{ old('phone') }}" size="40"
                    class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" aria-required="true"
                    aria-invalid="false" placeholder="123 456 7890">
                @error('phone')
                    <div class="text-danger error-message">{{ $message }}</div>
                @enderror
            </span>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 form-group">
            <span class="wpcf7-form-control-wrap" data-name="date-of-birth">
                <span class="form-el-label">Date of Birth<span style="color: red"> *</span></span>
                <input id="nove_date_picker" name="date_of_birth" type="text" readonly="readonly"
                    value="{{ old('date_of_birth') }}" placeholder="YYYY-MM-DD">
                @error('date_of_birth')
                    <div class="text-danger error-message">{{ $message }}</div>
                @enderror
            </span>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 form-group">
            <span class="wpcf7-form-control-wrap" data-name="country">
                <span class="form-el-label">Country/Countries of Citizenship<span style="color: red">
                        *</span></span>
                <input type="text" name="country" value="{{ old('country') }}" size="40"
                    class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" aria-required="true"
                    aria-invalid="false">
                {{-- TODO: Troubleshoot why countries in Select dropdown not scrollable --}}
                {{-- <select name="country" id="country" class="wpcf7-form-control wpcf7-select wide">
                    <option value="">Select:</option>
                    @foreach ($countries as $country)
                        <option value="{{ $country['code'] }} "{{ old('country') == $country['code'] ? 'selected' : '' }}>
                            {{ $country['name'] }}</option>
                    @endforeach
                </select> --}}
                @error('country')
                    <div class="text-danger error-message">{{ $message }}</div>
                @enderror
            </span>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 form-group">
            <span class="wpcf7-form-control-wrap" data-name="country-of-residence">
                <span class="form-el-label">Country of Residence<span style="color: red"> *</span></span>
                <input type="text" name="country_of_residence" value="{{ old('country_of_residence') }}"
                    size="40" class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required"
                    aria-required="true" aria-invalid="false">
                @error('country_of_residence')
                    <div class="text-danger error-message">{{ $message }}</div>
                @enderror
            </span>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 form-group message-btn">
            <button class="theme-btn-two wpcf7-form-control" type="submit" name="submit-form">
                Continue<i class="flaticon-send"></i>
            </button>
        </div>
    </div>
</form>

<form action="{{ route('consultation.store') }}" method="post" class="wpcf7-form">
    @csrf
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 form-group">
            <span class="wpcf7-form-control-wrap" data-name="first-name">
                <span class="form-el-label">Enter your first name<span style="color: red"> *</span></span>
                <input type="text" name="first_name" value="{{ old('first_name') }}" size="40"
                    class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" aria-required="true"
                    aria-invalid="false">
            </span>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 form-group">
            <span class="wpcf7-form-control-wrap" data-name="last-name">
                <span class="form-el-label">Enter your last name<span style="color: red"> *</span></span>
                <input type="text" name="last_name" value="{{ old('last_name') }}" size="40"
                    class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" aria-required="true"
                    aria-invalid="false">
            </span>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 form-group">
            <span class="wpcf7-form-control-wrap" data-name="your-email">
                <span class="form-el-label">Provide your email address<span style="color: red"> *</span></span>
                <input type="email" name="email" value="<?php if (isset($email)) {
                    echo $email;
                } ?>" size="40"
                    class="wpcf7-form-control wpcf7-text wpcf7-email wpcf7-validates-as-required wpcf7-validates-as-email"
                    aria-required="true" aria-invalid="false">
            </span>
        </div>
        <div class="col-lg-3 col-md-12 col-sm-12 form-group" style="padding-right: 0;">
            <span class="wpcf7-form-control-wrap" data-name="phone">
                <span class="form-el-label">Country Code<span style="color: red"> *</span></span>
                <input id="country_code" type="text" onkeypress="return /[0-9+]/i.test(event.key)"
                    name="country_code" value="<?php if (isset($country_code)) {
                        echo $country_code;
                    } ?>" size="40"
                    class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" aria-required="true"
                    aria-invalid="false" placeholder="+1">
            </span>
        </div>
        <div class="col-lg-9 col-md-12 col-sm-12 form-group">
            <span class="wpcf7-form-control-wrap" data-name="phone">
                <span class="form-el-label">Phone number<span style="color: red"> *</span></span>
                <input id="phone" type="text" onkeypress="return /[0-9 ]/i.test(event.key)" name="phone"
                    value="<?php if (isset($phone)) {
                        echo $phone;
                    } ?>" size="40"
                    class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" aria-required="true"
                    aria-invalid="false" placeholder="123 456 7890">
            </span>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 form-group">
            <span class="wpcf7-form-control-wrap" data-name="date-of-birth">
                <span class="form-el-label">Select your date of birth<span style="color: red"> *</span></span>
                <input id="nove_date_picker" name="date_of_birth" type="text" readonly="readonly"
                    value="<?php if (isset($date_of_birth)) {
                        echo $date_of_birth;
                    } ?>" placeholder="YYYY-MM-DD">
            </span>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 form-group">
            <span class="wpcf7-form-control-wrap" data-name="country">
                <span class="form-el-label">Enter country/countries of citizenship<span style="color: red">
                        *</span></span>
                <input type="text" name="country" value="<?php if (isset($country)) {
                    echo $country;
                } ?>" size="40"
                    class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" aria-required="true"
                    aria-invalid="false">
            </span>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 form-group">
            <span class="wpcf7-form-control-wrap" data-name="country-of-residence">
                <span class="form-el-label">Country of residence<span style="color: red"> *</span></span>
                <input type="text" name="country_of_residence" value="<?php if (isset($country_of_residence)) {
                    echo $country_of_residence;
                } ?>" size="40"
                    class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" aria-required="true"
                    aria-invalid="false">
            </span>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 form-group message-btn">
            <button class="theme-btn-two wpcf7-form-control" type="submit" name="submit-form">
                <i class="flaticon-send"></i>Continue
            </button>
        </div>
    </div>
</form>

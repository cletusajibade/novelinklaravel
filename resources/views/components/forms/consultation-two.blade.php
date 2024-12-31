<form action="{{ route('consultation.store') }}" method="post" class="wpcf7-form">
    @csrf
    <div class="row clearfix">
        
        <div class="col-lg-12 col-md-12 col-sm-12 form-group">
            <span class="wpcf7-form-control-wrap" data-name="country-residence-status">
                <span class="form-el-label">Status in country of residence<span style="color: red"> *</span></span>
                <select name="country_residence_status" class="wpcf7-form-control wpcf7-select wide"
                    aria-invalid="false" style="display: none">
                    <option value="Select:">
                        Select:
                    </option>
                    <option <?php if (isset($_POST['country_residence_status']) and $_POST['country_residence_status'] == 'Citizen') { ?>selected="selected" <?php }; ?>value="Citizen">Citizen</option>
                    <option <?php if (isset($_POST['country_residence_status']) and $_POST['country_residence_status'] == 'Permanent Resident') { ?>selected="true" <?php }; ?>value="Permanent Resident">
                        Permanent Resident</option>
                    <option <?php if (isset($_POST['country_residence_status']) and $_POST['country_residence_status'] == 'Worker') { ?>selected="true" <?php }; ?>value="Worker">Worker</option>
                    <option <?php if (isset($_POST['country_residence_status']) and $_POST['country_residence_status'] == 'Student') { ?>selected="true" <?php }; ?>value="Student">Student</option>
                    <option <?php if (isset($_POST['country_residence_status']) and $_POST['country_residence_status'] == 'Visitor') { ?>selected="true" <?php }; ?>value="Visitor">Visitor</option>
                    <option <?php if (isset($_POST['country_residence_status']) and $_POST['country_residence_status'] == 'Asylum Seeker') { ?>selected="true" <?php }; ?>value="Asylum Seeker">Asylum
                        Seeker</option>
                </select>
            </span>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 form-group">
            <div class="select-box">
                <span class="wpcf7-form-control-wrap" data-name="marital-status">
                    <span class="form-el-label">Select your marital status<span style="color: red"> *</span></span>
                    <select name="marital_status" class="wpcf7-form-control wpcf7-select wide" aria-invalid="false"
                        style="display: none">
                        <option value="Select:">
                            Select:
                        </option>
                        <option <?php if (isset($_POST['marital_status']) and $_POST['marital_status'] == 'Single') { ?>selected="selected" <?php }; ?>value="Single">Single
                        </option>
                        <option <?php if (isset($_POST['marital_status']) and $_POST['marital_status'] == 'Married') { ?>selected="true" <?php }; ?>value="Married">Married
                        </option>
                        <option <?php if (isset($_POST['marital_status']) and $_POST['marital_status'] == 'Divorced') { ?>selected="true" <?php }; ?>value="Divorced">Divorced
                        </option>
                        <option <?php if (isset($_POST['marital_status']) and $_POST['marital_status'] == 'Common-Law') { ?>selected="true" <?php }; ?>value="Common-Law">Common-Law
                        </option>
                        <option <?php if (isset($_POST['marital_status']) and $_POST['marital_status'] == 'Other') { ?>selected="true" <?php }; ?>value="Other">Other</option>
                    </select>
                </span>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 form-group">
            <div class="select-box">
                <span class="wpcf7-form-control-wrap" data-name="passport">
                    <span class="form-el-label">Do you have a passport?<span style="color: red"> *</span></span>
                    <select name="have_a_passport" class="wpcf7-form-control wpcf7-select wide" aria-invalid="false"
                        style="display: none">
                        <option value="Select:">
                            Select:
                        </option>
                        <option <?php if (isset($_POST['have_a_passport']) and $_POST['have_a_passport'] == '1') { ?>selected="selected" <?php }; ?>value="1">Yes</option>
                        <option <?php if (isset($_POST['have_a_passport']) and $_POST['have_a_passport'] == '0') { ?>selected="selected" <?php }; ?>value="0">No</option>
                    </select>
                </span>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 form-group">
            <span class="wpcf7-form-control-wrap" data-name="passport-expiry-date">
                <span class="form-el-label">What is your passport expiry date?</span>
                <input id="passport-expiry-date" name="passport_expiry_date" type="text" readonly="readonly"
                    value="<?php if (isset($passport_expiry_date)) {
                        echo $passport_expiry_date;
                    } ?>">
            </span>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 form-group">
            <span class="wpcf7-form-control-wrap" data-name="passport-country">
                <span class="form-el-label">Which country issued the passport?</span>
                <input type="text" name="passport_country" value="<?php if (isset($passport_country)) {
                    echo $passport_country;
                } ?>" size="40"
                    class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" aria-required="true"
                    aria-invalid="false">
            </span>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 form-group">
            <div class="select-box">
                <span class="wpcf7-form-control-wrap" data-name="family-coming-to-canada">
                    <span class="form-el-label">Are you coming to Canada with your family member(s)?<span
                            style="color: red"> *</span></span>
                    <select name="family_coming_to_canada" class="wpcf7-form-control wpcf7-select wide"
                        aria-invalid="false" style="display: none">
                        <option value="Select:">
                            Select:
                        </option>
                        <option <?php if (isset($_POST['family_coming_to_canada']) and $_POST['family_coming_to_canada'] == '1') { ?>selected="selected" <?php }; ?>value="1">Yes</option>
                        <option <?php if (isset($_POST['family_coming_to_canada']) and $_POST['family_coming_to_canada'] == '0') { ?>selected="selected" <?php }; ?>value="0">No</option>
                    </select>
                </span>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 form-group">
            <div class="select-box">
                <span class="wpcf7-form-control-wrap" data-name="highest-education">
                    <span class="form-el-label">What is your highest level of education?<span style="color: red">
                            *</span></span>
                    <select name="highest_education" class="wpcf7-form-control wpcf7-select wide"
                        aria-invalid="false" style="display: none">
                        <option value="Select:">
                            Select:
                        </option>
                        <option <?php if (isset($_POST['highest_education']) and $_POST['highest_education'] == 'None') { ?>selected="selected" <?php }; ?>value="None">None</option>
                        <option <?php if (isset($_POST['highest_education']) and $_POST['highest_education'] == 'High School') { ?>selected="selected" <?php }; ?>value="High School">
                            Secondary/High School</option>
                        <option <?php if (isset($_POST['highest_education']) and $_POST['highest_education'] == 'Diploma') { ?>selected="selected" <?php }; ?>value="Diploma">Diploma of
                            two (2) or more years</option>
                        <option <?php if (isset($_POST['highest_education']) and $_POST['highest_education'] == 'Undergraduate/Associates Degree') { ?>selected="selected"
                            <?php }; ?>value="Undergraduate/Associates Degree">Undergraduate/Associates Degree
                        </option>
                        <option <?php if (isset($_POST['highest_education']) and $_POST['highest_education'] == 'Bachelors') { ?>selected="selected" <?php }; ?>value="Bachelors">
                            Bachelor's Degree</option>
                        <option <?php if (isset($_POST['highest_education']) and $_POST['highest_education'] == 'Masters') { ?>selected="selected" <?php }; ?>value="Masters">Master's
                            Degree</option>
                        <option <?php if (isset($_POST['highest_education']) and $_POST['highest_education'] == 'Ph.D') { ?>selected="selected" <?php }; ?>value="Ph.D">Ph.D</option>
                        <option <?php if (isset($_POST['highest_education']) and $_POST['highest_education'] == 'Other') { ?>selected="selected" <?php }; ?>value="Other">Other
                        </option>
                    </select>
                </span>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 form-group">
            <span class="wpcf7-form-control-wrap" data-name="occupation">
                <span class="form-el-label">What is your current occupation? (If self-employed please indicate) <span
                        style="color: red"> *</span></span>
                <input type="text" name="occupation" value="<?php if (isset($occupation)) {
                    echo $occupation;
                } ?>" size="40"
                    class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" aria-required="true"
                    aria-invalid="false">
            </span>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 form-group">
            <span class="wpcf7-form-control-wrap" data-name="years-of-experience">
                <span class="form-el-label">How many years of experience do you have in this occupation? <span
                        style="color: red">*</span></span>
                <input type="text" onkeypress="return /[0-9 ]/i.test(event.key)" name="years_of_experience"
                    value="<?php if (isset($years_of_experience)) {
                        echo $years_of_experience;
                    } ?>" size="40"
                    class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" aria-required="true"
                    aria-invalid="false">
            </span>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 form-group">
            <div class="select-box">
                <span class="wpcf7-form-control-wrap" data-name="canadian-work-experience">
                    <span class="form-el-label">Do you have any Canadian work experience?<span style="color: red">
                            *</span></span>
                    <select name="canadian_work_experience" class="wpcf7-form-control wpcf7-select wide"
                        aria-invalid="false" style="display: none">
                        <option value="Select:">
                            Select:
                        </option>
                        <option <?php if (isset($_POST['canadian_work_experience']) and $_POST['canadian_work_experience'] == '1') { ?>selected="selected" <?php }; ?>value="1">Yes</option>
                        <option <?php if (isset($_POST['canadian_work_experience']) and $_POST['canadian_work_experience'] == '0') { ?>selected="selected" <?php }; ?>value="0">No</option>
                    </select>
                </span>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 form-group">
            <div class="select-box">
                <span class="wpcf7-form-control-wrap" data-name="canadian-education">
                    <span class="form-el-label">Do you have any Canadian education?<span style="color: red">
                            *</span></span>
                    <select name="canadian_education" class="wpcf7-form-control wpcf7-select wide"
                        aria-invalid="false" style="display: none">
                        <option value="Select:">
                            Select:
                        </option>
                        <option <?php if (isset($_POST['canadian_education']) and $_POST['canadian_education'] == '1') { ?>selected="selected" <?php }; ?>value="1">Yes</option>
                        <option <?php if (isset($_POST['canadian_education']) and $_POST['canadian_education'] == '0') { ?>selected="selected" <?php }; ?>value="0">No</option>
                    </select>
                </span>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 form-group">
            <div class="select-box">
                <span class="wpcf7-form-control-wrap" data-name="refused-visa">
                    <span class="form-el-label">Have you ever been refused a visa or permit, denied entry or ordered to
                        leave Canada or any other country or territory?<span style="color: red"> *</span></span>
                    <select name="refused_visa" class="wpcf7-form-control wpcf7-select wide" aria-invalid="false"
                        style="display: none">
                        <option value="Select:">
                            Select:
                        </option>
                        <option <?php if (isset($_POST['refused_visa']) and $_POST['refused_visa'] == '1') { ?>selected="selected" <?php }; ?>value="1">Yes</option>
                        <option <?php if (isset($_POST['refused_visa']) and $_POST['refused_visa'] == '0') { ?>selected="selected" <?php }; ?>value="0">No</option>
                    </select>
                </span>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 form-group">
            <div class="select-box">
                <span class="wpcf7-form-control-wrap" data-name="involved-in-genocide">
                    <span class="form-el-label">Have you been involved in an act of genocide, a war crime or in the
                        commission of a crime against humanity?<span style="color: red"> *</span></span>
                    <select name="involved_in_genocide" class="wpcf7-form-control wpcf7-select wide"
                        aria-invalid="false" style="display: none">
                        <option value="Select:">
                            Select:
                        </option>
                        <option <?php if (isset($_POST['involved_in_genocide']) and $_POST['involved_in_genocide'] == '1') { ?>selected="selected" <?php }; ?>value="1">Yes</option>
                        <option <?php if (isset($_POST['involved_in_genocide']) and $_POST['involved_in_genocide'] == '0') { ?>selected="selected" <?php }; ?>value="0">No</option>
                    </select>
                </span>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 form-group">
            <div class="select-box">
                <span class="wpcf7-form-control-wrap" data-name="criminal-offence">
                    <span class="form-el-label">Have you ever committed, been arrested for, or been charged with or
                        convicted of any criminal offence in any country or territory?<span style="color: red">
                            *</span></span>
                    <select name="criminal_offence" class="wpcf7-form-control wpcf7-select wide" aria-invalid="false"
                        style="display: none">
                        <option value="Select:">
                            Select:
                        </option>
                        <option <?php if (isset($_POST['criminal_offence']) and $_POST['criminal_offence'] == '1') { ?>selected="selected" <?php }; ?>value="1">Yes</option>
                        <option <?php if (isset($_POST['criminal_offence']) and $_POST['criminal_offence'] == '0') { ?>selected="selected" <?php }; ?>value="0">No</option>
                    </select>
                </span>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 form-group">
            <span class="wpcf7-form-control-wrap" data-name="background-check-details">
                <span class="form-el-label">If you answered yes to any of the last 3 questions, please provide the
                    details</span>
                <textarea type="text" name="background_check_details" size="40"
                    class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" aria-required="true" aria-invalid="false"></textarea>
            </span>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 form-group">
            <span class="wpcf7-form-control-wrap" data-name="other-information">
                <span class="form-el-label">Do you have any other information you would like to provide?</span>
                <input type="text" name="other_information" value="<?php if (isset($other_information)) {
                    echo $other_information;
                } ?>" size="40"
                    class="wpcf7-form-control wpcf7-text wpcf7-validates-as-required" aria-required="true"
                    aria-invalid="false">
            </span>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 form-group">
            <div class="select-box">
                <span class="wpcf7-form-control-wrap" data-name="consultation-packages">
                    <span class="form-el-label">Select your Consultation Package(s):<span style="color: red">
                            *</span></span>
                    <div class="checkbox-group">
                        <input type="checkbox" id="checkbox-1" name="package[]" value="PR_100">
                        <label for="checkbox-1"> {{TITLE_PERM_RESIDENCE}} (US$100)</label>

                        <input type="checkbox" id="checkbox-2" name="package[]" value="FS_100">
                        <label for="checkbox-2">Family Sponsorship (US$100)</label>

                        <input type="checkbox" id="checkbox-3" name="package[]" value="VV_100">
                        <label for="checkbox-3">Visitor Visas (US$100)</label>

                        <input type="checkbox" id="checkbox-4" name="package[]" value="SP_100">
                        <label for="checkbox-4">Study Permit (US$100)</label>

                        <input type="checkbox" id="checkbox-5" name="package[]" value="WP_100">
                        <label for="checkbox-5">Work Permit (US$100)</label>

                        <input type="checkbox" id="checkbox-12" name="package[]" value="EI_450">
                        <label for="checkbox-12">Entrepreneur/Investor (US$450)</label>

                        <input type="checkbox" id="checkbox-7" name="package[]" value="C_80">
                        <label for="checkbox-7">{{TITLE_CITIZENSHIP}} (US$80)</label>

                        <input type="checkbox" id="checkbox-8" name="package[]" value="O_100">
                        <label for="checkbox-8">Others (US$100)</label>
                    </div>
                </span>
            </div>
        </div>
        <div class="col-lg-12 col-md-12 col-sm-12 form-group message-btn">
            <button class="theme-btn-two wpcf7-form-control" type="submit" name="submit-form">
                <i class="flaticon-send"></i>Continue
            </button>
        </div>
    </div>
</form>

<?php
    require_once './util/autoload.php';

    $fns = new MyFunctions();

    if (isset($_POST['submit-form'])) {

        /**
         * Get all the POST form fields with validations:
         */

        $first_name = trim($_POST['first_name']);
        $last_name = trim($_POST['last_name']);
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $phone = trim($_POST['phone']);
        $country_code = trim($_POST['country_code']);
        $date_of_birth = trim($_POST['date_of_birth']);
        $country = trim($_POST['country']);
        $country_of_residence = trim($_POST['country_of_residence']);
        $country_residence_status = trim($_POST['country_residence_status']);
        $marital_status = trim($_POST['marital_status']);
        $have_a_passport = trim($_POST['have_a_passport']);
        $passport_expiry_date = trim($_POST['passport_expiry_date']);
        $passport_country = trim($_POST['passport_country']);
        $family_coming_to_canada = trim($_POST['family_coming_to_canada']);
        $highest_education = trim($_POST['highest_education']);
        $occupation = trim($_POST['occupation']);
        $years_of_experience = filter_input(INPUT_POST, 'years_of_experience', FILTER_VALIDATE_INT);
        $canadian_work_experience = filter_input(INPUT_POST, 'canadian_work_experience', FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        $canadian_education = filter_input(INPUT_POST, 'canadian_education', FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        $refused_visa = filter_input(INPUT_POST, 'refused_visa', FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        $involved_in_genocide = filter_input(INPUT_POST, 'involved_in_genocide', FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        $criminal_offence = filter_input(INPUT_POST, 'criminal_offence', FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        $background_check_details = trim($_POST['background_check_details']);
        $other_information = trim($_POST['other_information']);
        $package = filter_input_array(INPUT_POST, array(
            'package' => array(
                'filter' => FILTER_DEFAULT,
                'flags' => FILTER_REQUIRE_ARRAY
            )
        ));


        /**
         * Do additional basic validations:
         */

        $validatedPhone = $fns->validatePhone($country_code, $phone);


        //echo $family_coming_to_canada;

        if (empty($first_name)) {
            $errorMsg = "First name is required.";
        } elseif (empty($last_name)) {
            $errorMsg = "Last name is required.";
        } elseif (empty($email)) {
            $errorMsg = "You did not enter a valid email address";
        } elseif (!preg_match("/^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$/i", $email)) {
            $errorMsg = 'You did not enter a valid email.';
        } elseif (!$validatedPhone['isValid']) {
            $errorMsg = $validatedPhone['error'];
        } elseif (empty($date_of_birth)) {
            $errorMsg = "Select your date of birth.";
        } elseif (empty($country)) {
            $errorMsg = "Enter country/countries of citizenship.";
        } elseif (empty($country_of_residence)) {
            $errorMsg = "Please enter country of residence.";
        } elseif ($country_residence_status == "Select:") {
            $errorMsg = "Status in country of residence is required.";
        } elseif ($marital_status == "Select:") {
            $errorMsg = "Select valid marital status.";
        } elseif ($have_a_passport == "Select:") {
            $errorMsg = "Do you have a passport? Select Yes or No.";
        } elseif (($have_a_passport == "1") and empty($passport_expiry_date)) {
            $errorMsg = "What is your passport expiry date?";
        } elseif (($have_a_passport == "1") and empty($passport_country)) {
            $errorMsg = "Enter the country that issued your passport";
        } elseif ($family_coming_to_canada == "Select:") {
            $errorMsg = "Are you coming to Canada with your family members? Select Yes or No.";
        } elseif ($highest_education == "Select:") {
            $errorMsg = "Select highest level of education.";
        } elseif (empty($occupation)) {
            $errorMsg = "Enter current occupation";
        } elseif (empty($years_of_experience)) {
            $errorMsg = "Enter years of experience.";
        } elseif (is_null($canadian_work_experience)) {
            $errorMsg = "Do you have any Canadian work experience? Select Yes or No.";
        } elseif (is_null($canadian_education)) {
            $errorMsg = "Do you have any Canadian education? Select Yes or No.";
        } elseif (is_null($refused_visa)) {
            $errorMsg = "Select Yes or No if you have been refused a visa or permit.";
        } elseif (is_null($involved_in_genocide)) {
            $errorMsg = "Select Yes or No if you have been involved in an act of either genocide or war crime.";
        } elseif (is_null($criminal_offence)) {
            $errorMsg = "Select Yes or No if you have been convicted of any criminal offence in any country.";
        } elseif (($refused_visa or $involved_in_genocide or $criminal_offence) and empty($background_check_details)) {
            $errorMsg = "Please give details why any of the background check questions is Yes.";
        } elseif (is_null($package['package'])) {
            $errorMsg = "Select at least one Consultation Package.";
        } else {

            /**
             * Add all fields to the super-global session variable:
             **/

            $_SESSION["u_id"] = $fns->str_rand(32);
            $_SESSION["first_name"] = $first_name;
            $_SESSION["last_name"] = $last_name;
            $_SESSION["email"] = $email;
            $_SESSION["phone"] = $phone;
            $_SESSION["date_of_birth"] = $date_of_birth;
            $_SESSION["country"] = $country;
            $_SESSION["country_of_residence"] = $country_of_residence;
            $_SESSION["country_residence_status"] = $country_residence_status;
            $_SESSION["marital_status"] = $marital_status;
            $_SESSION["have_a_passport"] = $have_a_passport;
            $_SESSION["passport_expiry_date"] = empty($passport_expiry_date) ?"0000-00-00": $passport_expiry_date;
            $_SESSION["passport_country"] = empty($passport_country) ? "NULL": $passport_country;
            $_SESSION["family_coming_to_canada"] = $family_coming_to_canada;
            $_SESSION["highest_education"] = $highest_education;
            $_SESSION["occupation"] = $occupation;
            $_SESSION["years_of_experience"] = $years_of_experience;
            $_SESSION["canadian_work_experience"] = $canadian_work_experience;
            $_SESSION["canadian_education"] = $canadian_education;
            $_SESSION["refused_visa"] = $refused_visa;
            $_SESSION["involved_in_genocide"] = $involved_in_genocide;
            $_SESSION["criminal_offence"] = $criminal_offence;
            $_SESSION["background_check_details"] = $background_check_details;
            $_SESSION["other_information"] = $other_information;
            $_SESSION["package"] = implode(",", $package["package"]);

            /**
             * Redirect to the terms page:
             **/
            header("Location:" . BASE_URL . "/terms");
        }
    }

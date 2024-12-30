<?php

namespace App\Helpers;

class PhoneHelper
{
    /**
     * Utility for validating international phone numbers using the 'libphonenumber-for-php' library
     * https://github.com/giggsey/libphonenumber-for-php
     */
    public static function validatePhone($country_code, $phone_number)
    {
        //get PhoneNumberUtil instance
        $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();

        //strip off non-numeric character and explicitly prepend a "+" char to the code
        $code_with_plus_sign = "+" . preg_replace("/[^0-9]/", "", $country_code);

        $validNumber = false;
        try {
            //Parse the phone number prepended with the country code
            $phoneNumberObject = $phoneUtil->parse($code_with_plus_sign . $phone_number, null, null, true);

            $validNumber = $phoneUtil->isValidNumber($phoneNumberObject);
        } catch (\Exception $e) {
            //die('Exception:' . $e);
            // echo 'Exception:' . $e;
            // exit;
            return array("isValid" => $validNumber, "error" => $e . "\nMake sure your phone number is valid.");
        }

        return array("isValid" => $validNumber, "error" => "Make sure your phone number is valid.");
    }
}

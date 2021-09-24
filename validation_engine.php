<?php
/**
 * Validation Rules
 */
function validation_rules()
{
    $allRules = array(
    "required"=>array(
        "regex"=>"none",
        "alertText"=>"This field is required !",
        "alertTextCheckboxMultiple"=>" Please select an option",
        "alertTextCheckboxe"=>" This checkbox is required"),
    "length"=>array(
        "regex"=>"none",
        "alertText"=>" Between ",
        "alertText2"=>" and ",
        "alertText3"=> " characters"),
    "maxCheckbox"=>array(
        "regex"=>"none",
        "alertText"=>" Checks allowed Exceeded"),
    "minCheckbox"=>array(
        "regex"=>"none",
        "alertText"=>" Please select atleast ",
        "alertText2"=>" option(s)"),
    "confirm"=>array(
        "regex"=>"none",
        "alertText"=>" Your field is not matching"),
    "imageFormat"=>array(
        "regex"=>"/\.(jpg|jpeg|png|gif)$/i",
        "alertText"=>" Only jpg, gif and png allowed"),
    "selectFile"=>array(
        "regex"=>"/^.{2,}$/",
        "alertText"=>" Please select a file to upload"),
    "telephone"=>array(
        "regex"=>"/^[0-9\-\(\)\ ]+$/",
        "alertText"=>" Invalid phone number"),
    "email"=>array(
        "regex"=>"/^[a-zA-Z0-9_\.\-]+\@([a-zA-Z0-9\-]+\.)+[a-zA-Z0-9]{2,4}$/",
        "alertText"=>" Invalid email address"),
    "date"=>array(
         "regex"=>"/^[0-9]{4}\-\[0-9]{1,2}\-\[0-9]{1,2}$/",
         "alertText"=>" Invalid date, must be in YYYY-MM-DD format"),
    "date_MMDDYYYY"=>array(
        "regex"=>"/^[0-9]{1,2}\\/[0-9]{1,2}\\/[0-9]{4}$/",
        "alertText"=>" Must be MM/DD/YYYY format"),
    "onlyNumber"=>array(
        "regex"=>"/^[0-9\ ]+$/",
        "alertText"=>" Numbers only"),
    "price"=>array(
        "regex"=>"/^[1-9][0-9]{0,}[.]{0,1}[0-9]{0,}$/",
        "alertText"=>" Numbers only"),
    "noSpecialCaracters"=>array(
        "regex"=>"/^[0-9a-zA-Z]+$/",
        "alertText"=>" No special character allowed"),
    "onlyLetter"=>array(
        "regex"=>"/^[a-zA-Z\ \']+$/",
        "alertText"=>" Letters only")
    );
    //var_dump('<pre>', $allRules['email']);

    return $allRules;

}//end func......




/** Server Side Validation
 *  $var_ar = multi-dimentional array with following components at its 2nd level:
 *  array(display title, key, field type, validations required)
 */
function validate_x($var_ar, $type='post')
{
    #/ Regex Rules Definition
    $allRules = validation_rules();


    $in_var = $_POST;
    if($type=='get')
    $in_var = $_GET;

    $ret = array();
    foreach($var_ar as $v)
    {
        $v_a = explode(',', $v[3]);
        foreach($v_a as $v2)
        {
            $v2 = trim($v2);
            $title = trim($v[0]);
            //var_dump($title);echo '<br /><br />';

            $regex = $allRules[$v2]['regex'];
            if($regex=='none')
            {
                switch($v2)
                {
                    case 'required':
                    if( (!isset($_POST[$v[1]])) || (empty($_POST[$v[1]])) )
                    $ret[] = "* {$title}:&nbsp; {$allRules[$v2]['alertText']}";
                    break;
                }
            }
            else
            {
                $regex_test = preg_match($regex, $_POST[$v[1]]);
                if(!$regex_test)
                {
                    $ret[] = "* {$title}:&nbsp; {$allRules[$v2]['alertText']}";
                }
            }
        }
    }//end foreach....

    return implode('<br />', $ret);

}//end func.....
?>
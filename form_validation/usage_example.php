<?php

#/ Process Post
if(isset($_POST['first_name']))
{
    #/ Check Attempts
    include_once('../includes/check_attempts.php');
    if(check_attempts(3)==false){
    update_attempt_counts(); redirect_me($seo_tag);
    }

    ##/ Validate Fields
    include_once('../includes/form_validator.php');
    $form_v = new Valitron\Validator($_POST);

    $rules = [
    'required' => [['first_name'], ['last_name'], ['email_add'], ['pass_w'], ['c_pass_w'], ['secret_question_id'], ['secret_answer'], ['phone_number'], ['country_code'], ['vercode']],
    'lengthMin' => [['pass_w', 7], ['c_pass_w', 7]],
    'lengthMax' => [['first_name', 60], ['last_name', 60], ['email_add', 150], ['pass_w', 20], ['c_pass_w', 20], ['secret_answer', 190], ['company_name', 100], ['phone_number', 20], ['city', 150], ['country_code', 3], ['vercode', 10]],
    'email' => [['email_add']],
    'equals' => [['c_pass_w', 'pass_w']],
    ];

    $form_v->labels(array(
    'email_add' => 'Email Address',
    'pass_w' => 'Password',
    'c_pass_w' => 'Confirm Password',
    'secret_question_id' => 'Secret Question',
    'country_code' => 'Country',
    'vercode' => 'Security Code',
    ));

    $form_v->rules($rules);
    $form_v->validate();
    $fv_errors = $form_v->errors();

    //var_dump("<pre>", $_POST, $fv_errors); die();
    #-


    #/ Check Captcha Code
    if( (empty($_SESSION['cap_code'])) || (empty($_POST['vercode'])) || ($_SESSION['cap_code']!=$_POST['vercode']) )
    {
        $fv_errors[] = array('The Security Code you entered does not match the one given in the image! Please try again.');
    }

    #/ Check if Email Add exists
    if(!is_array($fv_errors) || empty($fv_errors) || (count($fv_errors)<=0))
    {
        $chk_user = mysql_exec("SELECT email_add FROM users WHERE email_add='{$_POST['email_add']}'", 'single');
        if(!empty($chk_user))
        {
            $fv_errors[] = array('This Email Address is already used, please try a different one!');
        }
    }


    if(!is_array($fv_errors) || empty($fv_errors) || (count($fv_errors)<=0))
    {
        ////process on no validation errors

        exit;
    }
    else
    {
        $fv_msg = 'Please clear the following Error(s):<br /><br />- '; $fv_msg_ar=array();
        foreach($fv_errors as $fv_k=>$fv_v){$fv_msg_ar = array_merge($fv_msg_ar, $fv_v);}
        $fv_msg.=@implode('<br />- ', $fv_msg_ar);

        $_SESSION["LDB_MSG_GLOBAL"] = array(false, $fv_msg);
        update_attempt_counts();
    }

}//end if form post..

/////////////////////////////////////////////////////////////////////
?>
<?php
/** re_post_form
 *  Reproduce Form with hidden fields, from an array of key=>value pairs
 *  $var = POST or GET or any other array
 *  $ignore = fields to ignore / not built in url
 *
 *  USAGE:
 *  =====================
    echo '<form id="re_form" name="re_form" action="'.$consts['DOC_ROOT'].'Formal-Request.html" method="post">';
    echo re_post_form($_POST);
    echo '</form><br /><br />';
    echo '<script>document.getElementById(\'re_form\').submit();</script>';
 */
function re_post_form($var, $ignore=array('submit'))
{
    $input_hiddens = '';

    foreach($var as $k=>$v)
    {
        $k = format_str($k);
        if(in_array($k, $ignore)!=false) continue;

        if(is_array($v))
        {
            foreach($v as $k2=>$v2){
            $input_hiddens .= "<input type='hidden' name='{$k}[{$k2}]' value='{$v2}' />\r\n";
            }
        }
        else{
        $input_hiddens .= "<input type='hidden' name='{$k}' value='{$v}' />\r\n";
        }
    }

    return $input_hiddens;
}//end func.....



/** re_get_form
 *  $var = POST or GET or any other array
 *  $ignore = fields to ignore / not built in url
 */
function re_get_form($var, $ignore=array())
{
    $form = '';
    foreach($var as $k=>$v)
    {
        if(!in_array($k, $ignore)){
        //$form .= "{$k}={$v}&";
        $form .= http_build_query(array($k=>$v)).'&';
        }
    }

    $form = str_replace("'", '&#39;', $form);
    $form = str_replace("#", '', $form);
    $form = preg_replace("/[\n\r]/", 'Xnl2nlX', $form);

    return $form;
}//end func.....
?>
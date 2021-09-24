<?php
/** Post data to an external form
*/
function post_form($url, $method, $data=null, $headers=array('Content-Type: text/html; charset=utf-8'), $response_hdr=false, $follow_loc=false, $debug=false)
{
    session_write_close();
    $session_cookie = session_name()."=".session_id()."; path=".session_save_path();


    $handle = curl_init();
    curl_setopt($handle, CURLOPT_URL, $url);

    if($headers!==false)
    curl_setopt($handle, CURLOPT_HTTPHEADER, $headers);

    curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($handle, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($handle, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($handle, CURLOPT_COOKIEFILE, $session_cookie);
    curl_setopt($handle, CURLOPT_COOKIEJAR, $session_cookie);

    curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 180);
    curl_setopt($handle, CURLOPT_TIMEOUT, 180);

    if($follow_loc!==false)
    curl_setopt($handle, CURLOPT_FOLLOWLOCATION, true);

    if($response_hdr)
    curl_setopt($handle, CURLOPT_HEADER, true);

    if($debug!=false){
    $fh = fopen('_curl_log.t3', 'w');
    curl_setopt($handle, CURLOPT_VERBOSE, 1);
    curl_setopt($handle, CURLOPT_STDERR, $fh);
    }

    switch ( strtoupper($method) )
    {
        case 'POST':
        curl_setopt($handle, CURLOPT_POST, true);
        curl_setopt($handle, CURLOPT_POSTFIELDS, $data );
        break;

        case 'PUT':
        curl_setopt($handle, CURLOPT_PUT, true);
        $file_handle = fopen($data, 'r');
        curl_setopt($handle, CURLOPT_INFILE, $file_handle);
        break;

        case 'DELETE':
        curl_setopt($handle, CURLOPT_CUSTOMREQUEST, 'DELETE');
        break;
    }

    $response = curl_exec($handle);

    if($response_hdr)
    $info = curl_getinfo($handle);

    $code = curl_getinfo($handle, CURLINFO_HTTP_CODE);
    curl_close($handle);

    $hdr = '';
    if($response_hdr)
    $hdr = substr($response, 0, $info['header_size']);

    return array( 'code' => $code, 'data' => $response, 'hdr'=>$hdr );

}//end func.....
?>
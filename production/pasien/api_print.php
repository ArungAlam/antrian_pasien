<?php 
function CallAPI($method, $api, $data)
{

    $url  = $api;
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    switch ($method) {
        case "GET":
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
            break;
        case "POST":
				 $data = http_build_query($data);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
            break;
        case "DELETE":
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            break;
    }
    $response = curl_exec($curl);
    $data     = $response;

    /* Check for 404 (file not found). */
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    // Check the HTTP Status code
    switch ($httpCode) {
        case 201:
            $error_status = "201: Success";
            return ($data);
            break;
        case 404:
            $error_status = "404: API Not found";
            break;
        case 500:
            $error_status = "500: servers replied with an error.";
            break;
        case 502:
            $error_status = "502: servers may be down or being upgraded. Hopefully they'll be OK soon!";
            break;
        case 503:
            $error_status = "503: service unavailable. Hopefully they'll be OK soon!";
            break;
        default:
            $error_status = "Undocumented error: " . $httpCode . " : " . curl_error($curl);
            break;
    }
    curl_close($curl);
    //echo $error_status;
	//echo $url;
    // die();
}
function my_ip() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}
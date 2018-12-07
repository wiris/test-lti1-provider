<?php
// https://www.imsglobal.org/specs/ltimemv1p0/specification-3
include "oauthsign.php";

$a=file_get_contents(dirname(__FILE__)."/outcome.template.xml");
$url = $_POST["url"];
//$url  = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
//$url .= $_SERVER['SERVER_NAME'];
//$url .= $_SERVER['REQUEST_URI'];
//$url=dirname($url)."/echo.php";
echo "URL".$url."<br/>";
$consumerKey = $_POST["consumerKey"];

$params = array(
    "oauth_consumer_key"=>$consumerKey,
    "oauth_timestamp"=>"".time(),
    "oauth_nonce"=>"".rand(0,1000000), 
    "oauth_signature_method"=>"HMAC-SHA1",
    "oauth_version"=>"1.0"
);

$authsign = sign("GET","secret&",$url,$params);
$params["oauth_signature"] = encode($authsign);

$authstr="";
foreach ($params as $key=>$value) {
    if (strlen($authstr)>0) $authstr.=", ";
    $authstr .= $key . '="' . $value . '"';
}

// Moodle only uses oauth_consumer_key and oauth_signature
$request_headers=array('Authorization: OAuth '.$authstr,
    "Accept: application/vnd.ims.lis.v2.membershipcontainer+json");
var_dump($request_headers);

$rq = curl_init($url);
curl_setopt($rq, CURLOPT_HTTPHEADER, $request_headers);

curl_setopt($rq, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($rq, CURLOPT_SSL_VERIFYPEER, 0);

$r = curl_exec($rq);
echo var_dump($r);
echo curl_error($rq);
curl_close($rq);

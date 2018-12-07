<?php
// https://www.imsglobal.org/specs/ltiv1p1/implementation-guide#toc-26
include "lib/oauthsign.php";

$a=file_get_contents(dirname(__FILE__)."/outcome.template.xml");
$url = $_POST["url"];
//$url  = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
//$url .= $_SERVER['SERVER_NAME'];
//$url .= $_SERVER['REQUEST_URI'];
//$url=dirname($url)."/echo.php";
echo "URL".$url."<br/>";
$sourceId = $_POST["sourceId"];
$score = $_POST["score"];
$consumerKey = $_POST["consumerKey"];
$a=str_replace("%SOURCEID%",$sourceId,$a);
$a=str_replace("%SCORE%",$score,$a);

$rq = curl_init($url);

$signature = base64_encode(sha1($a, true));

$params = array(
    "oauth_consumer_key"=>$consumerKey,
    "oauth_body_hash"=>($signature), 
    "oauth_timestamp"=>"".time(),
    "oauth_nonce"=>"".rand(0,1000000), 
    "oauth_signature_method"=>"HMAC-SHA1",
    "oauth_version"=>"1.0"
);

$authsign = sign("POST","secret&",$url,$params);
$params["oauth_signature"] = encode($authsign);
$params["oauth_body_hash"] = encode($signature); // wierd!

$authstr="";
foreach ($params as $key=>$value) {
    if (strlen($authstr)>0) $authstr.=", ";
    $authstr .= $key . '="' . $value . '"';
}

// Moodle only uses oauth_consumer_key and oauth_signature
$request_headers=array('Authorization: OAuth '.$authstr,
   'content-length: '.strlen($a),
   'content-type: application/xml'
);
var_dump($request_headers);
curl_setopt($rq, CURLOPT_HTTPHEADER, $request_headers);

curl_setopt($rq,CURLOPT_POSTFIELDS,$a);
curl_setopt($rq, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($rq, CURLOPT_SSL_VERIFYPEER, 0);

$r = curl_exec($rq);
var_dump($r);
echo curl_error($rq);
curl_close($rq);

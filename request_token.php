<?php
// https://developer.twitter.com/en/docs/basics/authentication/guides/access-tokens.html

$requestTokenUrl = "https://api.twitter.com/oauth/request_token";
$consumerKey = "OqEqJeafRSF11jBMStrZz";
$params = array(
    "oauth_consumer_key"=>$consumerKey,
    "oauth_signature_method"=>"HMAC-SHA1",
    "oauth_timestamp"=>"1300228849", // this.getTimeStampAsString(),
    "oauth_nonce"=>"K7ny27JTpKVsTgdyLdDfmQQWVLERj2zAK5BslRsqyw", // this.getOnceAsString(),
    "oauth_version"=>"1.0",
    "oauth_callback"=>"http://myapp.com:3005/twitter/process_callback",
    //"oauth_token"=>"nnch734d00sl2jdk"
);


$postData = sign($requestTokenUrl,$params);

$rq = curl_init($requestTokenUrl);
curl_setopt($rq,CURLOPT_POSTFIELDS,$postData);
curl_setopt($rq, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($rq, CURLOPT_SSL_VERIFYPEER, 0);

$r = curl_exec($rq);

var_dump($r);
echo curl_error($rq);
// echo $postData;
curl_close($rq);


function sign($serviceUrl,$params) {
    $keys = array();
    foreach ($params as $key=>$_value) {
        $keys[] = $key;
    }
    asort($keys);
    $s = "";
    foreach ($keys as $key) {
        if (strlen($s)>0) $s.="&";
        $s.=$key;
        $s.="=";
        $s.=encode($params[$key]);
    }
    $tosign = "GET&".encode($serviceUrl)."&".encode($s);
    
    $pass1 = hash_hmac('sha1', $tosign, "kd94hf93k423kf44&pfkkdhi9sl3r4s00", true);
    $pass = base64_encode($pass1);

    $s.="&oauth_signature=".$pass;
    return $s;
}

function encode($s) {
    return urlencode($s);
}

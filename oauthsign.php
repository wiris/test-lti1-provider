<?php
function sign($method,$secret,$serviceUrl,$params) {
    $keys = array();
    foreach ($params as $key=>$_value) {
        $keys[] = $key;
    }
    asort($keys);
    $s = "";
    foreach ($keys as $key) {
        if ($key!="oauth_signature") {
            if (strlen($s)>0) $s.="&";
            $s.=$key;
            $s.="=";
            $s.=encode($params[$key]);
            //echo $key . "=" . encode($params[$key]) . "<br/>";
        }
    }
    $tosign = $method."&".encode($serviceUrl)."&".encode($s);
            
    // DEBUG
    $s = fopen("C:\\soft\\Apache2.4\\htdocs\\moodle35\\output3.txt","wt");
    fprintf($s,"%s\r\n",$tosign);
    fprintf($s,"%s\r\n",$secret);
    fclose($s);

    $pass1 = hash_hmac('sha1', $tosign, $secret, true);
    $pass = base64_encode($pass1);

    return $pass;
}

function encode($s) {
    $r="";
    for ($i=0;$i<strlen($s);$i++) {
        $c = $s[$i];
        if ($c>='A' && $c<='Z' || $c>='a' && $c<='z' || $c>='0' && $c<='9' || $c=='-' || $c=='_' || $c=='.' || $c=='~') {
            $r .= $c;
        } else {
            $r .= "%".strtoupper(dechex(ord($c)));
        }
    }
    //echo $s." -> ".$r;
    //echo "<br/>";
    return $r;
}
?>
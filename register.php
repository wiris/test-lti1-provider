<!DOCTYPE html>
<html>
    <header>
        <?php
            function guidv4()
            {
                $data = openssl_random_pseudo_bytes(16);
                $data[6] = chr(ord($data[6]) & 0x0f | 0x40); // set version to 0100
                $data[8] = chr(ord($data[8]) & 0x3f | 0x80); // set bits 6-7 to 10
                return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
            }

            $tc_profile_url = $_POST["tc_profile_url"];
            $i = strpos($tc_profile_url,"?");
            if ($i) {
                $tc_profile_url = substr($tc_profile_url,0,$i);
            }
            $tc_profile_url .= "?lti_version=LTI-2p0";
            $rq = curl_init($tc_profile_url);
            curl_setopt($rq, CURLOPT_RETURNTRANSFER, 1);
            $r = curl_exec($rq);
            $tc_profile = json_decode($r);

            $return_url = $_POST["launch_presentation_return_url"];
            parse_str(parse_url($return_url, PHP_URL_QUERY),$return_url_params);

            $toolproxy = false;
            foreach ($tc_profile->service_offered as $services) {
                if ($services->format[0]=="application/vnd.ims.lti.v2.toolproxy+json") {
                    $toolproxy = $services;
                }
            }

            if ($toolproxy) {
                echo $toolproxy->endpoint;
            }
        ?>
    </header>
    <body>
        Hello world!
        <?php
            //var_dump($tc_profile->service_offered);
        ?>
        <br/>
        <?php echo $tc_profile->guid; ?>
        <br/>
        <form action="<?php echo $tc_profile_url;?>">
            <input type="hidden" name="lti_version" value="LTI-2p0">
            <input type="submit" value="Get profile">
            
        </form>
        </p>
        <form action="<?php echo $return_url;?>">
            <input type="hidden" name="status" value="success">
            <input type="hidden" name="tool_proxy_guid" value="<?php echo $tc_profile->guid;?>">
            <input type="hidden" name="id" value="<?php echo $return_url_params["id"];?>">
            <input type="hidden" name="sesskey" value="<?php echo $return_url_params["sesskey"];?>">
            <input type="submit" value="Return & save">
        </form>
    </body>
</html>
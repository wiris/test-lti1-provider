<html>
    <header>
    </header>
    <body>
    <?php
        // This file is the entry point for the LTI 1 launch
        include "lib/oauthsign.php";

        // Testing signature
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
        $oauth_signature = $_POST["oauth_signature"];
        if ($oauth_signature!=sign("POST","secret&",$actual_link,$_POST)) {
            echo "Invalid signature!!<br/>";
            die;
        }

        // Get some parameters
        $back_link = $_POST["launch_presentation_return_url"];
        $oauth_consumer_key = $_POST["oauth_consumer_key"];
        $lis_person_name_full = $_POST["lis_person_name_full"];
        $lis_outcome_service_url = $_POST["lis_outcome_service_url"];
        $lis_result_sourcedid = $_POST["lis_result_sourcedid"];
        $custom_context_memberships_url = $_POST["custom_context_memberships_url"];
        $lis_result_sourcedid = str_replace('"','&quot;',$lis_result_sourcedid);
    ?>
    Hello <span><?php echo $lis_person_name_full; ?></span>
    <br/>
    <a href="<?php echo $back_link; ?>">back</a>
    <br/>
    <br/>
    <form action="service-calls/outcome_service.php" method="POST">
        <input type="hidden" name="url" value="<?php echo $lis_outcome_service_url; ?>"/>
        <input type="hidden" name="sourceId" value="<?php echo $lis_result_sourcedid; ?>"/>
        <input type="hidden" name="consumerKey" value="<?php echo $oauth_consumer_key; ?>"/>
        <input type="hidden" name="score" value="<?php echo rand(0,100)/100.0; ?>"/>
        <input type="submit" value="Score" > (press button to send score message, <a href="https://www.imsglobal.org/specs/ltiv1p1/implementation-guide#toc-6">LTI Basic Outcomes Service</a>)
    </form>
    <form action="service-calls/membership.php" method="POST">
        <input type="hidden" name="url" value="<?php echo $custom_context_memberships_url; ?>"/>
        <input type="hidden" name="consumerKey" value="<?php echo $oauth_consumer_key; ?>"/>
        <input type="submit" value="Membership" > (press button to get roster, <a href="https://www.imsglobal.org/lti/model/uml/purl.imsglobal.org/vocab/lis/v2/mm/LISMembershipContainer/service.html#Service_Methods">LTI Names and Role Provisioning Services</a>)
    </form>
    </body>
</html>

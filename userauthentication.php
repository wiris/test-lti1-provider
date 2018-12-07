<!DOCTYPE html>
<body>
    <header>
    </header>
    <body>
        <?php
            echo $_POST["oauth_token"];
            echo "<br />";
            echo $_POST["oauth_callback"];
            echo "<br />encode=";
            echo encode("$");
        ?>
    </body>
</body>
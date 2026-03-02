<html
    lang="<?= CONF_SITE_LANG; ?>"
    class="light-style layout-wide customizer-hide"
    dir="ltr"
    data-theme="theme-default"
    data-assets-path="<?= url("/common/assets/"); ?>"
    data-template="vertical-menu-template-free"
    data-style="light">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
        
        <?= $head; ?>
       
        <meta name="author" content="Yelloweb - Tecnologia" />
        <meta name="robots" content="index, follow">
    
        <!-- Favicon -->
        <link rel="icon" type="image/x-icon" href="<?= url("/common/assets/img/illustrations/caminho.png"); ?>" />
         

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&ampdisplay=swap"
        rel="stylesheet" />
        <link rel="stylesheet" href="<?php echo url("/vendor/twbs/bootstrap/dist/css/bootstrap.min.css"); ?>" />

        <?= $this->section("styles"); ?>
    </head>
    <body> 
        <?= $this->section("content"); ?>

        <!-- JS FILES -->
        <script src="<?= url("/common/jquery.js"); ?>"></script>
        <script src="<?= url("/common/script.js?v=" . time()); ?>"></script>
        <script src="<?= url("/vendor/twbs/bootstrap/dist/js/bootstrap.bundle.min.js"); ?>"></script>
        <script>
            (function() {
                if (typeof console === "object") {
                    console.log("%cSTOP!", "color:red;font-size:xx-large;font-weight:bold;");
                    console.log("%cYELLOWEB ALERT", "color:yellow;font-size:x-large;font-weight:bold;");
                    console.log(
                    "%cThis is a browser feature intended for developers. Do not enter or paste code which you don't understand. It may allow attackers to steal your information or impersonate you.\nSee https://en.wikipedia.org/wiki/Self-XSS for more details",
                    "font-size:large;"
                    );
                }
            })();
        </script>
        <?= $this->section("scripts"); ?>
    </body>
</html>
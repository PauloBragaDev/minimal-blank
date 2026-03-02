<?php
    use Dotenv\Dotenv;

    $dotenv = Dotenv::createImmutable(dirname(__DIR__, 2));
    $dotenv->load();
    
    define("CONF_ENV", $_ENV['APP_ENV'] ?? 'production');
    define("CONF_DEBUG", filter_var($_ENV['APP_DEBUG'] ?? false, FILTER_VALIDATE_BOOLEAN));


    /** DATABASE **/
    define("CONF_DB_HOST", $_ENV['CONF_DB_HOST']);
    define("CONF_DB_HOST_DEV", $_ENV['CONF_DB_HOST']);
    define("CONF_DB_USER", $_ENV['CONF_DB_USER']);
    define("CONF_DB_PASS", $_ENV['CONF_DB_PASS']);
    define("CONF_DB_NAME", $_ENV['CONF_DB_NAME']);


    /**
     * PROJECT URLs
     */
    define("CONF_URL_BASE", $_ENV['CONF_URL_BASE']);
    define("CONF_URL_DEV", $_ENV['CONF_URL_DEV']);

    /**
     * SITE
     */
    define("CONF_SITE_NAME",  "Template Blank");
    define("CONF_SITE_TITLE", "Template Blank");
    define("CONF_SITE_DESC",  "Template Blank");
    define("CONF_SITE_LANG",  "pt_BR");
    define("CONF_SITE_DOMAIN", CONF_URL_BASE);

    /**
     * SOCIAL
     */
    define("CONF_SOCIAL_TWITTER_CREATOR", "xxx");
    define("CONF_SOCIAL_TWITTER_PUBLISHER", "xx");
    define("CONF_SOCIAL_FACEBOOK_APP", "xx");
    define("CONF_SOCIAL_FACEBOOK_PAGE", "xx");
    define("CONF_SOCIAL_FACEBOOK_AUTHOR", "xx");
    define("CONF_SOCIAL_GOOGLE_PAGE", "xx");
    define("CONF_SOCIAL_GOOGLE_AUTHOR", "xx");
    define("CONF_SOCIAL_INSTAGRAM_PAGE", "xx");
    define("CONF_SOCIAL_FACEBOOK_PAGES", "xx");
    define("CONF_SOCIAL_LINKEDIN_PAGE", "xx");
    define("CONF_SOCIAL_YOUTUBE_PAGE", "xx");

    /**
     * DATES
     */
    define("CONF_DATE_BR", "d/m/Y");
    define("CONF_DATE_APP", "Y-m-d H:i:s");

    /**
     * PASSWORD
     */
    define("CONF_PASSWD_MIN_LEN", 1);
    define("CONF_PASSWD_MAX_LEN", 999);
    define("CONF_PASSWD_ALGO", PASSWORD_DEFAULT);
    define("CONF_PASSWD_OPTION", ["cost" => 10]);

    /**
     * MESSAGE
     */
    define("CONF_MESSAGE_CLASS", "alert alert-");
    define("CONF_MESSAGE_INFO", "info");
    define("CONF_MESSAGE_SUCCESS", "success");
    define("CONF_MESSAGE_WARNING", "warning");
    define("CONF_MESSAGE_ERROR", "danger");

    /**
     * VIEW
     */
    define("CONF_VIEW_PATH", __DIR__ . "/../../common/views");
    define("CONF_VIEW_EXT", "php");
    define("CONF_VIEW_THEME", "web");
    define("CONF_VIEW_ADMIN", "admin");
    define("CONF_VIEW_APP", "app");

    /**
     * UPLOAD
     */
    define("CONF_UPLOAD_DIR", "storage");
    define("CONF_UPLOAD_IMAGE_DIR", "products");
    define("CONF_UPLOAD_FILE_DIR", "files");
    define("CONF_UPLOAD_MEDIA_DIR", "media");
    define("CONF_SRC_IMG", "/common/img");

    /**
     * IMAGES
     */
    define("CONF_IMAGE_CACHE", CONF_UPLOAD_DIR . "/" . CONF_UPLOAD_IMAGE_DIR . "/cache");
    define("CONF_IMAGE_SIZE", 1280);
    define("CONF_IMAGE_QUALITY", ["jpg" => 75, "png" => 5]);

    /**
     * MAIL
     */
    define("CONF_MAIL_HOST", "smtp.hostinger.com");
    define("CONF_MAIL_PORT", "465");
    define("CONF_MAIL_USER", "noreply@pautly.com.br");
    define("CONF_MAIL_PASS", "your_password");
    define("CONF_MAIL_SENDER", ["name" => CONF_SITE_NAME, "address" => "noreply@pautly.com.br"]);
    define("CONF_MAIL_SUPPORT", "noreply@pautly.com.br");
    define("CONF_MAIL_OPTION_LANG", "br");
    define("CONF_MAIL_OPTION_HTML", true);
    define("CONF_MAIL_OPTION_AUTH", true);
    define("CONF_MAIL_OPTION_CHARSET", "utf-8");


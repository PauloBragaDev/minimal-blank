<?php

/**
 * ####################
 * ###   VALIDATE   ###
 * ####################
 */

/**
 * @param string $email
 * @return bool
 */
function is_email(string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * @param string $password
 * @return bool
 */
function is_passwd(string $password): bool
{
    if (password_get_info($password)['algo'] || (mb_strlen($password) >= CONF_PASSWD_MIN_LEN && mb_strlen($password) <= CONF_PASSWD_MAX_LEN)) {
        return true;
    }

    return false;
}

/**
 * ##################
 * ###   STRING   ###
 * ##################
 */

/**
 * @param string $string
 * @return string
 */
function str_slug(string $string): string
{
    $string = filter_var(mb_strtolower($string), FILTER_SANITIZE_STRIPPED);
    $formats = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜüÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿRr"!@#$%&*()_-+={[}]/?;:.,\\\'<>°ºª';
    $replace = 'aaaaaaaceeeeiiiidnoooooouuuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr                                 ';

    $slug = str_replace(["-----", "----", "---", "--"], "-",
        str_replace(" ", "-",
            trim(strtr(utf8_decode($string), utf8_decode($formats), $replace))
        )
    );
    return $slug;
}

/**
 * @param string $string
 * @return string
 */
function str_studly_case(string $string): string
{
    $string = str_slug($string);
    $studlyCase = str_replace(" ", "",
        mb_convert_case(str_replace("-", " ", $string), MB_CASE_TITLE)
    );

    return $studlyCase;
}

/**
 * @param string $string
 * @return string
 */
function str_camel_case(string $string): string
{
    return lcfirst(str_studly_case($string));
}

/**
 * @param string $string
 * @return string
 */
function str_title(string $string): string
{
    return mb_convert_case(filter_var($string, FILTER_SANITIZE_SPECIAL_CHARS), MB_CASE_TITLE);
}

/**
 * @param string $string
 * @param int $limit
 * @param string $pointer
 * @return string
 */
function str_limit_words(string $string, int $limit, string $pointer = "..."): string
{
    $string = trim($string);
    // $string = trim(filter_var($string, FILTER_SANITIZE_SPECIAL_CHARS));
    $arrWords = explode(" ", $string);
    $numWords = count($arrWords);

    if ($numWords < $limit) {
        return $string;
    }

    $words = implode(" ", array_slice($arrWords, 0, $limit));
    return "{$words}{$pointer}";
}

/**
 * @param string $string
 * @param int $limit
 * @param string $pointer
 * @return string
 */
function str_limit_chars(string $string, int $limit, string $pointer = "..."): string
{
    $string = trim(filter_var($string, FILTER_SANITIZE_SPECIAL_CHARS));
    if (mb_strlen($string) <= $limit) {
        return $string;
    }

    $chars = mb_substr($string, 0, mb_strrpos(mb_substr($string, 0, $limit), " "));
    return "{$chars}{$pointer}";
}

/**
 * ###############
 * ###   URL   ###
 * ###############
 */

/**
 * @param string $path
 * @return string
 */
function url(string $path = null): string
{
    if (strpos($_SERVER['HTTP_HOST'], "localhost") !== false) {
        if ($path) {
            return CONF_URL_DEV . "/" . ($path[0] == "/" ? mb_substr($path, 1) : $path);
        }
        return CONF_URL_DEV;
    }

    if ($path) {
        return CONF_URL_BASE . "/" . ($path[0] == "/" ? mb_substr($path, 1) : $path);
    }

    return CONF_URL_BASE;
}

/**
 * @param string $path
 * @return string
 */
function url_back(): string
{
    return ($_SERVER['HTTP_REFERER'] ?? url());
}

/**
 * @param string $url
 */
function redirect(string $url): void
{
    header("HTTP/1.1 302 Redirect");
    if (filter_var($url, FILTER_VALIDATE_URL)) {
        header("Location: {$url}");
        exit;
    }

    if(filter_input(INPUT_GET, "route", FILTER_DEFAULT) != $url){
        $location = url($url);
        header("Location: {$location}");
        exit;
    }
}


/**
 * #################
 * ###   ASSETS  ###
 * #################
 */

 /**
 * @param string|null $path
 * @return string
 */
function imgsource(string $path = null): string
{
    if (strpos($_SERVER['HTTP_HOST'], "localhost")) {
        if ($path) {
            return CONF_URL_DEV . "/" . CONF_SRC_IMG. "/".($path[0] == "/" ? mb_substr($path, 1) : $path);
        }
        return CONF_URL_DEV;
    }

    if ($path) {
        return CONF_URL_BASE . "/" . CONF_SRC_IMG. "/".($path[0] == "/" ? mb_substr($path, 1) : $path);
    }

    return CONF_URL_BASE;
}

 /**
 * @param string|null $path
 * @param int|null $index
 * @param bool $open
 * @return string|null
 */
function activeClass(string $path = null, int $index = 3, bool $open = false): ?string
{
    $explode = explode("/", $_SERVER["REQUEST_URI"]);
    if($explode[$index] == $path){
        if($open){
            return "active open";
        }
        return "active";
    } else {
        return null;
    }                             
}

/**
 * DISPLAY HOUR
 * 
 * @param string!null $hour
 * @param int|null descont
 * @return string 
 */
function displayHour(?string $hour, ?int $descont = 3): string 
{
    if(empty($hour)){
        return "--/--/---- --:--";
    }
    
    return date("d/m/Y H:i", strtotime("{$hour} - {$descont} hours"));
}

/**
 * ROLE - PERMISSIONS
 * 
 * @param string!null $profile
 * @return string 
 */
function roleProfile(?string $profile): string 
{
    if(empty($profile)){
        return "------";
    }

    switch ($profile) {
        case 'admin':
            $role = '<i class="ri-vip-crown-line text-danger"></i> Admin';
            break;

        case 'editor':
            $role = '<i class="ri-edit-box-line text-info"></i> Editor';
            break;
        
        default:
            $role = '<i class="ri-user-smile-line"></i> Leitor';
            break;
    }
    
    return $role;
}



/**
 * @param string|null $path
 * @return string
 */
function theme(string $path = null): string
{
    if ((strpos($_SERVER['HTTP_HOST'], "localhost") !== false)) {
        if ($path) {
            return CONF_URL_DEV . "/themes/" . CONF_VIEW_THEME . "/" . ($path[0] == "/" ? mb_substr($path, 1) : $path);
        }
        return CONF_URL_DEV. "/themes/" . CONF_VIEW_THEME;
    }

    if ($path) {
        return CONF_URL_BASE . "/themes/" . CONF_VIEW_THEME . "/" . ($path[0] == "/" ? mb_substr($path, 1) : $path);
    }

    return CONF_URL_BASE. "/themes/" . CONF_VIEW_THEME;
}

/**
 * @param string|null $path
 * @return string
 */
function themeAdm(string $path = null): string
{
    if ((strpos($_SERVER['HTTP_HOST'], "localhost") !== false)) {
        if ($path) {
            return CONF_URL_DEV . "/themes/" . CONF_VIEW_ADMIN . "/" . ($path[0] == "/" ? mb_substr($path, 1) : $path);
        }
        return CONF_URL_DEV. "/themes/" . CONF_VIEW_ADMIN;
    }

    if ($path) {
        return CONF_URL_BASE . "/themes/" . CONF_VIEW_ADMIN . "/" . ($path[0] == "/" ? mb_substr($path, 1) : $path);
    }

    return CONF_URL_BASE. "/themes/" . CONF_VIEW_ADMIN;
}

/**
 * @param string|null $path
 * @return string
 */
function themeApp(string $path = null): string
{
    if ((strpos($_SERVER['HTTP_HOST'], "localhost") !== false)) {
        if ($path) {
            return CONF_URL_DEV . "/themes/" . CONF_VIEW_APP . "/" . ($path[0] == "/" ? mb_substr($path, 1) : $path);
        }
        return CONF_URL_DEV. "/themes/" . CONF_VIEW_APP;
    }

    if ($path) {
        return CONF_URL_BASE . "/themes/" . CONF_VIEW_APP . "/" . ($path[0] == "/" ? mb_substr($path, 1) : $path);
    }

    return CONF_URL_BASE. "/themes/" . CONF_VIEW_APP;
}

/**
 * @param string $image
 * @param int $width
 * @param int|null $height
 * @return string
 */
function image(string $image, int $width, int $height = null) :string
{
    return url()."/".(new \Source\Support\Thumb())->make($image, $width, $height);
}

/**
 * ################
 * ###   DATE   ###
 * ################
 */

/**
 * @param string $date
 * @param string $format
 * @return string
 */
function date_fmt(string $date = "now", string $format = "d/m/Y H\hi"): string
{
    return (new DateTime($date))->format($format);
}

/**
 * @param string $date
 * @return string|null
 */
function date_fmt_br(?string $date = null): ?string
{
    if(empty($date)){
        return null;
    }
    return (new DateTime($date))->format(CONF_DATE_BR);
}

/**
 * @param string $date
 * @return string
 */
function date_fmt_app(string $date = "now"): string
{
    return (new DateTime($date))->format(CONF_DATE_APP);
}

/**
 * @param string|null $date
 * @return bool
 */
function verifyValidate(?string $date): bool
{
    if(empty($date)){
        return false;
    }
    if(strtotime(date("Y-m-d")) >= strtotime($date)){
        return false;
    }
    return true;
}

/**
 * ####################
 * ###   PASSWORD   ###
 * ####################
 */

/**
 * @param string $password
 * @return string
 */
function passwd(string $password): string
{
    if (!empty(password_get_info($password)['algo'])) {
        return $password;
    }
    return password_hash($password, CONF_PASSWD_ALGO, CONF_PASSWD_OPTION);
}

/**
 * @param string $password
 * @param string $hash
 * @return bool
 */
function passwd_verify(string $password, string $hash): bool
{
    return password_verify($password, $hash);
}

/**
 * @param string $hash
 * @return bool
 */
function passwd_rehash(string $hash): bool
{
    return password_needs_rehash($hash, CONF_PASSWD_ALGO, CONF_PASSWD_OPTION);
}

/**
 * ###################
 * ###   REQUEST   ###
 * ###################
 */

/**
 * @return string
 */
function csrf_input(): string
{
    $session = new \Source\Infrastructure\Session\Session();
    $session->csrf();
    return "<input type='hidden' name='csrf' value='" . ($session->csrf_token ?? "") . "'/>";
}

/**
 * @param $request
 * @return bool
 */
function csrf_verify($request): bool
{
    $session = new \Source\Infrastructure\Session\Session();
    if (empty($session->csrf_token) || empty($request['csrf']) || $request['csrf'] != $session->csrf_token) {
        return false;
    }
    return true;
}

/**
 * @return null|string
 */
function flash(): ?string
{
    $session = new \Source\Infrastructure\Session\Session();
    if ($flash = $session->flash()) {
        echo $flash;
    }
    return null;
}

/**
 * @param string $key
 * @param int $limit
 * @param int $seconds
 * @return bool
 */
function request_limit(string $key, int $limit = 3, int $seconds = 60): bool
{
    $session = new \Source\Infrastructure\Session\Session();
    if ($session->has($key) && $session->$key->time >= time() && $session->$key->requests < $limit) {
        $session->set($key, [
            "time" => time() + $seconds,
            "requests" => $session->$key->requests + 1
        ]);
        return false;
    }

    if ($session->has($key) && $session->$key->time >= time() && $session->$key->requests >= $limit) {
        return true;
    }

    $session->set($key, [
        "time" => time() + $seconds,
        "requests" => 1
    ]);

    return false;
}

/**
 * @return null/float
 */
function price($price): float
{
    return number_format(floatval(str_replace(",", ".", $price)), 2, ".", "");
}

/**
 * @return null/string
 */
function phone($phone): string
{
    return str_replace(["(", ")", " ", "-"], "", $phone);
}

/**
 * @return null/string
 */
function clearCpf($cpf): string
{
    return str_replace([".", " ", "-"], "", $cpf);
}

/**
 * @param string $path
 * @param string $string
 * @return string|null
 */
function create_folder(string $path, string $string): ?string
{
    $string = str_slug($string);
    $dir = $path.'/'.$string;

    if(!file_exists($dir)){
        mkdir($dir, 0777, true);
    } else {
        return false;
    }

    return $string;
}

/**
 * @return string
 */
function gen_uuid() {
    return sprintf( '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        // 32 bits for "time_low"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

        // 16 bits for "time_mid"
        mt_rand( 0, 0xffff ),

        // 16 bits for "time_hi_and_version",
        // four most significant bits holds version number 4
        mt_rand( 0, 0x0fff ) | 0x4000,

        // 16 bits, 8 bits for "clk_seq_hi_res",
        // 8 bits for "clk_seq_low",
        // two most significant bits holds zero and one for variant DCE1.1
        mt_rand( 0, 0x3fff ) | 0x8000,

        // 48 bits for "node"
        mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
    );
}

/**
 * @return int
 */
function countItemsCart(?array $data = null): int
{
    if (empty($data)) {
        return 0;
    }

    $value = 0;
    foreach ($data as $item) {
        $value += $item['amount'];
    }

    return $value;
}

/**
 * @param string|null $search
 * @return string
 */
function str_search(?string $search): string
{
    if (!$search) {
        return "all";
    }

    $search = preg_replace("/[^a-zA-Z0-9@ -]/", "", $search);
    return (!empty($search) ? $search : "all");
}

/**
 * @param array $array
 * @param array $ignoreItems
 * @return bool
 */
function checkArray(array $array, array $ignoreItems = []): bool
{
    foreach ($array as $key => $value) {
        if (in_array($key, $ignoreItems, true)) {
            continue;
        }
        if (empty($value)) {
            return false;
        }
    }
    return true;
}

/*** 
 * FUNCÇÕES PARA O MAPA ASTRAL  
 ***/


/** 
 * DIAS DA SEMANA 
 * 
**/
function day_of_the_week($data)
{
    $diasemana_numero = date('w', strtotime($data));
    return $diasemana_numero == 0 ? 7 : $diasemana_numero;
}

/* ### CONTA DIAS ### */
function count_days($data)
{
    $now = date("Y-m-d");
    $data = date("Y-m-d", strtotime($data));

    $diferenca = strtotime($now) - strtotime($data);
    $dias = floor($diferenca / (60 * 60 * 24));

    return($dias);
}

/**
 * @param float|string $money
 * @return null|string
 */
function moneys($money): ?string
{
    return number_format(floatval($money), 2, ',', '.');
}

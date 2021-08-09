<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * funcoes do sistema
 */


/**
 * ###################
 * ###  STRINGS    ###
 * ###################
 */

/**
 * @param string $string
 * @param int $limit
 * @param string $pointer
 * @return string
 */
if (!function_exists("str_limit_words")) {

    function str_limit_words(string $string, int $limit, string $pointer = "..."): string
    {
        $string = trim(filter_var($string, FILTER_SANITIZE_SPECIAL_CHARS));
        $arrWords = explode(" ", $string);
        $numWords = count($arrWords);

        if ($numWords < $limit) {
            return $string;
        }

        $words = implode(" ", array_slice($arrWords, 0, $limit));
        return "{$words}{$pointer}";
    }
}


/**
 * ###################
 * ###  SESSIONS   ###
 * ###################
 */
if (!function_exists("showFlash")) {

    function showFlash()
    {
        if (isset($_SESSION['flash_msg'])) {
            $flash = $_SESSION['flash_msg'];
            unset($_SESSION['flash_msg']);
            return $flash;
        }
    }
}

/**
 * checa se esta logado
 */
if (!function_exists("is_logged")) {

    function is_logged()
    {
        $ci = get_instance();
        $logged_user = $ci->session->userdata['logged_user'];
        if (!$logged_user) {
            $ci->session->set_flashdata("flash_msg",
                createMessage("danger", "Você precisar logar antes de acessar essa área"));
            redirect("signin");
        }
        return $logged_user;
    }
}


/**
 * ###################
 * ### VALIDATIONS ###
 * ###################
 */

/**
 * @param string $password
 * @return bool
 */
if (!function_exists("is_passwd")) {

    function is_passwd(string $password): bool
    {
        if (password_get_info($password)['algo'] || (mb_strlen($password) >= CONF_PASSWD_MIN_LEN && mb_strlen($password) <= CONF_PASSWD_MAX_LEN)) {
            return true;
        }
        return false;
    }
}

/**
 * @param string $password
 * @return string
 */
if (!function_exists("passwd")) {

    function passwd(string $password): string
    {
        if (!empty(password_get_info($password)['algo'])) {
            return $password;
        }
        return password_hash($password, CONF_PASSWD_ALGO, CONF_PASSWD_OPTION);
    }
}

/**
 * @param string $password
 * @param string $hash
 * @return bool
 */
if (!function_exists("passwd_verify")) {

    function passwd_verify(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}

/**
 * @param string $hash
 * @return bool
 */
if (!function_exists("passwd_rehash")) {

    function passwd_rehash(string $hash): bool
    {
        return password_needs_rehash($hash, CONF_PASSWD_ALGO, CONF_PASSWD_OPTION);
    }
}

/**
 * ###################
 * ###   REQUEST   ###
 * ###################
 */
/*
 * @return string
 */

if (!function_exists("csrf_input")) {

    function csrf_input(): string
    {
        // adiciona o token ou cria pela primeira vez
        if (!empty($_SESSION['userdata']['csrf_token'])) {
            array_push($_SESSION['userdata']['csrf_token'], md5(uniqid(rand(), true)));
        } else {
            // add token na sessao pela primeira vez
            $_SESSION['userdata']['csrf_token'] = [md5(uniqid(rand(), true))];
        }
        // obtem o ultimo token
        $csrf_token = $_SESSION['userdata']['csrf_token'];
        $csrf_token = end($csrf_token);
        return "<input type='hidden' name='csrf' value='" . ($csrf_token ?? "") . "'/>";
    }
}

/*
 * @param $request
 * @return bool
 */
if (!function_exists("csrf_verify")) {

    function csrf_verify($request): bool
    {
        if (!empty($_SESSION['userdata']['csrf_token']) || !empty($request['csrf'])) {
            foreach ($_SESSION['userdata']['csrf_token'] as $key => $value) {
                if ($request['csrf'] == $value) {
                    return true;
                }
            }
        }
        return false;
    }
}

/**
 * ###################
 * ###   ALERTS    ###
 * ###################
 */

/**
 * @param string $type = info, danger, warning, success
 * @param $msg
 * @return string
 */
if (!function_exists("createMessage")) {

    function createMessage(string $type, $msg)
    {
        switch ($type) {
            case "info":
                $type = "alert-info";
                break;
            case "danger":
                $type = "alert-danger";
                break;
            case "warning":
                $type = "alert-warning";
                break;
            case "success":
                $type = "alert-success";
                break;
        }
        return "<div class='alert {$type}'>{$msg}</div>";
    }
}

/**
 * @param array $data
 * @return array|null
 */
if (!function_exists("filter")) {

    function filter(array $data): ?array
    {
        $filter = [];
        foreach ($data as $key => $value) {
            $filter[$key] = (is_null($value) ? null : filter_var($value, FILTER_DEFAULT));
        }
        return $filter;
    }
}


/**
 * ################
 * ###   DATE   ###
 * ################
 */

/** retorna uma data no formato passado ou no padrao: "d/m/Y H\hi"
 * @param string $date
 * @param string $format
 * @return string
 */
if (!function_exists("date_fmt")) {

    function date_fmt(string $date = "now", string $format = "d/m/Y H\hi"): string
    {
        return (new DateTime($date))->format($format);
    }
}

/** retorna uma data/hora no formato padrao BR
 * @param string $date
 * @return string
 */
if (!function_exists("date_fmt_br")) {

    function date_fmt_br(string $date = "now"): string
    {
        return (new DateTime($date))->format(CONF_DATE_BR);
    }
}

/** retorna data para exibicao
 * @param string $date
 * @param string $concat = simbolo entre data e hora
 * @return string = "d/m/Y $concat H:i"
 */
if (!function_exists("date_fmt_br_show")) {
    function date_fmt_br_show(string $date = "now", string $concat = "-"): string
    {
        return (new DateTime($date))->format("d/m/Y {$concat} H:i");
    }
}

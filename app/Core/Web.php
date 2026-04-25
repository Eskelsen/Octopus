<?php

namespace App\Core;

class Web
{
    public $response;
    public string|false $error = false;

    public function __construct($response, $error)
    {
        $this->response = $response;
        $this->error = $error;
    }

    public static function return($response, $error = false)
    {
        return new static($response,$error);
    }

    public static function response($data, $code = 200)
    {
        $response = empty($data->response) ? $data : $data->response;
        response($response, $code);
    }

    public static function match()
    {
        $path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        $path = ltrim(substr($path, strlen(BASE)),'/');
        $filepath = $path === '' ? 'streams/home-stream.php' : 'streams/' . $path . '-stream.php';
        if (!is_file(APP . $filepath)) {
            return APP . 'streams/pages/404.php';
        }
        self::redirect();
        return APP . $filepath;
    }

    private static function redirect()
    {
        if (!empty($_SESSION['redirect_to'])) {
            $url = $_SESSION['redirect_to'];
            unset($_SESSION['redirect_to']);
            redirect($url);
        }
    }
}
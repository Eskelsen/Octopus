<?php

namespace App\Core;

class Session
{
    public static function start(): void
    {
        session_name('octopus');

        session_set_cookie_params([
            'lifetime' => 604800,
            'path'     => '/',
            'domain'   => '',
            'secure'   => isset($_SERVER['HTTPS']),
            'httponly' => true,
            'samesite' => 'Lax',
        ]);

        ini_set('session.use_strict_mode', '1');
        ini_set('session.use_only_cookies', '1');

        ini_set('session.gc_probability', '1');
        ini_set('session.gc_divisor', '10');
        ini_set('session.gc_maxlifetime', '604800');

        session_save_path(APP . 'tmp/');

        session_start();

        if (empty($_SESSION['_last_regeneration'])) {
            $_SESSION['_last_regeneration'] = time();
            $_SESSION['csrf'] = bin2hex(random_bytes(32));
            return;
        }
        
        if ((time() - $_SESSION['_last_regeneration']) > 1800) {
            self::regenerate();
        }
    }

    public static function regenerate(): void
    {
        session_regenerate_id(true);
        $_SESSION['_last_regeneration'] = time();
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }

    public static function load($data)
    {
        $_SESSION = is_array($data) ? array_filter($data) : [];
    }

    public static function data()
    {
        return $_SESSION;
    }

    public static function get($key)
    {
        return $_SESSION[$key] ?? null;
    }

    public static function set($key,$value)
    {
        $_SESSION[$key] = $value;
    }

	public function alert($msg,$color = null,$disappear = false)
    {
		$_SESSION['alerts'][] = [$msg,$color,$disappear];
	}

    public static function on()
    {
        return $_SESSION['id'] ?? null;
    }

    public static function exit()
    {
        session_unset();
        session_destroy();
        setcookie(session_name(), '', time() - 3600, '/');
    }
}

<?php

class Authenticator
{
    static function create_authenticate_session()
    {
        if (session_status() == PHP_SESSION_NONE)
        {
            session_start();
        }

        $_SESSION['authenticated'] = true;
    }

    static function is_session_authenticated(): bool
    {
        if (session_status() == PHP_SESSION_NONE)
        {
            session_start();
        }

        return isset($_SESSION['authenticated']);
    }
}
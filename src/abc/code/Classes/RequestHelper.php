<?php

namespace Azt3k\SS\Classes;

class RequestHelper
{
    public static function is_ie(): bool
    {
        $u_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        return (bool) preg_match('/MSIE/i', $u_agent);
    }
}

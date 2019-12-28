<?php

namespace App\Service;

class TokenGenerator
{
    public static function generate()
    {
        return rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '=');
    }
}

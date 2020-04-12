<?php

declare(strict_types=1);

namespace App;

class TokenGenerator
{

    public function generate(int $length = 32)
    {
        return bin2hex(random_bytes($length / 2));
    }
}

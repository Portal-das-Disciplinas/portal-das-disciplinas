<?php

namespace App\Faker;

use Faker\Provider\Base;
use Illuminate\Support\Str;

class CodeFaker extends Base
{
    /**
     * @param int $length
     * @return string
     */
    public function code($length = 7): string
    {
        return strtoupper(Str::random($length));
    }
}

<?php

namespace App\Enums;

use InvalidArgumentException;
use ReflectionClass;

class Enum
{
    /**
     * Gets all defined constants from a class.
     * @return array
     */
    public static function getConstants()
    {
        $oClass = new ReflectionClass(static::class);
        return $oClass->getConstants();
    }

    /**
     * @param int $count
     * @return array|\Illuminate\Support\Collection|mixed
     * @throws \Exception
     */
    public static function random($count = 1)
    {
        if ($count < 1) {
            throw new \Exception('$count must be equal to or greater than 1');
        }

        $values = collect(self::getConstants());

        if ($count == 1) {
            return $values->random($count)
                ->toArray()[0];
        }

        try {
            return $values->random($count)
                ->toArray();
        } catch (InvalidArgumentException $exception) {
            return $values->shuffle()
                ->all();
        }
    }
}

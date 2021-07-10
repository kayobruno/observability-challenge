<?php

namespace App\Traits\Constants;

use ReflectionClass;

trait BaseConstants
{
    /**
     * @return array
     */
    public static function getConstants(): array
    {
        $reflectionClass = new ReflectionClass(self::class);
        return $reflectionClass->getConstants();
    }

    /**
     * @param string $prefix
     * @return array
     */
    public static function getConstantsValuesByPrefix(string $prefix): array
    {
        $data = [];
        $consts = self::getConstants();
        foreach ($consts as $key => $const) {
            if (strpos($key, $prefix) !== false) {
                $data[substr($key, strlen($prefix))] = $const;
            }
        }

        return $data;
    }
}

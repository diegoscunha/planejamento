<?php

namespace App\Enum;

class Enum
{
    public static function getConstants() {
        $oClass = new \ReflectionClass(get_called_class());
        return $oClass->getConstants();
    }

    public static function getConstante($v)
    {
        $oClass = new \ReflectionClass(get_called_class());
        foreach ($oClass->getConstants() as $key => $value) {
            if($v==$value)
                return $key;
        }
        return "";
    }
}

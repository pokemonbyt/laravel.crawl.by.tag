<?php


namespace App\Modules\Common\Enum;


class ActivationEnum
{
    //禁用
    public const INACTIVE = 0;
    //激活
    public const ACTIVE = 1;

    public static function all(){
        return [
            self::INACTIVE,
            self::ACTIVE
        ];
    }
}

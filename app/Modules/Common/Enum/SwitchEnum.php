<?php
/**
 * Created by PhpStorm.
 * User: pan
 * Date: 2020/1/1 17:55
 */

namespace App\Modules\Common\Enum;

/**
 * Notes: 是/否 的通用枚举
 *
 * Class SwitchEnum
 * @package App\Modules\Common\Enum
 */
class SwitchEnum
{
    //否
    const NO = 0;
    //是
    const YES = 1;

    /**
     * Notes: 检测是否是这个枚举
     * User: pan
     * Date: 2020/1/7 15:30
     *
     * @param $value
     * @return bool
     */
    public static function isExist($value)
    {
        return $value === self::NO || $value === self::YES;
    }

    /**
     * Notes: 获取全部
     * User: pan
     * Date: 2020/2/19 14:35
     *
     * @return array
     */
    public static function all()
    {
        return [
            self::NO,
            self::YES
        ];
    }
}

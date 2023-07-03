<?php
/**
 * Created by PhpStorm
 * User: pan
 * Date: 2020/2/27 17:37
 */

namespace App\Modules\Common\Enum;

/**
 * Notes: 队列优先级枚举
 *
 * Class QueueLevelEnum
 * @package App\Modules\Common\Enum
 */
class QueueLevelEnum
{
    //高
    const HIGH = 'high';
    //默认
    const DEFAULT = 'default';
    //低
    const LOW = 'low';

    /**
     * Notes: 获取全部
     * User: pan
     * Date: 2020/2/29 13:10
     *
     * @return array
     */
    public static function all()
    {
        return [
            self::HIGH,
            self::DEFAULT,
            self::LOW,
        ];
    }
}

<?php
/**
 * Created by PhpStorm
 * User: pan
 * Date: 2020/7/2 12:06
 */

namespace App\Modules\Common\Traits;

/**
 * model的拓展工具
 *
 * Trait ModelToolTrait
 * @package App\Modules\Common\Traits
 */
trait ModelToolTrait
{
    /**
     * Notes: 表名
     * User: pan
     * Date: 2020/7/2 12:07
     *
     * @return mixed
     */
    public static function tableName()
    {
        return (new self())->table;
    }

    /**
     * Notes: 统一 laravel7 的时间序列化格式
     * User: pan
     * Date: 2020/7/2 12:08
     *
     * @param \DateTimeInterface $date
     * @return string
     */
    public function serializeDate(\DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
    /**
     * Notes: Find first row of Model
     * User: limbo
     * Date: 2022/10/05 14:10
     *
     * @param int | array $condition
     * @return mixed
     */
    public static function findOne($condition)
    {
        if (is_array($condition)) {

            return (new self())::where($condition)->first();
        } else {

            return (new self())::find($condition);
        }
    }
}

<?php
/**
 * Created by PhpStorm
 * User: pan
 * Date: 2020/6/7 15:23
 */

namespace App\Modules\Common\Traits;


use Illuminate\Database\Eloquent\Relations\Relation;

trait GetMorphClassTrait
{
    /**
     * Notes: 获取当前调用的class全限定类名
     * User: pan
     * Date: 2020/6/7 15:23
     *
     * @return false|int|string
     */
    public function getMorphClass()
    {
        $morphMap = Relation::morphMap();

        if (! empty($morphMap) && in_array(static::class, $morphMap)) {
            return array_search(static::class, $morphMap, true);
        }

        return static::class;
    }
}

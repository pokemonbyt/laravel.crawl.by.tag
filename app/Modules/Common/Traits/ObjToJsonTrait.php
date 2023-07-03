<?php
/**
 * Created by PhpStorm
 * User: pan
 * Date: 2020/7/1 19:18
 */

namespace App\Modules\Common\Traits;


use Illuminate\Database\Eloquent\JsonEncodingException;

/**
 * Notes: 对象转json拓展
 *
 * Class ObjToJsonTrait
 * @package App\Modules\Common\Traits
 */
trait ObjToJsonTrait
{
    public function toArray()
    {
        return get_object_vars($this);
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }

    public function toJson($options = 0)
    {
        $json = json_encode($this->jsonSerialize(), $options);

        if (JSON_ERROR_NONE !== json_last_error()) {
            throw JsonEncodingException::forModel($this, json_last_error_msg());
        }

        return $json;
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: pan
 * Date: 2020/1/14 13:03
 */

namespace App\Modules\Common\Entity;


use App\Models\User;
use App\Modules\Common\Enum\DataOperateEnum;
use App\Modules\User\Repository\UserRepository;

/**
 * Notes: 生成操作记录表格
 *
 * Class DataOperateTable
 * @package App\Modules\Common\Entity
 */
class DataOperateTable
{
    /**
     * Notes: 表格，先操作的会在最后出现
     *
     * @var array
     */
    private $table = [];

    public function __construct($records, $revisionHistory)
    {
        if ($records) {
            //创建和删除记录
            collect($records)->each(function ($value) {
                array_unshift($this->table, $this->getItem(
                    $value->username,
                    $value->name,
                    $value->operate,
                    $value->created_at
                ));
            });

            $revisionHistory = collect($revisionHistory);

            //获取操作id再去重
            $ids = $revisionHistory->pluck('user_id')->unique()->values()->toArray();
            $users = (new UserRepository())->listByIds($ids);

            //数据变动记录
            $revisionHistory->each(function ($revision) use ($users) {
                $users->each(function ($user) use ($revision) {
                    if ($user->id == $revision->user_id) {
                        array_unshift($this->table, $this->getItem(
                            $user->username,
                            $user->name,
                            DataOperateEnum::UPDATE,
                            $revision->created_at,
                            $revision->key,
                            $revision->old_value,
                            $revision->new_value
                        ));
                    }
                });
            });
        }
    }

    /**
     * Notes: 获取结果
     * User: pan
     * Date: 2020/3/19 16:01
     *
     * @return array
     */
    public function get()
    {
        return $this->table;
    }

    /**
     * Notes: 生成结构
     * User: pan
     * Date: 2020/1/14 13:31
     *
     * @param $username
     * @param $name
     * @param $operate
     * @param string $created_at
     * @param string $key
     * @param string $oldValue
     * @param string $newValue
     * @return array
     */
    private function getItem($username, $name, $operate, $created_at = '', $key = '', $oldValue = '', $newValue = '')
    {
        return [
            'username' => $username,        //工号
            'name' => $name,                //名字
            'operate' => $operate,          //操作
            'key' => $key,                  //变化的key
            'old_value' => $oldValue,       //旧值
            'new_value' => $newValue,       //新值
            'created_at' => (string)$created_at,    //创建时间
        ];
    }
}

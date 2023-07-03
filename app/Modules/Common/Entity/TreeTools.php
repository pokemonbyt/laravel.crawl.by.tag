<?php
/**
 * Created by PhpStorm.
 * User: pan
 * Date: 2020/1/14 16:14
 */

namespace App\Modules\Common\Entity;

/**
 * Notes: 树结构工具(使用原生SQL语句)
 *
 * Class TreeTools
 * @package App\Modules\Common\Entity
 */
class TreeTools
{
    /**
     * Notes: 获取树的所有子节点（包括自身）
     * User: pan
     * Date: 2020/1/14 18:42
     *
     * @param $table
     * @param $id
     * @param string $pid
     * @return array
     */
    public static function findMeAndChild($table, $id, $pid = "pid")
    {
        $ids = \DB::select("select * from {$table} where {$pid} = {$id} or id={$id}");
        $ids = collect($ids)->pluck('id')->toArray();

        $descendantIds = self::loopInTree($table,$ids,$pid);

        $result = array_merge($ids, $descendantIds);

        return $result;
    }

    /**
     * Notes: 递归数据库
     * User: john
     * Date: 2020/1/14 18:42
     *
     * @param $table
     * @param $ids
     * @param string $pid
     * @return array
     */
    private static function loopInTree($table, $ids, $pid = "pid"){
        if (!$ids){
            return [];
        }

        $idsSqlList = self::calculateSqlList($ids);

        $childIds = \DB::select("select * from {$table} where {$pid} in ({$idsSqlList}) and id not in ({$idsSqlList})");
        $childIds = collect($childIds)->pluck('id')->toArray();
        if ($childIds){
            $result = self::loopInTree($table,$childIds,$pid);
            $childIds = array_merge($childIds, $result);
        }

        return $childIds;
    }

    /**
     * Notes:计算SQL清单
     * User: john
     * Date: 2021/12/18 17:46
     *
     * @param $arr
     * @return string
     */
    private static function calculateSqlList($arr)
    {
        $result = '';
        foreach ($arr as $item) {
            $result = $result ? $result . ',\'' . $item. '\'' : $result .  '\''.$item. '\'';
        }
        return $result;
    }

    /**
     * Notes: 获取树的所有子节点（包括自身）(父级id可以<子级id仓库调用)
     * User: nemsy
     * Date: 2021/06/11 16:04
     *
     * @param $table
     * @param $id
     * @param string $pid
     * @return array
     */
    public static function findMeAndChildWithouOrder($table, $id, $pid = "pid")
    {
//        $ids = \DB::select("select id from (select * from {$table} where {$pid} >= 0) realname_sorted, (select @pv := {$id}) initialisation where (FIND_IN_SET(pid,@pv) > 0 and @pv := concat(@pv, ',', id)) or id = @pv");
//
//        return collect($ids)->pluck('id')->toArray();
        $ids = \DB::select("select * from {$table} where {$pid} = {$id} or id={$id}");
        $ids = collect($ids)->pluck('id')->toArray();

        $descendantIds = self::loopInTree($table,$ids,$pid);

        $result = array_merge($ids, $descendantIds);

        return $result;
    }

    /**
     * Notes: 获取树的所有子节点（不包括自身）
     * User: pan
     * Date: 2020/1/14 18:43
     *
     * @param $table
     * @param $id
     * @param string $pid
     * @return array
     */
    public static function findChild($table, $id, $pid = "pid")
    {
        $ids = \DB::select("select id from (select * from {$table} where {$pid} >= 0 order by pid) realname_sorted, (select @pv := {$id}) initialisation where (FIND_IN_SET(pid,@pv) > 0 and @pv := concat(@pv, ',', id))");

        return collect($ids)->pluck('id')->toArray();
    }

    /**
     * Notes: 获取树的所有父节点（包括自身）
     * User: pan
     * Date: 2020/1/14 18:51
     *
     * @param $table
     * @param $id
     * @param string $pid
     * @return array
     */
    public static function findParent($table, $id, $pid = "pid")
    {
        $ids = \DB::select("select id from (select @r as _id, (select @r := {$pid} from {$table} where id = _id) as {$pid}, @l := @l + 1 as depth from (select @r := {$id}, @l := 0) vars, {$table} h where @r <> 0 and {$pid} > 0) T1 JOIN {$table} T2 on T1._id = T2.id order by id");

        return collect($ids)->pluck('id')->toArray();
    }

    /**
     * Notes: 获取树的所有父节点（原本不要排序）（包括自身）
     * User: nemsy
     * Date: 2021/1/26 17:19
     *
     * @param $table
     * @param $id
     * @param string $pid
     * @return array
     */
    public static function findParentWithoutSort($table, $id, $pid = "pid")
    {
        $ids = \DB::select("select id from (select @r as _id, (select @r := {$pid} from {$table} where id = _id) as {$pid}, @l := @l + 1 as depth from (select @r := {$id}, @l := 0) vars, {$table} h where @r <> 0 and {$pid} > 0) T1 JOIN {$table} T2 on T1._id = T2.id");

        return collect($ids)->pluck('id')->toArray();
    }

    /**
     * Notes: 搜索用户名与搜索值匹配的 user_id
     * User: Lion
     * Date: 2022/1/18 15:43
     *
     * @param $table
     * @param string $value
     * @param string $column
     * @return array
     */
    public static function findWithLikeQuery($table, $value, $column)
    {
        $ids = \DB::select("select * from {$table} where {$column} like '%{$value}%'");
        return collect($ids)->pluck('id')->toArray();
    }
}

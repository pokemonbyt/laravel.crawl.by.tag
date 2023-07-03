<?php
/**
 * Created by PhpStorm.
 * User: pan
 * Date: 2020/1/2 14:56
 */

namespace App\Modules\Common\Entity;

/**
 * Notes: 列表生成树结构的工具
 *
 * Class TreeTool
 * @package App\Modules\Common\Entity
 */
class ListToTreeTool
{
    private $tree = [];

    /**
     * Notes: 数据列表转换成树
     * User: pan
     * Date: 2020/1/2 15:19
     *
     * @param array $data 数据列表
     * @param string $sort 排序字段(如果传入排序字段，结果会自动排序)
     * @param int $rootId 根节点ID
     * @param string $pk 主键名称
     * @param string $pid 父节点名称
     * @param string $childName 子节点名称
     */
    public function __construct($data, $sort = '', $rootId = 0, $pk = 'id', $pid = 'pid', $childName = 'children')
    {
        if (is_array($data)) {
            //创建基于主键的数组引用
            $referList  = [];
            foreach ($data as $key => & $sorData) {
                $referList[$sorData[$pk]] =& $data[$key];
            }

            //list 转换为 tree
            foreach ($data as $key => $value) {
                $pId = $value[$pid];
                //一级
                if ($rootId == $pId) {
                    $this->tree[] =& $data[$key];
                }
                //多级
                else {
                    if (isset($referList[$pId])) {
                        $pNode               =& $referList[$pId];
                        $pNode[$childName][] =& $data[$key];
                        //排序
                        if (!empty($sort)) {
                            usort($pNode[$childName], function ($v1, $v2) use ($sort) {
                                return $v1[$sort] < $v2[$sort];
                            });
                        }
                    }
                }
            }
        }
    }

    /**
     * Notes: 增加Somi Root
     * User: Lion
     * Date: 2022/04/20 16:46
     *
     * @param string $rootName
     */
    public function addRootNote($rootName = 'SOMI'){
        $root = [
            'id' => 0,
            'name' => $rootName,
            'pid' => -99,
            'is_rent' => 0,
            'type' => -99,
            'sort' => null,
            'room_type_id' => -99,
            'status' => 0,
            'children' => $this->tree
        ];
        $this->tree = [$root];
    }

    /**
     * Notes: 获取结果
     * User: pan
     * Date: 2020/1/2 15:30
     *
     * @return array
     */
    public function get()
    {
        return $this->tree;
    }
}

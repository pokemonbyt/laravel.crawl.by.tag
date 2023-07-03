<?php
/**
 * Created by PhpStorm
 * User: pan
 * Date: 2020/12/15 16:43
 */

namespace App\Modules\Common\Entity;


use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

/**
 * Notes: 通用的表导入基类
 *
 * Class CommonImportBase
 * @package App\Modules\Common\Entity
 */
abstract class CommonImportBase implements ToCollection
{
    /**
     * 工号所在的列
     * @var int|mixed
     */
    protected $usernameColumn;
    /**
     * 标题所在的行
     * @var int
     */
    protected $titleRow;
    /**
     * 把标题转换成key
     * @var array
     */
    protected $keys;
    /**
     * excel表的内容
     * @var Collection
     */
    protected $collection;
    /**
     * 有效数据开始的行
     * @var int
     */
    protected $dataStartRow;
    /**
     * 解析完成的数据
     * @var array
     */
    protected $data = [];

    /**
     * Notes: 解析集合
     * User: pan
     * Date: 2020/12/15 16:46
     *
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        $this->collection = $collection;

        $this->parseKeys();

        $this->handleData();
    }

    /**
     * Notes: 把转换标题到key列表
     * User: pan
     * Date: 2020/12/15 16:56
     *
     */
    protected function parseKeys()
    {
        if (array_key_exists($this->titleRow, $this->collection->toArray())) {
            $row = $this->collection[$this->titleRow];
            foreach ($row as $key => $column) {
                if ($column) {
                    $this->keys[$key] = $column;
                }
            }
        }
    }

    /**
     * Notes: 解析数据
     * User: pan
     * Date: 2020/12/15 21:41
     *
     */
    protected function parseData()
    {
        foreach ($this->collection as $index => $row) {
            if ($index >= $this->dataStartRow) {
                $username = null;
                $temp = [];
                foreach ($row as $key => $column) {
                    if ($key == $this->usernameColumn) {
                        $username = $column;
                    }

                    if (array_key_exists($key, $this->keys)) {
                        $temp[$this->keys[$key]] = $column;
                    }
                }

                if ($username) {
                    //用户数组存的目的是因为有可能有些表是同一个工号有多条记录的，有些需求是所有的记录都要显示的
                    if (!array_key_exists($username, $this->data)) {
                        $this->data[$username] = [];
                    }
                    $this->data[$username][] = $temp;
                }
            }
        }
    }

    /**
     * Notes: 解析数据（不用Key）
     * User: nemsy
     * Date: 2021/03/09 20:24
     *
     */
    protected function parseNewData()
    {
        foreach ($this->collection as $index => $row) {
            if ($index >= $this->dataStartRow) {
                $username = null;
                $temp = [];
                foreach ($row as $key => $column) {
                    if ($key == $this->usernameColumn) {
                        $username = $column;
                    }

                    if (array_key_exists($key, $this->keys)) {
                        $temp[$this->keys[$key]] = $column;
                    }
                }
                if ($username) {
                    //用户数组存的目的是因为有可能有些表是同一个工号有多条记录的，有些需求是所有的记录都要显示的
//                    if (!array_key_exists($username, $this->data)) {
//                        $this->data[] = [];
//                    } else {
                         $this->data[] = $temp;
//                    }
                }
            }
        }
    }

    /**
     * Notes: 解析数据
     * User: pan
     * Date: 2020/12/15 16:47
     *
     * @return mixed
     */
    public abstract function handleData();
}

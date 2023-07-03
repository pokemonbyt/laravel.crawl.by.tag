<?php
/**
 * Created by PhpStorm
 * User: nemsy
 * Date: 2021/08/03 10:54
 */

namespace App\Modules\Common\Entity;


use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

/**
 * Notes: 提成表导入基类-允许重复标题
 *
 * Class CommissionImportBaseDuplicateTitle
 * @package App\Modules\Common\Entity
 */
abstract class CommissionImportBaseDuplicateTitle implements ToCollection
{
    /**
     * 工号所在的列
     * @var int|mixed
     */
    protected $usernameColumn;
    /**
     * 标题所在的行 (两层)
     * @var array
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
     * User: nemsy
     * Date: 2021/08/03 10:55
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
     * User: nemsy
     * Date: 2021/08/03 10:55
     *
     */
    protected function parseKeys()
    {
        //处理两层标题
        if (count($this->titleRow) == 2) {
            //层1
            if (array_key_exists($this->titleRow[0], $this->collection->toArray())) {
                $row = $this->collection[$this->titleRow[0]];
                foreach ($row as $key => $column) {
                    if ($column) {
                        $this->keys[$key] = $column;
                    } else {
                        $this->keys[$key] = $this->keys[$key - 1];
                    }
                }
            }
            //层2
            if (array_key_exists($this->titleRow[1], $this->collection->toArray())) {
                $row = $this->collection[$this->titleRow[1]];
                foreach ($row as $key => $column) {
                    if ($column) {
                        $this->keys[$key] = $this->keys[$key] . '||' . $column;
                    }
                }
            }
        } else if (count($this->titleRow) == 1) {
            $row = $this->collection[$this->titleRow[0]];
            //标题后面加个数字，就可以允许重复标题
            $i = 10;
            foreach ($row as $key => $column) {
                $i++;
                if ($column) {
                    $this->keys[$key] = $column.$i;
                } else {
                    $this->keys[$key] = '';
                }
            }
        }

    }

    /**
     * Notes: 解析数据
     * User: nemsy
     * Date: 2021/08/03 10:55
     *
     */
    protected function parseManyData()
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
                    $this->data[] = $temp;
                }
            }
        }
    }

    /**
     * Notes: 解析数据
     * User: nemsy
     * Date: 2021/08/03 10:55
     *
     * @return mixed
     */
    public abstract function handleData();
}

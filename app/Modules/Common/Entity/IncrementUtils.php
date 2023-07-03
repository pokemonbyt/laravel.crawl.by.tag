<?php
/**
 * Created by PhpStorm
 * User: pan
 * Date: 2020/8/31 13:00
 */

namespace App\Modules\Common\Entity;

use App\Modules\Warehouse\Enum\WarehouseInStoreTypeEnum;

/**
 * Notes: 生成自增id的工具
 *
 * Class IncrementUtils
 * @package App\Modules\Common\Entity
 */
class IncrementUtils
{
    /**
     * 单据的编号key
     */
    const PROCESS_NUMBER = "process:number";

    /**
     * 仓库-固定资产的编号key
     */
    const FIXED_NUMBER = "fixed:number";
    /**
     * 仓库-GOODS RETURN KEY
     */
    const FIXED_RETURN = "fixed:return";

    /**
     * 证书管理编号key
     */
    const CERTIFICATE_NUMBER = "certificate:number";

    /**
     * 仓库-固定资产盘盈的编号key
     */
    const FIXED_SURPLUS_NUMBER = "fixed_surplus:number";

    /**
     * 仓库单据的编号key
     */
    const WAREHOUSE_INVOICE_NUMBER = "warehouse_invoice:number";

    /**
     * 仓库出货单的编号key
     */
    const WAREHOUSE_SEND_NUMBER = "warehouse_send:number";

    /**
     * 仓库收货单的编号key
     */
    const WAREHOUSE_RECEIVE_NUMBER = "warehouse_receive:number";

    /**
     * 仓库-固定资产丢失二维码的编号key
     */
    const FIXED_NUMBER_LOST_QR = "fixed_lost_qr:number";

    /**
     * 需要每天重置的自增id可以在这里配置
     * @var string[]
     */
    public static $resetKey = [
        self::PROCESS_NUMBER,
        self::FIXED_NUMBER,
        self::CERTIFICATE_NUMBER,
        self::FIXED_SURPLUS_NUMBER,
        self::WAREHOUSE_INVOICE_NUMBER,
        self::WAREHOUSE_SEND_NUMBER,
        self::WAREHOUSE_RECEIVE_NUMBER,
        self::FIXED_NUMBER_LOST_QR,
    ];

    /**
     * Notes: 获取自增id
     * User: pan
     * Date: 2020/8/31 13:02
     *
     * @param $key
     * @return mixed
     */
    public static function getId($key)
    {
        \Cache::increment($key);

        return \Cache::get($key);
    }

    /**
     * Notes: 获取日期类的自增id
     * User: pan
     * Date: 2020/8/31 13:13
     *
     * @param $key
     * @param int $len
     * @return string
     */
    public static function getDateId($key, $len = 5)
    {
        $date = date('Ymd');
        $id = self::getId($key);

        $id = str_pad($id, $len, "0", STR_PAD_LEFT);

        return "{$date}{$id}";
    }

    /**
     * Notes: 获取出货单的自增id
     * User: nemsy
     * Date: 2022/02/10 15:20
     *
     * @param $key
     * @param int $len
     * @return string
     */
    public static function getWarehouseSendId($key, $len = 3)
    {
        $date = date('Ymd');
        $id = self::getId($key);

        $id = str_pad($id, $len, "0", STR_PAD_LEFT);

        return "CH{$date}{$id}";
    }

    /**
     * Notes: 获取收货单的自增id
     * User: nemsy
     * Date: 2022/02/10 15:20
     *
     * @param $key
     * @param int $len
     * @return string
     */
    public static function getWarehouseReceiveId($key, $len = 3)
    {
        $date = date('Ymd');
        $id = self::getId($key);

        $id = str_pad($id, $len, "0", STR_PAD_LEFT);

        return "SH{$date}{$id}";
    }

    /**
     * Notes: Generate invoice id
     * User: limbo
     * Date: 2022/09/26 15:08
     *
     * @param $prefix
     * @param $key
     * @param int $len
     *
     * @return string
     */
    public static function getWarehouseReturnId($prefixInvoice, $key, int $len = 3)
    {
        $date = date('Ymd');
        $id = self::getId($key);

        $id = str_pad($id, $len, "0", STR_PAD_LEFT);

        return "{$prefixInvoice}{$date}{$id}";
    }

    /**
     * Notes: 获取固定资产的自增id
     * User: nemsy
     * Date: 2021/03/14 15:29
     *
     * @param $key
     * @param int $len
     * @return string
     */
    public static function getFixedId($key, $len = 5)
    {
        $date = date('Ymd');
        $id = self::getId($key);

        $id = str_pad($id, $len, "0", STR_PAD_LEFT);

        if ($key == self::FIXED_NUMBER_LOST_QR) {
            return "X{$date}-{$id}";
        }
        return "{$date}-{$id}";
    }

    /**
     * Notes: 获取固定资产盘盈的自增id
     * User: nemsy
     * Date: 2021/11/09 11:35
     *
     * @param $key
     * @param int $len
     * @return string
     */
    public static function getSurplusFixedId($key, $len = 5)
    {
        $date = date('Ymd');
        $id = self::getId($key);

        $id = str_pad($id, $len, "0", STR_PAD_LEFT);

        return "PD{$date}-{$id}";
    }

    /**
     * Notes: 获取证书管理编号的自增id
     * User: nemsy
     * Date: 2021/07/03 16:06
     *
     * @param $key
     * @param int $len
     * @return string
     */
    public static function getCertificateId($key, $len = 5)
    {
        $date = date('Ymd');
        $id = self::getId($key);

        $id = str_pad($id, $len, "0", STR_PAD_LEFT);

        return "C{$date}-{$id}";
    }

    /**
     * Notes: 获取仓库单据编号的自增id
     * User: nemsy
     * Date: 2021/11/23 14:07
     *
     * @param $key
     * @param int $len
     * @return string
     */
    public static function getWarehouseInvoiceId($key, $len = 5)
    {
        $date = date('Ymd');
        $id = self::getId($key);

        $id = str_pad($id, $len, "0", STR_PAD_LEFT);

        return "CK{$date}-{$id}";
    }

    /**
     * Notes: 删除id
     * User: pan
     * Date: 2020/8/31 13:18
     *
     * @param $key
     * @return bool
     */
    public static function delId($key)
    {
        return \Cache::forget($key);
    }

    /**
     * Notes: 每日0点重置当然的自增id
     * User: pan
     * Date: 2020/8/31 16:07
     *
     */
    public static function resetId()
    {
        foreach (self::$resetKey as $key) {
            self::delId($key);
        }
    }
}

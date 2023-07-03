<?php
/**
 * Created by PhpStorm
 * User: nemsy
 * Date: 2020/6/19 15:25
 */

namespace App\Common;


use GatewayClient\Gateway;

/**
 * Notes: 推送消息工具，在Gateway基础上添加注册中心地址配置 （使得框架具备实时通信能力）
 *
 * Class GatewayUtil
 * @package App\Common
 */
class GatewayUtil extends Gateway
{
    /**
     * 注册中心地址 (如果gateway服务器和本项目不在同一台服务器，需要修改这个地址为目标服务器地址)
     *
     * @var string|array
     */
    public static $registerAddress = "127.0.0.1:1238";
}

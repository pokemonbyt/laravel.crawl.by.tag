<?php
//全局辅助函数

use Illuminate\Container\Container;
use Illuminate\Contracts\Auth\Factory as AuthFactory;

if (! function_exists('api_auth')) {
    /**
     * Notes: 全局的身份认证实列
     * User: nemsy
     * Date: 2019/11/29 20:22
     *
     * @return \Illuminate\Contracts\Auth\Factory|\Illuminate\Contracts\Auth\Guard|\Illuminate\Contracts\Auth\StatefulGuard
     */
    function api_auth()
    {
        return app(AuthFactory::class)->guard("api");
    }
}

if (! function_exists('api_user')) {
    /**
     * Notes: 获取当前登录用户
     * User: nemsy
     * Date: 2019/12/25 10:41
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    function api_user()
    {
        return api_auth()->user();
    }
}

if (! function_exists('api_user_model')) {
    /**
     * Notes: 获取用户模型
     * User: nemsy
     * Date: 2020/6/14 13:40
     *
     * @param null $userId
     * @return \App\Models\User|\App\Models\User[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|null
     */
    function api_user_model($userId = null)
    {
        if ($userId == null) {
            $userId = api_user()['id'];
        }

        return \App\Models\User::find($userId);
    }
}

if (! function_exists('api_user_models')) {
    /**
     * Notes: 查找用户的集合
     * User: nemsy
     * Date: 2020/6/26 16:02
     *
     * @param array $userIds
     * @return \Illuminate\Support\Collection
     */
    function api_user_models(array $userIds)
    {
        return \App\Models\User::whereIn('id', $userIds)->get();
    }
}

if (! function_exists('is_super_admin')) {
    /**
     * Notes: 当前登录是否是超级管理员
     * User: nemsy
     * Date: 2019/12/25 14:39
     *
     * @return bool
     */
    function is_super_admin()
    {
        return api_user()['types'] === \App\Modules\User\Enum\UserTypesEnum::SUPER_ADMIN;
    }
}

if (! function_exists('is_admin')) {
    /**
     * Notes:当前登录是否是管理员
     * User: john
     * Date: 2021-04-21 14:43
     *
     * @return bool
     */
    function is_admin()
    {
        return api_user()['types'] === \App\Modules\User\Enum\UserTypesEnum::ADMIN;
    }
}


if (! function_exists('check_super_admin')) {
    /**
     * Notes: 检测用户是否超级管理员
     * User: nemsy
     * Date: 2020/1/10 19:18
     *
     * @param mixed|string|int $usernameOrId 参数是工号或者id都可以检测
     * @return bool|int
     */
    function check_super_admin($usernameOrId)
    {
        $user = \App\Models\User::where('username', $usernameOrId)->first();
        if ($user instanceof \App\Models\User) {
            return $user->types === \App\Modules\User\Enum\UserTypesEnum::SUPER_ADMIN;
        }

        $user = \App\Models\User::where('id', $usernameOrId)->first();
        if ($user instanceof \App\Models\User) {
            return $user->types === \App\Modules\User\Enum\UserTypesEnum::SUPER_ADMIN;
        }

        return false;
    }
}

if (! function_exists('my_user_id')) {
    /**
     * Notes: 获取我的id
     * User: nemsy
     * Date: 2020/8/5 14:07
     *
     * @return integer|null
     */
    function my_user_id()
    {
        $user = api_user();

        return $user? $user['id'] : null;
    }
}

if (! function_exists('my_username')) {
    /**
     * Notes: 获取我的工号
     * User: nemsy
     * Date: 2019/12/25 10:41
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null|string
     */
    function my_username()
    {
        $user = api_user();

        return $user? $user['username'] : null;
    }
}

if (! function_exists('my_name')) {
    /**
     * Notes: 获取我的名字
     * User: nemsy
     * Date: 2019/12/25 10:41
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null|string
     */
    function my_name()
    {
        $user = api_user();

        return $user? $user['name'] : null;
    }
}

if (! function_exists('utils')) {
    /**
     * Notes: 全局小工具
     * User: nemsy
     * Date: 2019/12/25 10:41
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null|\App\Modules\Common\Entity\Utils
     */
    function utils()
    {
        return app(\App\Modules\Common\Entity\Utils::class);
    }
}

if (! function_exists('guard_api')) {
    /**
     * Notes: API路由守护
     * User: nemsy
     * Date: 2020/3/7 14:23
     *
     * @return string
     */
    function guard_api()
    {
        return 'api';
    }
}

if (! function_exists('is_api_route')) {
    /**
     * Notes: 检查路由是否API的
     * User: nemsy
     * Date: 2020/2/29 19:16
     *
     * @return bool
     */
    function is_api_route()
    {
        try {
            return app('router')->getRoutes()->match(request())->action['middleware'][0] === guard_api();

        } catch (Throwable $e) {
            return false;
        }
    }
}

if (! function_exists('super_admin')) {
    /**
     * Notes: 获取超级管理员列表
     * User: nemsy
     * Date: 2020/3/24 11:42
     *
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\App\Models\User[]
     */
    function super_admin()
    {
        return \App\Models\User::where('types', \App\Modules\User\Enum\UserTypesEnum::SUPER_ADMIN)->get();
    }
}

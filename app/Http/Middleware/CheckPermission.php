<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Modules\User\Enum\UserTypesEnum;
use Closure;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

/**
 * 路由权限访问检测
 * Class RouterPermission
 * @package App\Http\Middleware
 */
class CheckPermission
{
    /**
     * Notes: 需要忽略权限检测的路由列表
     *
     * @var array
     */
    private $ignoreRoute = [

    ];

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     * @throws AuthorizationException
     */
    public function handle($request, Closure $next)
    {
        return $next($request);
    }
}

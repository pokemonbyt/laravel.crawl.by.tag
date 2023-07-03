<?php

namespace App\Exceptions;

use App\Common\ExceptionReport;
use Illuminate\Contracts\Container\Container;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (\League\OAuth2\Server\Exception\OAuthServerException $e) {
            if($e->getCode() == 9)
                throw new UnauthorizedHttpException('jwt-auth', 'Unauthorized');
        });
    }


    private $service;

    public function __construct(Container $container)
    {
        parent::__construct($container);
    }

    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     *
     * @throws \Exception
     */
    public function report(\Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Notes: Render an exception into an HTTP response.
     * User: nemsy
     * Date: 2020/2/29 15:45
     *
     * @param \Illuminate\Http\Request $request
     * @param \Throwable $exception
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|mixed|\Symfony\Component\HttpFoundation\Response
     * @throws \Throwable
     */
    public function render($request, \Throwable $exception)
    {
        // 将方法拦截到自己的ExceptionReport
        $reporter = ExceptionReport::make($exception);
        if ($reporter->shouldReturn()) {
            return $reporter->report();
        }

        if(env('APP_DEBUG')) {
            //开发环境，则显示详细错误信息
            return parent::render($request, $exception);
        } else {
            //线上环境,未知错误，则显示500
            return $reporter->prodReport();
        }
    }
}

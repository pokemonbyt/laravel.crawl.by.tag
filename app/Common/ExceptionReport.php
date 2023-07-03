<?php


namespace App\Common;


use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response as FoundationResponse;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

/**
 * 统一错误返回方式
 * @package App\Helpers
 */
class ExceptionReport
{
    use ApiResponse;

    /**
     * @var \Throwable
     */
    public $exception;
    /**
     * @var Request
     */
    public $request;

    /**
     * @var
     */
    protected $report;

    /**
     * ExceptionReport constructor.
     * @param Request $request
     * @param \Throwable $exception
     */
    function __construct(Request $request, \Throwable $exception)
    {
        $this->request = $request;
        $this->exception = $exception;
    }

    /**
     * @var array
     */
    //当抛出这些异常时，可以使用我们定义的错误信息与HTTP状态码
    //可以把常见异常放在这里
    public $doReport = [
        ValidationException::class => [],
        AuthenticationException::class => FoundationResponse::HTTP_UNAUTHORIZED,
        ModelNotFoundException::class => FoundationResponse::HTTP_NOT_FOUND,
        AuthorizationException::class => FoundationResponse::HTTP_FORBIDDEN,
        UnauthorizedHttpException::class => FoundationResponse::HTTP_UNAUTHORIZED,
        NotFoundHttpException::class => FoundationResponse::HTTP_NOT_FOUND,
        MethodNotAllowedHttpException::class => FoundationResponse::HTTP_METHOD_NOT_ALLOWED,
    ];

    //可以注册异常信息
    public function register($className, callable $callback)
    {
        $this->doReport[$className] = $callback;
    }

    /**
     * @return bool
     */
    public function shouldReturn()
    {
        foreach (array_keys($this->doReport) as $report) {
            if ($this->exception instanceof $report) {
                $this->report = $report;
                return true;
            }
        }

        return false;
    }

    /**
     * @param \Throwable $e
     * @return static
     */
    public static function make(\Throwable $e)
    {
        return new static(\request(), $e);
    }

    /**
     * @return mixed
     */
    public function report()
    {
        //请求参数验证错误返回request的 message信息
        if ($this->exception instanceof ValidationException) {
            $error = Arr::first($this->exception->errors());

            return $this->failed($this->exception->status, Arr::first($error));
        }

        $code = $this->doReport[$this->report];

        return $this->failed($code, FoundationResponse::$statusTexts[$code]);
    }

    /**
     * Notes: 返回错误
     * User: nemsy
     * Date: 2020/2/29 15:45
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function prodReport()
    {
        $code = FoundationResponse::HTTP_INTERNAL_SERVER_ERROR;
        $message = FoundationResponse::$statusTexts[$code];

        return $this->failed($code, $message);
    }
}

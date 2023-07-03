<?php


namespace App\Common;

use Symfony\Component\HttpFoundation\Response as FoundationResponse;

/**
 * 统一回调格式
 * @package App\Helpers
 */
trait ApiResponse
{
    protected $statusCode;

    /**
     * Notes: 设置状态码
     * User: nemsy
     * Date: 2019/12/20 14:52
     *
     * @param int $statusCode
     */
    public function setStatusCode(int $statusCode)
    {
        $this->statusCode = $statusCode;
    }

    /**
     * Notes: 发送成功消息
     * User: nemsy
     * Date: 2019/12/20 14:53
     *
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function success($data = null)
    {
        return $this->respond(FoundationResponse::HTTP_OK, "success", null, compact('data'));
    }

    /**
     * Notes: 发送失败消息
     * User: nemsy
     * Date: 2019/12/20 14:53
     *
     * @param $code
     * @param string $message
     * @param null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function failed($code, $message = "", $data = null)
    {
        if($data) {
            $data = compact('data');
        }

        return $this->respond($code, "error", $message, $data);
    }

    /**
     * Notes: 发送自定义消息
     * User: nemsy
     * Date: 2019/12/20 14:53
     *
     * @param $code
     * @param string $status
     * @param null $message
     * @param null $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function common($code, $status = "", $message = null, $data = null)
    {
        return $this->respond($code, $status, $message, $data);
    }

    /**
     * Notes: 发送分页消息
     * User: nemsy
     * Date: 2019/12/20 14:56
     *
     * @param $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function paginate($data)
    {
        if ($data instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator) {
            if ($data->total() > 0) {
                return $this->success($data);
            } else {
                return $this->success([]);
            }
        }

        return $this->success($data);
    }

    /**
     * Notes: 发送
     * User: nemsy
     * Date: 2019/12/20 14:53
     *
     * @param $code
     * @param string $status
     * @param null $message
     * @param null $data
     * @return \Illuminate\Http\JsonResponse
     */
    private function respond($code, $status = "", $message = null, $data = null)
    {
        if ($code) {
            $this->setStatusCode($code);
        }

        $result = [
            'code' => $this->statusCode,
            'status' => $status,
            'timestamp' => now()->toDateTimeString()
        ];

        if ($data) {
            $result = array_merge($result, $data);
        }

        if ($message) {
            $result = array_merge($result, [
                'message' => $message,
            ]);
        }

        return response()->json($result);
    }
}

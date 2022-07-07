<?php
namespace app\api;

use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\Handle;
use think\exception\HttpException;
use think\exception\HttpResponseException;
use think\exception\ValidateException;
use think\Response;
use Throwable;
use app\common\ResponseJson;
use app\common\SdException;

/**
 * 应用异常处理类
 */
class ExceptionHandle extends Handle
{
    private const SUCCESS_CODE = 200;
    private const FAIL_CODE = 202;

    private const SUCCESS_MSG = 'success';
    private const FAIL_MSG = 'fail';

    /**
     * 不需要记录信息（日志）的异常类列表
     * @var array
     */
    protected $ignoreReport = [
        HttpException::class,
        HttpResponseException::class,
        ModelNotFoundException::class,
        DataNotFoundException::class,
        ValidateException::class,
        SdException::class,
    ];

    /**
     * 记录异常信息（包括日志或者其它方式记录）
     *
     * @access public
     * @param  Throwable $exception
     * @return void
     */
    public function report(Throwable $exception): void
    {
        // 使用内置的方式记录异常日志
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @access public
     * @param \think\Request   $request
     * @param Throwable $e
     * @return Response
     */
    public function render($request, Throwable $e): Response
    {
        // 添加自定义异常处理机制
        $param = [];
        header('Content-Type:application/json;');

        if (env('app_debug', false)) {
            $param = [
                'method' => $request->method(),
                'params' => $request->post(),
                'msg' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'code' => $e->getCode(),
                'class' => get_class($e)
            ];
        }

        return ResponseJson::fail($e->getMessage(),static::FAIL_CODE, $param);
    }
}

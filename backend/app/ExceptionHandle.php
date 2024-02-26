<?php
namespace app;

use app\common\Status;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\Handle;
use think\exception\HttpException;
use think\exception\HttpResponseException;
use think\exception\ValidateException;
use think\exception\ClassNotFoundException;
use think\exception\RouteNotFoundException;
use think\Response;
use Throwable;
use Exception;
use ErrorException;
use ParseError;
use TypeError;
use InvalidArgumentException;

/**
 * 应用异常处理类
 */
class ExceptionHandle extends Handle
{
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

        if (env('app_debug')) {
            // return result()::error($e -> getTraceAsString(), $e -> getCode(), $e -> getMessage());
             return parent::render($request, $e);
        }

        // 参数验证错误
        if ($e instanceof ValidateException) {
            return result()::error(422, $e->getError());
        }

        // 请求404异常
        if (($e instanceof ClassNotFoundException || $e instanceof RouteNotFoundException) || ($e instanceof HttpException && $e->getStatusCode() == 404)) {
            return result()::error(Status::NOTFOUND());
        }

        // 请求500异常
        if ($e instanceof Exception ||  $e instanceof HttpException || $e instanceof InvalidArgumentException || $e instanceof ErrorException || $e instanceof ParseError || $e instanceof TypeError)  {

            // $this->reportException($request, $e);
            return result()::error(500, '系统异常，请稍后再试');
        }

        // 其他错误
        // $this->reportException($request, $e); // 将错误信息输出到调试台
        return result()::error(Status::UNKNOWN());

        // 其他错误交给系统处理
        // return parent::render($request, $e);
    }

    private function reportException($request, Throwable $e):void {
        $errorStr = "url:".$request->host().$request->url()."\n";
        $errorStr .= "code:".$e->getCode()."\n";
        $errorStr .= "file:".$e->getFile()."\n";
        $errorStr .= "line:".$e->getLine()."\n";
        $errorStr .= "message:".$e->getMessage()."\n";
        $errorStr .=  $e->getTraceAsString();

        trace($errorStr, 'error');
    }
}

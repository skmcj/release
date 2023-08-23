<?php

namespace app\middleware;

use app\common\Status;
use app\common\Token;
use app\model\Role;
use think\facade\Session;

class TokenAdminHandle
{
    public function handle($request, \Closure $next)
    {
        // 第一步先取token
        $stoken = $request -> header('Authorization');
        if(empty($stoken)) return result()::error(Status::TOKEN_ERR('Token为空'));
        $utoken = session('utoken');
        if(empty($utoken)) return result()::error(Status::TOKEN_FAIL('用户未登录'));
        // jwt进行校验token
        $res = Token::checkToken($utoken.'.'.$stoken);
        if (!$res['flag']){
            return result()::error($res['status']);
        }
        // 校验权限
        $role = Role::id($res['data'] -> id) -> find();
        if($role === null) {
            // 用户不存在
            return result()::error(Status::TOKEN_FAIL('Token异常，请重新登录'));
        }
        if($role -> role > 1) {
            return result()::error(Status::TOKEN_ERR('权限不足'));
        }
        $request -> uData = [
            'id' => $res['data'] -> id,
            'role' => $role -> role
        ];
        // 当 token 还有 5 分钟无效时，刷新token
        if(!empty($res['exp']) && $res['exp'] < time() + config('token.refresh_time')) {
            $token = Token::createToken($res['data']);
            $list = explode('.', $token);
            session('utoken', $list[0].'.'.$list[1]);
            return $next($request) -> header([
                'Access-Control-Expose-Headers' => 'Authorization',
                'Authorization' => $stoken
            ]);
        }
        return $next($request);
    }
}

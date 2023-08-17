<?php

namespace app\controller;
use app\BaseController;
use app\common\IDGenerator;
use app\common\Status;
use app\common\Token;
use app\model\Role;
use app\pojo\PageEntity;

class RoleController extends BaseController
{
    public function index()
    {
        return result()::error(Status::COMMON_ERR());
    }

    /**
     * 角色登录
     */
    public function login() {

        /**
         * 根据登录信息生成 JWT 
         * 将其分割成三部分
         * 前两部分未加密信息存放在本地，服务端session中
         * 第三段加密信息传给用户，用于后续操作的身份验证
         * 
         * 其实，这种做法有种为了用 JWT 而用 token 的意味，违背了 JWT 的初衷
         * 可以优化为完全将信息存放到 session 中
         * 
         * 当然，这种做法也有一定的好处，增加了一道验证
         */

        $res = Role::login($this -> request -> post());
        if($res['data'] === null) return result()::error($res['status']);
        $token = Token::createTokenById($res['data'] -> id);
        // 将token分解，只给用户加密部分
        $list = explode('.', $token);
        // 存入session
        session('utoken', $list[0].'.'.$list[1]);
        
        return result()::success([
            'username' => $res['data'] -> username,
            'role' => $res['data'] -> role
        ], $res['status']) -> header([
            'Authorization' => $list[2]
        ]);
    }

    /**
     * 角色退出
     */
    public function logout() {
        // $uData = $this -> request -> uData;
        // 清除 session
        session('utoken', null);
        return result()::success(null, Status::LOGOUT_OK());
    }

    /**
     * 添加新角色
     */
    public function save() {
        $role = $this -> request -> uData['role'];
        $data = $this -> request -> post();
        if(isset($data['role']) && $data['role'] <= $role) {
            return result()::error(Status::TOKEN_ERR('权限不足，不能新增超过自己权限的角色'));
        }
        $status = Role::add($data);
        return result()::success(null, $status);
    }

    /**
     * 修改角色
     */
    public function edit() {
        $role = $this -> request -> uData['role'];
        $data = $this -> request -> post();
        $data['adRole'] = $role;
        $status = Role::edit($data);
        return result()::success(null, $status);
    }

    /**
     * 根据ID获取
     */
    public function getById($id) {
        $role = Role::getById($id);
        if($role === null) return result()::error(Status::GET_ERR());
        return result()::success($role, Status::GET_OK());
    }

    /**
     * 分页获取
     */
    public function getPage($page = 1, $pageSize = 5) {
        $query = new Role();
        $list = $query -> page($page, $pageSize) -> field(['id', 'username', 'role', 'disabled']) -> select();
        $total = $query -> count();
        $data = new PageEntity($list, $total, $page, $pageSize);
        return result()::success($data, Status::GET_OK());
    }

    /**
     * 根据ID删除
     */
    public function deleteById($id) {
        $r = $this -> request -> uData['role'];
        $role = Role::getById($id);
        if($role === null) return result()::error(Status::COMMON_FIND_ERR());
        if($r >= $role -> role) return result()::error(Status::TOKEN_ERR('权限不足，不能删除超出自己权限的角色'));
        $flag = Role::destroy($id);
        if(!$flag) return result()::error(Status::DEL_ERR());
        return result()::success(null, Status::DEL_OK());
    }
}

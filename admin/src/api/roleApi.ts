import request from '@/utils/requert';
import type { RoleInfo } from '@/types';
import { md5 } from '@/utils/commonUtil';

/**
 * 登录
 * @param username
 * @param password
 */
export const loginApi = function (username: string, password: string) {
  return request.post<RoleInfo>('/login', {
    username,
    password: md5(password)
  });
};

/**
 * 退出
 */
export const logoutApi = function () {
  return request.post<RoleInfo>('/logout');
};

/**
 * 验证token
 */
export const checkLoginApi = function () {
  return request.get<RoleInfo>('/role/check');
};

/**
 * 分页获取角色信息
 * @param page
 * @param pageSize
 */
export const getRolePageApi = function (page = 1, pageSize = 5) {
  return request.get('/role', {
    params: {
      page,
      pageSize
    }
  });
};

/**
 * 添加角色
 * @param data
 */
export const addRoleApi = function (data: RoleInfo) {
  data['password'] && (data['password'] = md5(data['password']));
  return request.post('/role', data);
};

/**
 * 修改角色
 * @param data
 */
export const editRoleApi = function (data: RoleInfo) {
  data['password'] && (data['password'] = md5(data['password']));
  return request.put('/role', data);
};

/**
 * 删除角色
 * @param id
 */
export const delRoleApi = function (id: string) {
  return request.delete(`/role/${id}`);
};

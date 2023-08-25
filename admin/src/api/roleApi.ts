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

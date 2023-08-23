import request from '@/utils/requert';
import { md5 } from '@/utils/commonUtil';

/**
 * 登录
 * @param username
 * @param password
 */
export const loginApi = function (username: string, password: string) {
  return request.post<LoginRes>('/login', {
    username,
    password: md5(password)
  });
};

/**
 * 退出
 */
export const logoutApi = function () {
  return request.post<LoginRes>('/logout');
};

/**
 * 验证token
 */
export const checkLoginApi = function () {
  return request.get<LoginRes>('/role/check');
};

interface LoginRes {
  username: string;
  role: number;
  roleText: string;
}

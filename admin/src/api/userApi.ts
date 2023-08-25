import request from '@/utils/requert';
import type { UserInfo, Page, UserShortInfo } from '@/types';

/**
 * 分页获取用户信息
 */
export const getUserPageApi = function (currentPage = 1, pageSize = 5) {
  return request.get<Page<UserInfo>>('/user/page', {
    params: {
      page: currentPage,
      pageSize
    }
  });
};

/**
 * 根据用户ID获取用户信息
 * @param userId
 */
export const getUserByIdApi = function (userId: string) {
  return request.get<UserInfo>(`/user/${userId}`);
};

/**
 * 添加用户
 * @param data
 */
export const addUserApi = function (data: UserInfo) {
  if (data.year) data.year = Number(data.year);
  return request.post('/user', data);
};

/**
 * 编辑用户
 * @param data
 */
export const editUserApi = function (data: UserInfo) {
  if (data.year) data.year = Number(data.year);
  return request.put('/user', data);
};

/**
 * 删除用户
 * @param data
 */
export const delUserApi = function (id: string) {
  return request.delete(`/user/${id}`);
};

/**
 * 获取用户缩略列表
 */
export const getUserListApi = function () {
  return request.get<UserShortInfo[]>('/user/list');
};

import request from '@/utils/requert';
import type { UserInfo, Page, UserShortInfo, Social } from '@/types';

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

// 用户社交信息
/**
 * 分页获取用户社交信息
 * @param page
 * @param pageSize
 * @param key
 */
export const getSocialPageApi = function (page = 1, pageSize = 5, key = '') {
  return request.get<Page<Social>>(`/social/all?page=${page}&pageSize=${pageSize}&key=${key}`);
};

/**
 * 新增社交信息
 * @param data
 */
export const addSocialApi = function (data: Social) {
  return request.post('/social', data);
};

/**
 * 修改社交信息
 * @param data
 */
export const editSocialApi = function (data: Social) {
  return request.put('/social', data);
};

/**
 * 删除社交信息
 * @param id
 */
export const delSocialApi = function (id: string) {
  return request.delete(`/social/${id}`);
};

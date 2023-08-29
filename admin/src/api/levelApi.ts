import type { LevelInfo, LevelShortInfo, Page } from '@/types';
import request from '@/utils/requert';

/**
 * 分页获取境界组
 * @param page
 * @param pageSize
 */
export const getLevelPageApi = function (page = 1, pageSize = 5) {
  return request.get<Page<LevelInfo>>('/level/page', {
    params: {
      page,
      pageSize
    }
  });
};

/**
 * 获取境界组列表
 */
export const getLevelListApi = function () {
  return request.get<LevelShortInfo[]>('/level/list');
};

/**
 * 添加境界组
 * @param data
 */
export const addLevelApi = function (data: LevelInfo) {
  return request.post('/level', data);
};

/**
 * 修改境界组
 * @param data
 */
export const editLevelApi = function (data: LevelInfo) {
  return request.put('/level', data);
};

/**
 * 删除境界组
 * @param id
 */
export const delLevelApi = function (id: string) {
  return request.delete(`/level/${id}`);
};

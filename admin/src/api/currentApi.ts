import request from '@/utils/requert';
import type { CurrentInfo } from '@/types';

/**
 * 获取current
 */
export const getCurrentApi = function () {
  return request.get<CurrentInfo>('/current');
};

/**
 * 修改current
 * @param data
 */
export const setCurrentApi = function (data: CurrentInfo) {
  return request.put('/current', data);
};

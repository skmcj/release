import request from '@/utils/requert';
import type { Page, Sentence } from '@/types';

/**
 * 分页获取
 * @param currentPage
 * @param pageSize
 * @param key
 */
export const getSentencePageApi = function (currentPage = 1, pageSize = 5, key = '') {
  return request.get<Page<Sentence>>('/sentence/page', {
    params: {
      page: currentPage,
      pageSize,
      key
    }
  });
};

/**
 * 添加句子
 * @param data
 */
export const addSentenceApi = function (data: Sentence) {
  return request.post('/sentence', data);
};

/**
 * 修改句子
 * @param data
 */
export const editSentenceApi = function (data: Sentence) {
  return request.put('/sentence', data);
};

/**
 * 批量添加
 * @param data
 */
export const batchSentenceApi = function (data: Sentence[]) {
  return request.post('/sentence/batch', data);
};

export const delSentenceApi = function (id: string) {
  return request.delete(`/sentence?ids=${id}`);
};

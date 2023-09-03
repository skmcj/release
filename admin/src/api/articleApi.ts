import type { ArticleInfo, Page, ArticleContent } from '@/types';
import request from '@/utils/requert';

/**
 * 分页获取文章
 * @param page
 * @param pageSize
 * @param key
 */
export const getArticlePageApi = function (page = 1, pageSize = 5, key: string = '') {
  return request.get<Page<ArticleInfo>>('/article/page', {
    params: {
      page,
      pageSize,
      key
    }
  });
};

/**
 * 获取文章列表
 */
export const getArticleListApi = function () {
  return request.get('/article/list');
};

/**
 * 根据ID获取文章
 * @param id
 */
export const getArticleByIdApi = function (id: string) {
  return request.get(`/article/id/${id}`);
};

/**
 * 获取文章内容
 * @param id
 */
export const getArticleContentApi = function (id: string) {
  return request.get(`/article/content/${id}`);
};

/**
 * 添加文章
 * @param data
 */
export const addArticleApi = function (data: ArticleInfo) {
  return request.post('/article', data);
};

/**
 * 编辑文章
 * @param data
 */
export const editArticleApi = function (data: ArticleInfo) {
  return request.put('/article', data);
};

/**
 * 编辑文章内容
 * @param data
 */
export const editArticleContentApi = function (data: ArticleContent) {
  return request.put('/article/edit', data);
};

/**
 * 根据ID删除文章
 * @param id
 */
export const delArticleApi = function (id: string) {
  return request.delete(`/article/${id}`);
};

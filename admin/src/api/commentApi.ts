import type { CommentInfo, Page } from '@/types';
import request from '@/utils/requert';

export type CommentSearchType = 'nickname' | 'content' | 'email' | 'address';

/**
 * 分页搜索留言
 * @param page
 * @param pageSize
 * @param key
 * @param type
 * @param visible
 * @param disabled
 */
export const getCommentPageApi = function (
  page = 1,
  pageSize = 5,
  key: string = '',
  type: CommentSearchType = 'content',
  visible: number = 2,
  disabled: number = 2
) {
  const data: any = { page, pageSize };
  if (key !== '') {
    data['key'] = key;
    data['type'] = type;
  }
  if (visible !== 2) data['visible'] = visible === 0 ? false : true;
  if (disabled !== 2) data['disabled'] = disabled === 0 ? false : true;
  return request.get<Page<CommentInfo>>('/comment/page', {
    params: data
  });
};

/**
 * 新增留言
 * @param data
 */
export const addCommentApi = function (data: CommentInfo) {
  return request.post('/comment/bk', data);
};

/**
 * 修改留言
 * @param data
 */
export const editCommentApi = function (data: CommentInfo) {
  return request.put('/comment/bk', data);
};

/**
 * 删除留言
 * @param id
 */
export const delCommentApi = function (id: string) {
  return request.delete(`/comment/${id}`);
};

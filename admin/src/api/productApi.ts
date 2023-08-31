import type { Page, ProductInfo, ProductLabel, ProductShortInfo } from '@/types';
import request from '@/utils/requert';

/**
 * 分页获取作品
 * @param page
 * @param pageSize
 * @param key
 */
export const getProductPageApi = function (page = 1, pageSize = 5, key = '') {
  const data: any = { page, pageSize };
  if (key !== '') data['key'] = key;
  return request.get<Page<ProductInfo>>('/product/page', {
    params: data
  });
};

/**
 * 获取作品列表
 */
export const getProductListApi = function () {
  return request.get('/product/list');
};

/**
 * 根据ID获取作品
 * @param id
 */
export const getProductByIdApi = function (id: string) {
  return request.get<ProductInfo>(`/product/${id}`);
};

/**
 * 获取作品缩略列表
 */
export const getProductShortListApi = function () {
  return request.get<ProductShortInfo[]>('/product/list');
};

/**
 * 添加作品
 * @param data
 */
export const addProductApi = function (data: ProductInfo) {
  return request.post('/product', data);
};

/**
 * 修改作品
 * @param data
 */
export const editProductApi = function (data: ProductInfo) {
  return request.put('/product', data);
};

/**
 * 删除作品
 * @param id
 */
export const delProductApi = function (id: string) {
  return request.delete(`/product/${id}`);
};

/**
 * 分页获取作品标签
 * @param page
 * @param pageSize
 * @param pid
 */
export const getPLabelPageApi = function (page = 1, pageSize = 5, pid: string = '') {
  return request.get<Page<ProductLabel>>('/plabel/page', {
    params: {
      page,
      pageSize,
      key: pid,
      type: 'pid'
    }
  });
};

/**
 * 添加作品标签
 * @param data
 */
export const addPLabelApi = function (data: ProductLabel) {
  return request.post('/plabel', data);
};

/**
 * 编辑作品标签
 * @param data
 */
export const editPLabelApi = function (data: ProductLabel) {
  return request.put('/plabel', data);
};

/**
 * 删除作品标签
 * @param id
 */
export const delPLabelApi = function (id: string) {
  return request.delete(`/plabel/${id}`);
};

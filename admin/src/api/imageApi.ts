import request from '@/utils/requert';
import type { ImageInfo, ImageURes, Page } from '@/types';

/**
 * 上传图片
 * @param blob 图片blob数据
 * @param imgName 图片名称
 */
export const uploadImageApi = function (blob: Blob, imgName: string = '') {
  const formData = new FormData();
  formData.append('image', blob, imgName ?? `${Date.now()}.png`);
  const header = {
    'Content-Type': 'multipart/form-data'
  };
  return request.post<ImageURes>('/image/upload', formData, {
    headers: header
  });
};

/**
 * 分页获取每一一图数据
 * @param page
 * @param pageSize
 * @param key
 * @param type
 */
export const getImagePageApi = function (page = 1, pageSize = 5, key = '', type = 'name') {
  return request.get<Page<ImageInfo>>('/image/page', {
    params: {
      page,
      pageSize,
      key,
      type
    }
  });
};

/**
 * 根据ID获取图片数据
 * @param id
 */
export const getImageByIdApi = function (id: string = '') {
  return request.get(`/image/${id}`);
};

/**
 * 添加每日图片数据
 * @param data
 */
export const addImageApi = function (data: ImageInfo) {
  return request.post('/image', data);
};

/**
 * 批量添加每日一图
 * @param data
 */
export const batchImageApi = function (data: ImageInfo[]) {
  return request.post('/image/batch', data);
};

/**
 * 修改每日一图
 * @param data
 */
export const editImageApi = function (data: ImageInfo) {
  return request.put('/image', data);
};

/**
 * 删除图片记录
 * @param ids
 */
export const delImageApi = function (ids: string) {
  return request.delete(`/image?ids=${ids}`);
};

/**
 * 获取图片大小
 * @param url
 */
export const getImageSizeApi = function (url: string) {
  return request.get('/image/size', {
    params: { url }
  });
};

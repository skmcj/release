import request from '@/utils/requert';
import type { ImageURes } from '@/types';

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

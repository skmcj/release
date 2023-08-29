import { ElMessage, type messageType } from 'element-plus';
import { Md5 } from './md5';
import type { ImageType } from '@/types';

interface MessageConfig {
  duration?: number;
  showClose?: boolean;
  onClose?: () => void;
}

/**
 * 显示消息
 * @param message
 * @param type
 * @param param2
 */
export const showMessage = function (
  message: string,
  type: messageType = 'info',
  { duration = 2500, showClose = true, onClose = () => {} } = {}
) {
  ElMessage({
    message: message,
    type: type,
    showClose: showClose,
    duration: duration,
    onClose: onClose
  });
};

/**
 * md5编码
 * @param str
 */
export const md5 = function (str: string): string {
  const md = new Md5();
  md.appendAsciiStr(str);
  return md.end() as string;
};

/**
 * 判断字符串是否是json
 * @param str
 */
export const isJson = function (str: string): boolean {
  if (!str) return false;
  if (typeof str !== 'string') return false;
  try {
    let obj = JSON.parse(str);
    if (obj && typeof obj === 'object') return true;
    else return false;
  } catch (error) {
    return false;
  }
};

/**
 * 判断链接是否是wallhaven壁纸完整链接
 * @param url
 */
export const isWallhavenFullImg = function (url: string): boolean {
  const reg = /^https?:\/\/w\.wallhaven\.cc\/full\/.+\.(gif|png|jpg|jpeg)$/i;
  return reg.test(url);
};

/**
 * 获取wallhaven完整链接的预览图地址
 * @param url
 */
export const calcWallhavenSmallImg = function (url: string): string {
  if (isWallhavenFullImg(url)) {
    const reg = /^https?:\/\/w\.wallhaven\.cc\/full\/.+?-(.*?)\.(gif|png|jpg|jpeg)$/i;
    const list = url.match(reg);
    if (list?.length && list.length > 1) {
      return `https://th.wallhaven.cc/small/${list[1].slice(0, 2)}/${list[1]}.jpg`;
    } else {
      return url;
    }
  } else {
    return url;
  }
};

/**
 * 获取网络图片信息
 * @param url
 */
export const getWebImgWH = function (url: string) {
  return new Promise((resolve, reject) => {
    // 创建对象
    const img = new Image();

    // 改变图片的src
    // img.crossOrigin = 'anonymous';
    img.src = url;
    // 判断是否有缓存
    if (img.complete) {
      resolve({ w: img.width, h: img.height });
    } else {
      // 加载完成执行
      img.onload = function () {
        resolve({ w: img.width, h: img.height });
      };
      img.onerror = function (e) {
        return reject(false);
      };
    }
  });
};

/**
 * 校验图片URL有效性
 * @param url
 */
export const validateImgUrl = function (url: string) {
  const IMG_REG = /^https?:\/\/(.+\/)+.+(\.(jpg|png|gif|jpeg|apng|avif|svg|webp|jif|ico|tiff))$/i;
  return IMG_REG.test(url);
};

/**
 * 获取图片链接类型
 * @param url
 */
export const getImageType = function (url: string) {
  const IMG_REG = /^https?:\/\/.+\/.+\.(jpg|png|gif|jpeg|apng|avif|svg|webp|jif|ico|tiff)$/;
  const res = url.match(IMG_REG);
  if (res) {
    return res[1] as ImageType;
  } else return '';
};

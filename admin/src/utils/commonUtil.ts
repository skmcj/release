import { ElMessage, type messageType } from 'element-plus';
import { Md5 } from './md5';

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

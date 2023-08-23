import { ElMessage, type messageType } from 'element-plus';
import { Md5 } from './md5';

interface MessageConfig {
  duration?: number;
  showClose?: boolean;
  onClose?: () => void;
}

export const showMessage = function (
  message: string,
  type: messageType = 'info',
  config: MessageConfig = {
    duration: 2500,
    showClose: true
  }
) {
  config.onClose
    ? ElMessage({
        message: message,
        type: type,
        showClose: config.showClose,
        duration: config.duration,
        onClose: config.onClose
      })
    : ElMessage({
        message: message,
        type: type,
        showClose: config.showClose,
        duration: config.duration
      });
};

export const md5 = function (str: string): string {
  const md = new Md5();
  md.appendAsciiStr(str);
  return md.end() as string;
};

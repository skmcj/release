import { showMessage } from '@/utils/commonUtil';

export const showStatus = (status: number | string) => {
  let message: string = '';
  switch (status) {
    case 400:
      message = '[400]: 请求错误';
      break;
    case 401:
      message = '[401]: 未授权，请登录后重试';
      break;
    case 403:
      message = '[403]: 拒绝访问';
      break;
    case 404:
      message = '[404]: Not Found, 请求不存在';
      break;
    case 408:
      message = '[408]: 请求超时';
      break;
    case 500:
      message = '[500]: 系统错误';
      break;
    case 501:
      message = '[501]: 系统错误';
      break;
    case 502:
      message = '[502]: 网络错误';
      break;
    case 503:
      message = '[503]: 服务不可用';
      break;
    case 504:
      message = '[504]: 网络超时';
      break;
    case 505:
      message = '[505]: HTTP版本不受支持';
      break;
    default:
      message = `[${status}]: 连接出错!`;
  }
  showMessage(message, 'error');
};

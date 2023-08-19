/**
 * 改变主题模式
 * @param mode
 */
export const changeThemeMode = function (mode: number = 0) {
  let modeStr = 'light';
  switch (mode) {
    case 0:
      modeStr = 'light';
      break;
    case 1:
      modeStr = 'dark';
      break;
  }
  setCookieItem('theme', modeStr);
  document.body.className = modeStr;
};

/**
 * 打开link
 * @param link
 * @param target
 */
export const openLink = (link: string, target: string = '_blank') => {
  window.open(link, target);
};

/**
 * 返回CSS规则值
 * @param val
 */
export const getPropsStyle = (val: number | string) => {
  if (typeof val === 'number') return `${val}px`;
  else return val;
};

/**
 * 复制内容到剪切板
 * 基于 document.execCommand
 * @param content 内容
 */
export const copyToClipboardE = function (content: string) {
  return new Promise((resolve, reject) => {
    const textarea = document.createElement('textarea');
    textarea.setAttribute('readonly', 'true');
    //将内容赋值给textarea
    textarea.value = content;
    document.body.appendChild(textarea);
    textarea.select();
    let flag = document.execCommand('copy');
    document.body.removeChild(textarea);
    if (flag) resolve(null);
    else reject();
  });
};

/**
 * 复制内容到剪切板
 * 基于 navigator.clipboard
 * @param content 内容
 */
export const copyToClipboard = function (content: string) {
  // 基于 navigator.clipboard
  return navigator.clipboard.writeText(content);
};

/**
 * 异步加载图片
 * @param imgUrl
 */
export const loadImage = function (imgUrl: string, isCross: boolean = false) {
  return new Promise<HTMLImageElement>((resolve, reject) => {
    const img = new Image();
    isCross && img.setAttribute('crossOrigin', 'anonymous');
    img.onload = () => {
      resolve(img);
    };
    img.onerror = () => {
      reject('图片加载失败');
    };
    img.src = imgUrl;
  });
};

/**
 * 根据URL下载文件
 * @param url
 */
export const downloadUrl = function (url: string) {
  const a = document.createElement('a');
  a.download = `file-${Date.now()}`;
  a.href = url;
  a.style.display = 'none';
  a.target = '_blank';
  document.body.appendChild(a);
  a.click();
  a.remove();
};

/**
 * 根据URL下载文件
 * @param url
 */
export const downloadImg = function (src: string) {
  const img = new Image();
  img.setAttribute('crossOrigin', 'anonymous');
  img.src = src;
  img.onload = () => {
    const canvas = document.createElement('canvas');
    canvas.width = img.width;
    canvas.height = img.height;
    const ctx = canvas.getContext('2d');
    ctx?.drawImage(img, 0, 0, img.width, img.height);
    const url = canvas.toDataURL();
    const a = document.createElement('a');
    a.download = `file-${Date.now()}`;
    a.href = url;
    document.body.appendChild(a);
    a.click();
    a.remove();
  };
};

interface DateFormatMap {
  [key: string]: number | string;
}

/**
 * 格式化日期
 * @param date {Date} 日期
 * @param format {string} 格式化字符串
 *   - y:年，M:月，d:日
 *   - h:时(12)，H:时(24)，m:分，s:秒
 *   - q:季度，a:上午|下午，A:AM|PM
 *   - w:星期(EN)，W:星期(CN)
 *   - 例：'yyyy-MM-dd W' = '1970-01-01 星期四'
 */
export const formatDate = function (date: Date, format: string = 'HH:mm') {
  const re = /(y+)/;
  if (re.test(format)) {
    const t = re.exec(format)![1];
    format = format.replace(t, (date.getFullYear() + '').substring(4 - t.length));
  }
  const CW = ['星期日', '星期一', '星期二', '星期三', '星期四', '星期五', '星期六'];
  const EW = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
  const o: DateFormatMap = {
    'M+': date.getMonth() + 1, // 月
    'd+': date.getDate(), // 日
    'h+': date.getHours() % 12 === 0 ? 12 : date.getHours() % 12, // 小时[12]
    'H+': date.getHours(), // 小时[24]
    'm+': date.getMinutes(), // 分
    's+': date.getSeconds(), // 秒
    'q+': Math.floor((date.getMonth() + 3) / 3), // 季度
    S: date.getMilliseconds(), // 毫秒
    a: date.getHours() < 12 ? '上午' : '下午', // 上午/下午
    A: date.getHours() < 12 ? 'AM' : 'PM', // AM/PM
    w: EW[date.getDay()],
    W: CW[date.getDay()]
  };
  for (let k in o) {
    const regx = new RegExp('(' + k + ')');
    if (regx.test(format)) {
      const t = regx.exec(format)![1];
      format = format.replace(t, t.length === 1 ? `${o[k]}` : `00${o[k]}`.slice(t.length * -1));
    }
  }
  return format;
};

/**
 * 将数据存入sessionStorage
 * @param name
 * @param value
 */
export const setSStoreItem = function (name: string, value: any) {
  // if (typeof value !== 'string') value = JSON.stringify(value);
  sessionStorage.setItem(name, (value = JSON.stringify(value)));
};

/**
 * 取出sessionStorage的项
 * @param name
 */
export const getSStoreItem = function (name: string) {
  const value = sessionStorage.getItem(name);
  if (value) return JSON.parse(value);
  return null;
};

/**
 * 将数据存入cookie
 * @param name
 * @param value
 * @param exp 有效天数
 */
export const setCookieItem = function (name: string, value: any, exp: number = 15) {
  const d = new Date();
  d.setTime(d.getTime() + exp * 24 * 60 * 60 * 1000);
  const expires = 'expires=' + d.toUTCString();
  document.cookie = name + '=' + value + '; ' + expires;
};

/**
 * 取出cookie的项
 * @param name
 */
export const getCookieItem = function (cname: string) {
  const name = cname + '=';
  const cList = document.cookie.split(';');
  for (let i = 0; i < cList.length; i++) {
    let cItem = cList[i].trim();
    if (cItem.indexOf(name) == 0) return cItem.substring(name.length, cItem.length);
  }
  return '';
};

/**
 * 判断数值是否为空
 * @param obj
 */
export const empty = function (obj: any): boolean {
  const str = JSON.stringify(obj);
  if (!str) return true;
  let flag = false;
  switch (str) {
    case '{}':
      flag = true;
      break;
    case '[]':
      flag = true;
      break;
    case '""':
      flag = true;
      break;
    case 'null':
      flag = true;
      break;
    case 'undefined':
      flag = true;
      break;
  }
  return flag;
};

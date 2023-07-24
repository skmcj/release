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

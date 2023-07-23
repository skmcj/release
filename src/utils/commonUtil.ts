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

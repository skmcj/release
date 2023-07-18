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

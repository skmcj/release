import { ref } from 'vue';
import { changeThemeMode } from '@/utils/commonUtil';

const theme = ref('');

const useTheme = () => {
  const setTheme = (newTheme: string) => {
    if (newTheme === 'dark') {
      theme.value = 'dark';
      changeThemeMode(1);
    } else {
      theme.value = 'light';
      changeThemeMode(0);
    }
  };
  return {
    theme,
    setTheme
  };
};

export default useTheme;

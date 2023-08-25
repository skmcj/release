import { ref } from 'vue';
import { defineStore } from 'pinia';
import type { RoleInfo } from '@/types';

export const useRoleInfoStore = defineStore(
  'roleinfo',
  () => {
    const roleinfo = ref<RoleInfo>({});
    const loginFlag = ref(false);
    const token = ref('');

    function setRoleInfo(info: RoleInfo) {
      roleinfo.value = info;
    }

    function setLoginFlag(flag: boolean) {
      loginFlag.value = flag;
    }

    function setToken(tk: string) {
      token.value = tk;
    }

    function clearLog() {
      loginFlag.value = false;
      roleinfo.value = {};
      token.value = '';
    }

    return { roleinfo, setRoleInfo, loginFlag, setLoginFlag, token, setToken, clearLog };
  },
  {
    persist: [
      {
        key: 'roleinfo',
        storage: sessionStorage,
        paths: ['roleinfo', 'loginFlag']
      },
      {
        key: 'rlToken',
        storage: localStorage,
        paths: ['token']
      }
    ]
  }
);

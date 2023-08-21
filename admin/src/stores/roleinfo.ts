import { ref } from 'vue';
import { defineStore } from 'pinia';

interface RoleInfo {
  username: string;
  role?: number;
  roleText?: string;
}

export const useRoleInfoStore = defineStore('roleinfo', () => {
  const roleinfo = ref<RoleInfo>({
    username: 'unknown'
  });
  function setRoleInfo(info: RoleInfo) {
    roleinfo.value = info;
  }

  return { roleinfo, setRoleInfo };
});

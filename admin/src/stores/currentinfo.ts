import { ref } from 'vue';
import { defineStore, storeToRefs } from 'pinia';
import type { CurrentInfo } from '@/types';

export const useCurrentInfoStore = defineStore(
  'currentinfo',
  () => {
    const currentinfo = ref<CurrentInfo>({});

    function setCurrent(info: CurrentInfo) {
      currentinfo.value = info;
    }

    function getCurrentId() {
      return currentinfo.value.id ?? '';
    }

    function setCurrentId(id: string) {
      currentinfo.value.id = id;
    }

    function getCurrentUserId() {
      return currentinfo.value.userId ?? '';
    }

    function setCurrentUserId(id: string) {
      currentinfo.value.userId = id;
    }

    return { currentinfo, setCurrent, getCurrentId, setCurrentId, getCurrentUserId, setCurrentUserId };
  },
  {
    persist: [
      {
        key: 'current',
        storage: sessionStorage,
        paths: ['currentinfo']
      }
    ]
  }
);

export const useCurrentInfoRefs = function () {
  const info = useCurrentInfoStore();
  return {
    ...storeToRefs(info),
    setCurrent: info.setCurrent,
    getCurrentUserId: info.getCurrentUserId,
    setCurrentUserId: info.setCurrentUserId,
    getCurrentId: info.getCurrentId,
    setCurrentId: info.setCurrentId
  };
};

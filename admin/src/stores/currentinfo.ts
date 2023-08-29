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

    function getCurrentLevelId() {
      return currentinfo.value.levelId ?? '';
    }

    function setCurrentLevelId(id: string) {
      currentinfo.value.levelId = id;
    }

    function getCurrentSentenceType() {
      return currentinfo.value.sentenceType ?? 0;
    }

    function setCurrentSentenceType(tp: number) {
      currentinfo.value.sentenceType = tp;
    }

    function getCurrentImageType() {
      return currentinfo.value.imageType ?? 0;
    }

    function setCurrentImageType(tp: number) {
      currentinfo.value.imageType = tp;
    }

    return {
      currentinfo,
      setCurrent,
      getCurrentId,
      setCurrentId,
      getCurrentUserId,
      setCurrentUserId,
      getCurrentSentenceType,
      setCurrentSentenceType,
      getCurrentImageType,
      setCurrentImageType,
      getCurrentLevelId,
      setCurrentLevelId
    };
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
    setCurrentId: info.setCurrentId,
    getCurrentSentenceType: info.getCurrentSentenceType,
    setCurrentSentenceType: info.setCurrentSentenceType,
    getCurrentImageType: info.getCurrentImageType,
    setCurrentImageType: info.setCurrentImageType,
    getCurrentLevelId: info.getCurrentLevelId,
    setCurrentLevelId: info.setCurrentLevelId
  };
};

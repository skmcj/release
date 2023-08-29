import { ref } from 'vue';
import { defineStore, storeToRefs } from 'pinia';

export const useViewParamsStore = defineStore('viewparams', () => {
  // 用户ID
  const userId = ref('');

  // 用户昵称
  const nickname = ref('');

  // 图片ID
  const imageId = ref('');

  const setUserId = function (id: string) {
    userId.value = id;
  };

  const setNickname = function (name: string) {
    nickname.value = name;
  };

  const setImageId = function (id: string) {
    imageId.value = id;
  };

  return { userId, setUserId, nickname, setNickname, imageId, setImageId };
});

export const useViewParamsRef = function () {
  const store = useViewParamsStore();
  return {
    ...storeToRefs(store),
    setUserId: store.setUserId,
    setNickname: store.setNickname,
    setImageId: store.setImageId
  };
};

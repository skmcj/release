import { ref } from 'vue';
import { defineStore, storeToRefs } from 'pinia';

export const useViewParamsStore = defineStore('viewparams', () => {
  // 用户ID
  const userId = ref('');

  // 用户昵称
  const nickname = ref('');

  const setUserId = function (id: string) {
    userId.value = id;
  };

  const setNickname = function (name: string) {
    nickname.value = name;
  };

  return { userId, setUserId, nickname, setNickname };
});

export const userViewParamsRef = function () {
  const store = useViewParamsStore();
  return {
    ...storeToRefs(store),
    setUserId: store.setUserId,
    setNickname: store.setNickname
  };
};

import { ref } from 'vue';
import { defineStore, storeToRefs } from 'pinia';

export const useViewParamsStore = defineStore('viewparams', () => {
  const userId = ref('');

  const setUserId = function (id: string) {
    userId.value = id;
  };

  return { userId, setUserId };
});

export const userViewParamsRef = function () {
  const store = useViewParamsStore();
  return {
    ...storeToRefs(store),
    setUserId: store.setUserId
  };
};

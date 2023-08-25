import { ref } from 'vue';
import { defineStore } from 'pinia';
import type { RouteRecordName } from 'vue-router';

interface RouteInfo {
  path: string;
  name: RouteRecordName;
  meta?: any;
  title?: string;
}

type MenuMap = {
  [key: RouteRecordName]: number;
};

const menuMap: MenuMap = {
  user: 0,
  sentence: 1,
  image: 2,
  level: 3,
  comment: 4,
  product: 5,
  role: 6
};

export const useRouteInfoStore = defineStore('routeinfo', () => {
  const activeIndex = ref(0);

  const routeinfo = ref<RouteInfo>({
    path: '/',
    name: 'user'
  });
  function setRouteInfo(info: RouteInfo) {
    routeinfo.value = info;
    activeIndex.value = menuMap[info.meta.bof];
    routeinfo.value.title = info.meta.title;
  }

  return { activeIndex, routeinfo, setRouteInfo };
});

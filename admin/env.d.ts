/// <reference types="vite/client" />

declare module 'element-plus/dist/locale/zh-cn.mjs';

declare module '*.vue' {
  import { ComponentOptions } from 'vue';
  const componentOptions: ComponentOptions;
  export default componentOptions;
}

import { computed, inject, ref, unref } from 'vue';

import type { InjectionKey, Ref } from 'vue';

export const isNumber = (val: any): val is number => typeof val === 'number';

const zIndex = ref(0);
export const defaultInitialZIndex = 2000;

export const zIndexContextKey: InjectionKey<Ref<number | undefined>> = Symbol('zIndexContextKey');

export const useZIndex = (zIndexOverrides?: Ref<number>) => {
  const zIndexInjection = zIndexOverrides || inject(zIndexContextKey, undefined);
  const initialZIndex = computed(() => {
    const zIndexFromInjection = unref(zIndexInjection);
    return isNumber(zIndexFromInjection) ? zIndexFromInjection : defaultInitialZIndex;
  });
  const currentZIndex = computed(() => initialZIndex.value + zIndex.value);

  const nextZIndex = () => {
    zIndex.value++;
    return currentZIndex.value;
  };

  return {
    initialZIndex,
    currentZIndex,
    nextZIndex
  };
};

export type UseZIndexReturn = ReturnType<typeof useZIndex>;

export class PopupManager {
  private static instance: UseZIndexReturn = useZIndex(computed(() => defaultInitialZIndex));
  constructor() {}
  public static initialZIndex = PopupManager.instance.initialZIndex;
  public static currentZIndex = PopupManager.instance.currentZIndex;
  public static nextZIndex = PopupManager.instance.nextZIndex;
}

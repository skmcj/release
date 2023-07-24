import { createVNode, render, isVNode } from 'vue';
import type { VNode, AppContext } from 'vue';
import MessageConstructor from './EasyMessage.vue';
import type { EsMessageProps } from './EasyMessage.vue';
import { PopupManager } from '@/utils/popup-manager';

interface MessageOptions extends EsMessageProps {
  appendTo?: HTMLElement | string;
}

const instances: VNode[] = [];
let seed = 0;

const message = (options: MessageOptions | string, context?: AppContext | null) => {
  if (typeof options === 'string') {
    options = { message: options };
  }
  let appendTo: HTMLElement | null = document.body;

  if (typeof options.appendTo === 'string') {
    appendTo = document.querySelector(options.appendTo);
  }
  if (!(appendTo instanceof HTMLElement)) {
    appendTo = document.body;
  }

  const props = {
    zIndex: PopupManager.nextZIndex(),
    id: seed++,
    onClose: () => {
      close(seed - 1);
    },
    ...options
  };

  let verticalOffset = options.offset || 20;
  instances.forEach(vInstance => {
    verticalOffset += (vInstance.el?.offsetHeight || 0) + 16;
  });

  props.offset = verticalOffset;

  const container = document.createElement('div');
  // container.className = 'message-container';

  const vm = createVNode(MessageConstructor, props, isVNode(props.message) ? { default: () => props.message } : null);

  vm.props!.onDestroy = () => {
    render(null, container);
  };

  render(vm, container);

  appendTo.appendChild(container.firstElementChild!);

  instances.push(vm);

  return {
    close: () => close(vm.props!.id as number)
  };
};

export const close = (vmId: number) => {
  const idx = instances.findIndex(vm => vm.props!.id === vmId);

  if (idx === -1) {
    return;
  }

  const vm = instances[idx];
  const removedHeight = vm.el!.offsetHeight;

  instances.splice(idx, 1);

  const len = instances.length;
  if (len === 0) {
    return;
  }

  for (let i = 0; i < len; i++) {
    // TODO Why when using `offsetHeight` will cause bug? And use `style.top` it will be ok?
    const pos = parseInt(instances[i].el!.style.top, 10) - removedHeight - 16;

    instances[i].component!.props.offset = pos;
  }
};

export default message;

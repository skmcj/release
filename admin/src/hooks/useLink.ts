import { onMounted, ref } from 'vue';

interface LinkOptions {
  href: string;
}

export function useLink(opts: LinkOptions, context: string = 'head') {
  const isLoading = ref(false);
  const error = ref(false);
  const success = ref(false);

  const promise = new Promise<HTMLLinkElement | string | Event>((resolve, reject) => {
    onMounted(() => {
      const link = document.createElement('link');
      link.rel = 'stylesheet';
      link.onload = function () {
        isLoading.value = false;
        success.value = true;
        error.value = false;
        resolve(link);
      };

      link.onerror = function (err) {
        isLoading.value = false;
        success.value = false;
        error.value = true;
        reject(err);
      };

      link.href = opts.href;
      switch (context) {
        case 'head':
          document.head.appendChild(link);
          break;
        default:
          document.body.appendChild(link);
          break;
      }
    });
  });

  return {
    isLoading,
    error,
    success,
    toPromise: () => promise
  };
}

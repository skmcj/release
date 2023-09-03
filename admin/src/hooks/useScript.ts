import { onMounted, ref } from 'vue';

interface ScriptOptions {
  src: string;
  async?: boolean;
}

export function useScript(opts: ScriptOptions, context: string = 'head') {
  const isLoading = ref(false);
  const error = ref(false);
  const success = ref(false);

  const promise = new Promise<HTMLScriptElement | string | Event>((resolve, reject) => {
    onMounted(() => {
      const script = document.createElement('script');
      script.type = 'text/javascript';
      script.onload = function () {
        isLoading.value = false;
        success.value = true;
        error.value = false;
        resolve(script);
      };

      script.onerror = function (err) {
        isLoading.value = false;
        success.value = false;
        error.value = true;
        reject(err);
      };

      script.src = opts.src;
      switch (context) {
        case 'head':
          document.head.appendChild(script);
          break;
        default:
          document.body.appendChild(script);
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

import axios from 'axios';
import type { AxiosRequestConfig, AxiosDefaults, HeadersDefaults, AxiosHeaderValue } from 'axios';
import { useRoleInfoStore } from '@/stores/roleinfo';
import { showStatus } from './status';
import { showMessage } from './commonUtil';
import { useRouter } from 'vue-router';

const request = axios.create({
  baseURL: 'http://localhost:8080/api',
  withCredentials: true
});

// 请求拦截
request.interceptors.request.use(
  function (config) {
    // 在发送请求之前做些什么
    const { token } = useRoleInfoStore();
    if (token) {
      config.headers['Authorization'] = token;
    }
    return config;
  },
  function (error) {
    // 对请求错误做些什么
    return Promise.reject(error);
  }
);

// 响应拦截
request.interceptors.response.use(
  function (response) {
    // 2xx 范围内的状态码都会触发该函数。
    // 如果请求头的['authorization']内有值，则保存
    // axios 请求头字段均为小写和-组合
    // 解构出响应头
    if (response.headers['authorization']) {
      const { setToken } = useRoleInfoStore();
      setToken(response.headers['authorization']);
    }
    const { data } = response;
    if (data.code === 423) {
      const router = useRouter();
      const { clearLog } = useRoleInfoStore();
      clearLog();
      showMessage('Token异常或失效，请重新登录', 'error', {
        onClose: () => {
          router.push('/login');
        }
      });
    }
    return data;
  },
  function (error) {
    // 超出 2xx 范围的状态码都会触发该函数。
    const { response } = error;
    if (response) {
      showStatus(response.status);
    } else {
      showMessage('网络异常，请稍后重试', 'warning');
    }
    // 对响应错误做点什么
    return Promise.reject(error);
  }
);

export interface CustomResponse<T = any> {
  code: number;
  data: T;
  msg: string;
}

export interface CustomRequest {
  <T = any, R = CustomResponse<T>, D = any>(config: AxiosRequestConfig<D>): Promise<R>;
  <T = any, R = CustomResponse<T>, D = any>(url: string, config?: AxiosRequestConfig<D>): Promise<R>;

  getUri(config?: AxiosRequestConfig): string;
  request<T = any, R = CustomResponse<T>, D = any>(config: AxiosRequestConfig<D>): Promise<R>;
  get<T = any, R = CustomResponse<T>, D = any>(url: string, config?: AxiosRequestConfig<D>): Promise<R>;
  delete<T = any, R = CustomResponse<T>, D = any>(url: string, config?: AxiosRequestConfig<D>): Promise<R>;
  head<T = any, R = CustomResponse<T>, D = any>(url: string, config?: AxiosRequestConfig<D>): Promise<R>;
  options<T = any, R = CustomResponse<T>, D = any>(url: string, config?: AxiosRequestConfig<D>): Promise<R>;
  post<T = any, R = CustomResponse<T>, D = any>(url: string, data?: D, config?: AxiosRequestConfig<D>): Promise<R>;
  put<T = any, R = CustomResponse<T>, D = any>(url: string, data?: D, config?: AxiosRequestConfig<D>): Promise<R>;
  patch<T = any, R = CustomResponse<T>, D = any>(url: string, data?: D, config?: AxiosRequestConfig<D>): Promise<R>;
  postForm<T = any, R = CustomResponse<T>, D = any>(url: string, data?: D, config?: AxiosRequestConfig<D>): Promise<R>;
  putForm<T = any, R = CustomResponse<T>, D = any>(url: string, data?: D, config?: AxiosRequestConfig<D>): Promise<R>;
  patchForm<T = any, R = CustomResponse<T>, D = any>(url: string, data?: D, config?: AxiosRequestConfig<D>): Promise<R>;

  defaults: Omit<AxiosDefaults, 'headers'> & {
    headers: HeadersDefaults & {
      [key: string]: AxiosHeaderValue;
    };
  };
}

export default request as CustomRequest;

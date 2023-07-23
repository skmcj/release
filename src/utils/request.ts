const BaseUrl = {
  Local: '/src/assets/data',
  LocalHost: 'http://localhost:8080'
};

type UNIMethod = 'GET' | 'DELETE' | 'HEAD' | 'OPTIONS' | 'POST' | 'PUT' | 'CONNECT' | 'TRACE';

export type Method =
  | 'GET'
  | 'DELETE'
  | 'HEAD'
  | 'OPTIONS'
  | 'POST'
  | 'PUT'
  | 'PATCH'
  | 'PURGE'
  | 'LINK'
  | 'UNLINK'
  | string;

export interface LoadingConfig {
  show: boolean;
  title?: string;
  mask?: boolean;
}

export interface RequestConfig<T = any> {
  method?: Method;
  url?: string;
  headers?: any;
  params?: any;
  data?: T;
  baseUrl?: string;
  loading?: LoadingConfig;
  [key: string]: any;
}

/**
 * 模仿 Axios 简易封装了 uni.request
 */

const typeOfTest = (type: string) => (thing: any) => typeof thing === type;

const isFunction = typeOfTest('function');

function forEach(obj: any, fn: Function, { allOwnKeys = false } = {}) {
  // 如果没有提供值
  if (obj === null || typeof obj === 'undefined') {
    return;
  }

  let i;
  let l;

  // 如果是没有迭代器的对象，强制为数组
  if (typeof obj !== 'object') {
    obj = [obj];
  }

  if (Array.isArray(obj)) {
    for (i = 0, l = obj.length; i < l; i++) {
      fn.call(null, obj[i], i, obj);
    }
  } else {
    // 迭代对象的键
    const keys = allOwnKeys ? Object.getOwnPropertyNames(obj) : Object.keys(obj);
    const len = keys.length;
    let key;

    for (i = 0; i < len; i++) {
      key = keys[i];
      fn.call(null, obj[key], key, obj);
    }
  }
}

function bind(fn: Function, thisArg: any) {
  return function wrap() {
    return fn.apply(thisArg, arguments);
  } as any;
}

const extend = (a: any, b: any, thisArg: any, allOwnKeys?: boolean) => {
  forEach(
    b,
    (val: any, key: any) => {
      if (thisArg && isFunction(val)) {
        a[key] = bind(val, thisArg);
      } else {
        a[key] = val;
      }
    },
    { allOwnKeys }
  );
  return a;
};

// 创建请求对象
function createRequest(config: RequestConfig) {
  const context = new Request(config);
  const request = bind(Request.prototype.request, context);
  // 拷贝prototype到实例上 类似于把Request的原型上的方法(例如: request、get、post...)继承到实例上，this指向为context
  extend(request, Request.prototype, context, true);
  // 拷贝上下文对象属性(默认配置和请求、相应拦截器对象)到实例上， this指向为context
  // extend(request, context, {});
  return request as CustomRequest;
}

/**
 * 简单地合并配置
 */
function mergeConfig(config1: RequestConfig = {}, config2: RequestConfig = {}) {
  config2 = config2 || {};
  const config: RequestConfig = {};
  Object.keys(Object.assign({}, config1, config2)).forEach(item => {
    const value = config2[item] || config1[item];
    config[item] = value;
  });
  return config;
}

function isUrl(url: string | undefined): boolean {
  if (!url) return false;
  const REG = /^(((ht|f)tps?):\/\/)/;
  return REG.test(url);
}

// 基于 uni.request 封装请求库
class Request {
  private defaults: RequestConfig;

  /**
   * 初始化
   */
  constructor(instanceConfig: RequestConfig) {
    this.defaults = instanceConfig;
  }

  /**
   * 基础请求
   */
  public request(config: RequestConfig) {
    // 判断参数类型，以支持不同的请求形式 request('url',config) / request(config)
    if (typeof config === 'string') {
      config = arguments[1] || {};
      config.url = arguments[0];
    } else {
      config = config || {};
    }

    config = mergeConfig(this.defaults, config);

    // 转化请求的方法 转化为大写
    if (config.method) {
      config.method = config.method.toUpperCase();
    } else if (this.defaults.method) {
      config.method = this.defaults.method.toUpperCase();
    } else {
      config.method = 'GET';
    }

    // 请求
    return new Promise((resolve, reject) => {
      const url = isUrl(config.url)
        ? (config.url as string)
        : config.baseUrl
        ? config.baseUrl + config.url
        : (config.url as string);
      fetch(url, {
        method: config.method,
        headers: config.headers,
        body: JSON.stringify(config.data)
      })
        .then(res => {
          if (res.ok) return Promise.all([res.json && res.json(), res.status, res.statusText]);
          else {
            return Promise.reject({
              code: res.status,
              msg: res.statusText,
              data: null
            });
          }
        })
        .then(([data, code, msg]) => {
          resolve({
            code,
            msg,
            data
          });
        })
        .catch(err => {
          reject(err);
        });
    });
  }

  /**
   * 请求
   */
  private generateRequest(url: string, data: any, config: RequestConfig) {
    return this.request(
      mergeConfig(config || {}, {
        method: config.method,
        url,
        data
      })
    );
  }

  /**
   * GET 请求
   */
  public get(
    url: string,
    data: any,
    config: RequestConfig = {
      loading: {
        show: true,
        title: '加载中',
        mask: false
      }
    }
  ) {
    return this.generateRequest(url, data, config);
  }

  /**
   * POST 请求
   */
  public post(url: string, data: any, config: RequestConfig) {
    return this.generateRequest(url, data, config);
  }

  /**
   * PUT 请求
   */
  public put(url: string, data: any, config: RequestConfig) {
    return this.generateRequest(url, data, config);
  }

  /**
   * DELETE 请求
   */
  public delete(url: string, data: any, config: RequestConfig) {
    return this.generateRequest(url, data, config);
  }
}

export interface CustomRequest {
  <T>(config: RequestConfig): Promise<T>;
  get<T = any>(url: string, data?: any, config?: RequestConfig): Promise<CustomResponse<T>>;
  post<T = any>(url: string, data?: any, config?: RequestConfig): Promise<CustomResponse<T>>;
  put<T = any>(url: string, data?: any, config?: RequestConfig): Promise<CustomResponse<T>>;
  delete<T = any>(url: string, data?: any, config?: RequestConfig): Promise<CustomResponse<T>>;
}

export interface CustomResponse<T> {
  code?: number;
  msg?: string;
  data: T;
}

const defaultConfig = {
  baseUrl: BaseUrl.Local
};

export default createRequest(defaultConfig);

/**
 * 导入：import request(随意) from '该文件位置';
 * 使用：
 * 1、request({url: '', method: '', ...})
 * 2、request.get(url)
 * 以上均返回请求结果的Promise
 * 使用方法类似Axios
 */

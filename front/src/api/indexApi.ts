import request from '@/utils/request';

/**
 * 获取当current信息
 */
export const getCurrentApi = function () {
  return request.get<Result<Current>>('/current');
};

/**
 * 获取用户信息
 * @param id
 */
export const getUserInfoApi = function (id = '') {
  return request.get<Result<UserInfo>>(`/user/all/${id}`);
};

/**
 * 获取每日一言
 */
export const getSTDailyApi = function () {
  return request.get<Result<Sentence>>('/sentence/daily');
};
export const getSTRandomApi = function () {
  return request.get<Result<Sentence>>('/sentence/random');
};

/**
 * 获取每日一图
 */
export const getImgDailyApi = function (type = 'pc') {
  return request.get<Result<Image>>(`/image/daily?type=${type}`);
};
export const getImgRandomApi = function (type = 'pc') {
  return request.get<Result<Image>>(`/image/random?type=${type}`);
};

/**
 * 获取境界标签
 */
export const getLevelListApi = function (id = '0') {
  return request.get<Result<Level>>(`/level/${id}`);
};

/**
 * 获取作品列表
 */
export const getProductApi = function (page = 1, pageSize = 3) {
  return request.get<Result<Page<Product>>>(`/product?page=${page}&pageSize=${pageSize}`);
};

/**
 * 获取留言列表
 */
export const getCommentApi = function (page = 1, pageSize = 5) {
  return request.get<Result<Page<Comment>>>(`/comment?page=${page}&pageSize=${pageSize}`);
};

/**
 * 新增留言
 */
export const setCommentApi = function (data: any) {
  return request.post<Result<null>>('/comment', data);
};

export interface Result<T> {
  code: number;
  data: T;
  msg: string;
}

export interface Page<T> {
  list: T[];
  total: number;
  totalPage: number;
  currentPage: number;
  pageSize: number;
}

export interface Current {
  userId: string;
  levelId: string;
  sentenceType: number;
  imageType: number;
}

export interface UserInfo {
  id: string;
  nickname: string;
  address: string;
  avatar: string;
  sex: number;
  level: number;
  year: number;
  author: string;
  social: Social[];
  days: number;
  startTime: string;
}

export interface Social {
  id: number;
  icon: string;
  label: string;
  link: string;
  disabled: boolean;
  sort: number;
  userId: number;
}

export interface Sentence {
  id: string;
  content: string;
  disabled: boolean;
  showDate: string;
}

export interface Image {
  id: string;
  name: string;
  url: string;
  type: string;
  labels: string[];
  w: number;
  h: number;
  size: number;
  disabled: boolean;
  imageType: string;
  showDate: string;
}

export interface Level {
  id: string;
  labels: string[];
  tip: string;
  sort: number;
}

export interface Product {
  logoUrl: string;
  id: string;
  name: string;
  tip: string;
  logo: string;
  disabled: false;
  article: Article;
  labels: ProductLabel[];
  articleId: string;
  compDate: string;
  stars: string;
}

export interface Article {
  id: string;
  title: string;
  path: string;
  cate: string;
  cover: string;
  tags: string[];
  description: string;
  count: number;
  eyes: number;
  disabled: boolean;
}

export interface ProductLabel {
  id: string;
  icon: string;
  label: string;
  color: string;
  link: string;
  sort: number;
  disabled: boolean;
  productId: string;
}

export interface Comment {
  id: string;
  nickname: string;
  content: string;
  address: string;
  showDate: string;
}

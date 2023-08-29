// role
export interface RoleInfo {
  username?: string;
  role?: number;
  roleText?: string;
}

// user
export interface UserInfo {
  id?: string;
  nickname: string;
  address: string;
  avatar: string;
  sex?: number;
  level: number;
  year: number | string;
  author: string;
  startTime: string;
}

export interface UserShortInfo {
  id: string;
  nickname: string;
}

export interface Social {
  id?: string;
  icon?: string;
  label?: string;
  link?: string;
  disabled?: boolean;
  sort?: number;
  userId?: string;
  createTime?: string;
  updateTime?: string;
}

// sentence
export interface Sentence {
  id?: string;
  content: string;
  disabled?: boolean;
  showDate?: null | string;
}

// current
export interface CurrentInfo {
  id?: string;
  sort?: number;
  userId?: string;
  levelId?: string;
  sentenceType?: number;
  imageType?: number;
}

// image
export interface ImageURes {
  size: number;
  name: string;
  type: string;
  url: string;
}

export type ImageShowType = 'pc' | 'phone' | 'other';
export type ImageType = 'jpg' | 'png' | 'gif' | 'jpeg' | 'apng' | 'avif' | 'svg' | 'webp' | 'jif' | 'ico' | 'tiff';

export interface ImageInfo {
  id?: string;
  name?: string;
  url?: string;
  type?: ImageShowType;
  labels?: string[];
  w?: number;
  h?: number;
  size?: number;
  disabled?: boolean;
  imageType?: ImageType;
  showDate?: null | string;
  createTime?: string;
  updateTime?: string;
}

// page
export interface Page<T = any> {
  list: T[];
  total: number;
  totalPage: number;
  currentPage: number;
  pageSize: number;
}

// 页面传参
export interface ViewParams {
  userId: string;
}

// role
export interface RoleInfo {
  id?: string;
  username?: string;
  password?: string;
  role?: number;
  roleText?: string;
  disabled?: boolean;
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

// 境界
export interface LevelInfo {
  id?: string;
  labels?: string[];
  tip?: string;
  sort?: number;
  createTime?: string;
  updateTime?: string;
}

// 留言
export interface CommentInfo {
  id?: string;
  nickname?: string;
  content?: string;
  email?: string;
  visible?: boolean;
  disabled?: boolean;
  ip?: string;
  address?: string;
  showDate?: string;
  createTime?: string;
  updateTime?: string;
  iprf?: boolean;
}

export interface LevelShortInfo {
  id: string;
  tip: string;
}

// product
export interface ProductInfo {
  id?: string;
  name?: string;
  tip?: string;
  logo?: string;
  logoUrl?: string;
  stars?: string;
  disabled?: boolean;
  articleId?: string;
  compDate?: string;
  createTime?: string;
  updateTime?: string;
}

export interface ProductLabel {
  id?: string;
  productId?: string;
  icon?: string;
  label?: string;
  color?: string;
  link?: string;
  sort?: number;
  disabled?: boolean;
  createTime?: string;
  updateTime?: string;
}

export interface ProductShortInfo {
  id: string;
  name: string;
}

// 文章
export interface ArticleShortInfo {
  id: string;
  title: string;
}

export interface ArticleInfo {
  id?: string;
  title?: string;
  path?: string;
  cate?: string | null;
  cover?: string;
  tags?: string[];
  description?: string;
  count?: number;
  eyes?: number;
  disabled?: boolean;
  createTime?: string;
  updateTime?: string;
}

export interface ArticleContent {
  id: string;
  content: string;
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

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

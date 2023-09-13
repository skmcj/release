<p align=center>
  <a href="https://github.com/skmcj/release">
    <img src="https://static.ltgcm.top/md/20230911214858.png" alt="个人发布页" style="width: 200px">
  </a>
</p>


<p align=center style="font-weight: bold;">
   作品发布页
</p>

## 简介

一个拟态风格的个人发布页，可用于展示一些个人作品的信息。

## 技术栈

- **前端：** `Vue3 + Ts + Vite + Vue-Router + Pina + Element-Plus + Editor.md`
- **后端：** `Thinkphp8 + Php-Jwt + MySQL`

## 功能介绍

- 拟态风UI，可切换**浅色**和**深色**两种模式，适配`PC`及`H5`两种界面
- 数码时钟、日期、圆形时钟展示
  - 实时展示系统时间
- 星座运势展示、天气温度展示
  - 可设置默认展示星座，也可任意选择其它十二星座
  - 天气会自动获取访问者`ip`，展示对应的温度、天气、风向等
- 社交信息展示
  - 以一图标按钮形式展示，点击可跳转到对应社交站点
- 每日一图、每日一言展示
  - 两种展示模式：`random`、`daily`
  - `random` — 每刷新一次就随机获取一次
  - `daily` — 每天展示不同的，当天刷新不变
- 留言展示
- 作品展示
- 境界展示
- 以上功能都有其对应的后端管理

## 预览

- [哔哩哔哩-拟态风个人页](https://www.bilibili.com/video/BV1j14y1r7W6)

- 前台

  ![theme-light](https://static.ltgcm.top/md/20230911222103.png)

  ![theme-dark](https://static.ltgcm.top/md/20230911222421.png)

  ![h5](https://static.ltgcm.top/md/20230912144609.png)

- 后台管理(部分)

  ![admin-login](https://static.ltgcm.top/md/20230912143233.png)
  
  ![admin-user](https://static.ltgcm.top/md/20230912143544.png)
  
  ![admin-sentence](https://static.ltgcm.top/md/20230912143847.png)
  
  ![admin-image](https://static.ltgcm.top/md/20230912144018.png)
  
  ![admin-editor](https://static.ltgcm.top/md/20230912144353.png)
  
  ![admin-setting](https://static.ltgcm.top/md/20230912144402.png)



## 部署



- 后端部署配置，以`nginx`为例

  ```nginx
  # 接口转发
  location /api {
  	if (!-f $request_filename) {
  		rewrite  ^/api/(.*)$  /backend/public/index.php/$1  last;
  	}
  }
  # 文章转发
  location /article {
  	if (!-f $request_filename) {
  		rewrite  ^/article/(.*)$  /api/article/$1  last;
  	}
  }
  # 本地存储转发
  location /oss {
  	if (!-f $request_filename) {
  		rewrite  ^/oss/(.*)$  /backend/public/storage/$1  last;
  	}
  }
  # 静态资源转发
  location /static {
  	if (!-f $request_filename) {
  		rewrite  ^/static/(.*)$  /backend/public/static/$1  last;
  	}
  }
  # 上传文件缓存转发
  location /tmp {
  	if (!-f $request_filename) {
  		rewrite  ^/tmp/(.*)$  /backend/public/tmp/$1  last;
  	}
  }
  ```

  - 其中，`backend`为后端`Api`项目的存放路径

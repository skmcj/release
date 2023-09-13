## release数据库表介绍文档



- 个人信息表-user

| 字段        | 类型     | 长度 | 非空 | 键   | 备注            |
| ----------- | -------- | ---- | ---- | ---- | --------------- |
| id          | bigint   |      | ✔    | 🔑    | 信息ID          |
| nickname    | varchar  | 64   | ✔    |      | 昵称            |
| address     | varchar  | 64   | ✔    |      | 地址            |
| avatar      | varchar  | 256  | ✔    |      | 头像            |
| sex         | int      | 1    |      |      | 性别            |
| level       | int      |      |      |      | 境界分数：0-100 |
| year        | int      |      | ✔    |      | 年份            |
| author      | varchar  | 32   | ✔    |      | 作者            |
| start_time  | datetime |      | ✔    |      | 开始时间        |
| create_time | datetime |      |      |      | 创建时间        |
| update_time | datetime |      |      |      | 更新时间        |

- 社交信息表-social

| 字段        | 类型     | 长度 | 非空 | 键   | 备注                     |
| ----------- | -------- | ---- | ---- | ---- | ------------------------ |
| id          | bigint   |      | ✔    | 🔑    | ID                       |
| user_id     | bigint   |      | ✔    |      | 用户ID                   |
| icon        | varchar  | 128  |      |      | 按钮                     |
| label       | varchar  | 32   |      |      | 标签                     |
| link        | varchar  | 256  |      |      | 链接                     |
| disabled    | int      |      |      |      | 是否禁用；0-正常；1-禁用 |
| sort        | int      |      |      |      | 排序                     |
| create_time | datetime |      |      |      | 创建时间                 |
| update_time | datetime |      |      |      | 更新时间                 |

- 每日一句表-sentence

| 字段        | 类型     | 长度 | 非空 | 键   | 备注     |
| ----------- | -------- | ---- | ---- | ---- | -------- |
| id          | bigint   |      | ✔    | 🔑    | ID       |
| content     | varchar  | 256  | ✔    |      | 句子内容 |
| show_date   | datetime |      |      |      | 显示日期 |
| disabled    | int      |      |      |      | 是否可用 |
| create_time | datetime |      |      |      | 创建时间 |
| update_time | datetime |      |      |      | 更新时间 |

- 每日一图表-image

| 字段        | 类型     | 长度 | 非空 | 键   | 备注                    |
| ----------- | -------- | ---- | ---- | ---- | ----------------------- |
| id          | bigint   |      | ✔    | 🔑    | ID                      |
| name        | varchar  | 128  |      |      | 图片名称                |
| url         | varchar  | 256  | ✔    |      | 图片链接                |
| image_type  | varchar  | 128  |      |      | 图片类型                |
| type        | int      | 1    | ✔    |      | 显示类型：0-Pc; 1-phone |
| labels      | varchar  | 256  |      |      | 标签                    |
| w           | int      |      |      |      | 宽                      |
| h           | int      |      |      |      | 高                      |
| size        | int      |      |      |      | 图片大小，字节          |
| disabled    | int      |      |      |      | 是否可用                |
| show_date   | datetime |      |      |      | 指定显示日期            |
| create_time | datetime |      |      |      | 创建时间                |
| update_time | datetime |      |      |      | 更新时间                |

- 留言表-comment

| 字段        | 类型     | 长度 | 非空 | 键   | 备注                     |
| ----------- | -------- | ---- | ---- | ---- | ------------------------ |
| id          | bigint   |      | ✔    | 🔑    | ID                       |
| nickname    | varchar  | 64   | ✔    |      | 昵称                     |
| content     | varchar  | 256  | ✔    |      | 内容                     |
| email       | varchar  | 256  | ✔    |      | 邮箱                     |
| visibile    | int      | 1    |      |      | 可见性，0-可见；1-不可见 |
| disabled    | int      |      |      |      | 是否可用                 |
| ip          | varchar  | 128  |      |      | 所属地IP                 |
| address     | varchar  | 255  |      |      | 所属地                   |
| create_time | datetime |      |      |      | 创建时间                 |
| update_time | datetime |      |      |      | 更新时间                 |

- 境界表-level

| 字段        | 类型     | 长度 | 非空 | 键   | 备注     |
| ----------- | -------- | ---- | ---- | ---- | -------- |
| id          | bigint   |      | ✔    | 🔑    | ID       |
| tip         | varchar  | 16   |      |      | 标识     |
| labels      | varchar  | 256  | ✔    |      | 标签名   |
| sort        | int      |      | ✔    |      | 排序依据 |
| create_time | datetime |      |      |      | 创建时间 |
| update_time | datetime |      |      |      | 更新时间 |

- 作品表-product

| 字段        | 类型     | 长度 | 非空 | 键   | 备注                 |
| ----------- | -------- | ---- | ---- | ---- | -------------------- |
| id          | bigint   |      | ✔    | 🔑    | ID                   |
| article_id  | bigint   |      |      |      | 简介文档ID           |
| name        | varchar  | 64   | ✔    |      | 作品名称             |
| tip         | varchar  | 256  |      |      | 作品简介             |
| logo        | varchar  | 256  |      |      | 作品logo             |
| comp_date   | datetime |      |      |      | 完成日期             |
| stars       | varchar  | 255  |      |      | github stars标签link |
| disabled    | int      |      |      |      | 是否可用             |
| create_time | datetime |      |      |      | 创建时间             |
| update_time | datetime |      |      |      | 更新时间             |

> - stars示例：https://img.shields.io/github/stars/skmcj/dycast

- 作品标签表-product_label

| 字段        | 类型     | 长度 | 非空 | 键   | 备注      |
| ----------- | -------- | ---- | ---- | ---- | --------- |
| id          | bigint   |      | ✔    | 🔑    | ID        |
| product_id  | bigint   |      |      |      | 作品ID    |
| icon        | varchar  | 128  | ✔    |      | 图标      |
| label       | varchar  | 64   | ✔    |      | 标签      |
| color       | varchar  | 64   |      |      | CSS颜色值 |
| link        | varchar  | 256  |      |      | 链接      |
| sort        | int      |      |      |      | 排序      |
| disabled    | int      | 1    |      |      | 可用性    |
| create_time | datetime |      |      |      | 创建时间  |
| update_time | datetime |      |      |      | 更新时间  |

- 文章表-article

| 字段        | 类型     | 长度 | 非空 | 键   | 备注                       |
| ----------- | -------- | ---- | ---- | ---- | -------------------------- |
| id          | bigint   |      | ✔    | 🔑    | ID                         |
| title       | varchar  | 64   |      |      | 标题                       |
| path        | varchar  | 256  |      |      | 路径                       |
| cate        | varchar  | 256  |      |      | 类别                       |
| cover       | varchar  | 256  |      |      | 封面                       |
| tags        | varchar  | 256  |      |      | 标签                       |
| description | varchar  | 256  |      |      | 描述                       |
| eyes        | int      |      |      |      | 阅读量                     |
| count       | int      |      |      |      | 字数                       |
| disabled    | int      | 1    |      |      | 是否可见，0-可见；1-不可见 |
| create_time | datetime |      |      |      |                            |
| update_time | datetime |      |      |      |                            |

- 角色表-role

| 字段        | 类型     | 长度 | 非空 | 键   | 备注                   |
| ----------- | -------- | ---- | ---- | ---- | ---------------------- |
| id          | bigint   |      | ✔    | 🔑    | ID                     |
| username    | varchar  | 256  | ✔    |      | 用户名                 |
| password    | varchar  | 256  | ✔    |      | 密码                   |
| role        | int      | 1    |      |      | 角色，0-所有者；1-管理 |
| disabled    | int      |      |      |      | 是否可用               |
| create_time | datetime |      |      |      | 创建时间               |
| update_time | datetime |      |      |      | 更新时间               |

> 所有者：拥有全部权限，可添加管理

- 前台展示设置表-current

| 字段          | 类型     | 长度 | 非空 | 键   | 备注                           |
| ------------- | -------- | ---- | ---- | ---- | ------------------------------ |
| id            | bigint   |      | ✔    | 🔑    | ID                             |
| user_id       | bigint   |      |      |      | 用户ID                         |
| level_id      | bigint   |      |      |      | 境界ID                         |
| sentence_type | int      |      |      |      | 每日一言类型，0-daily;1-random |
| gray_mode     | int      |      |      |      | 展示模式                       |
| gray_date     | varchar  |      |      |      | 指定日期                       |
| sort          | int      |      |      |      | 排序                           |
| create_time   | datetime |      |      |      | 创建时间                       |
| update_time   | datetime |      |      |      | 更新时间                       |

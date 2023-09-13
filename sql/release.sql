/*
 Source Host           : localhost:3306
 Source Schema         : release

 Target Server Type    : MySQL
 Target Server Version : 80026
 File Encoding         : 65001

 Date: 09/09/2023 15:14:32
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for article
-- ----------------------------
DROP TABLE IF EXISTS `article`;
CREATE TABLE `article`  (
  `id` bigint NOT NULL COMMENT 'ID',
  `title` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '标题',
  `path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '路径',
  `cate` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '分类',
  `cover` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '封面',
  `tags` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '标签',
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '描述',
  `count` int NULL DEFAULT 0 COMMENT '字数',
  `eyes` int NULL DEFAULT 0 COMMENT '阅读量',
  `disabled` int NULL DEFAULT 0 COMMENT '是否可见，0-可见；1-不可见',
  `create_time` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of article
-- ----------------------------

-- ----------------------------
-- Table structure for comment
-- ----------------------------
DROP TABLE IF EXISTS `comment`;
CREATE TABLE `comment`  (
  `id` bigint NOT NULL COMMENT 'id',
  `nickname` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '昵称',
  `content` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '内容',
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '邮箱',
  `visible` int NULL DEFAULT 0 COMMENT '可见性(用户控制)：0-可见；1-不可见(仅站长后台可见)',
  `disabled` int NULL DEFAULT 0 COMMENT '可用性(站长控制)：0-可用；1-禁用',
  `ip` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT 'IP地址',
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT 'IP所属地',
  `create_time` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of comment
-- ----------------------------
INSERT INTO `comment` VALUES (15503831026633688, '测试者', '一条测试留言', 'test@qq.com', 0, 0, '127.0.0.1', '内网IP', '2023-08-18 18:46:41', '2023-08-18 18:46:41');

-- ----------------------------
-- Table structure for current
-- ----------------------------
DROP TABLE IF EXISTS `current`;
CREATE TABLE `current`  (
  `id` bigint NOT NULL COMMENT 'ID',
  `user_id` bigint NOT NULL COMMENT '用户ID',
  `level_id` bigint NOT NULL COMMENT '境界ID',
  `sentence_type` int NULL DEFAULT 0 COMMENT '每日一言类型，0-daily；1-random',
  `image_type` int NULL DEFAULT 0 COMMENT '每日一图类型，0-daily；1-random',
  `gray_mode` int NULL DEFAULT 0 COMMENT '灰度模式，0-正常；1-灰屏；2-指定日期灰屏',
  `gray_date` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '指定灰屏日期',
  `sort` int NULL DEFAULT 0 COMMENT '排序',
  `create_time` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of current
-- ----------------------------
INSERT INTO `current` VALUES (15110242022983244, 11506737211245885, 11867932787215171, 0, 0, 0, '[\"12-13\"]', 7, '2023-08-17 16:42:42', '2023-09-04 16:18:54');

-- ----------------------------
-- Table structure for image
-- ----------------------------
DROP TABLE IF EXISTS `image`;
CREATE TABLE `image`  (
  `id` bigint NOT NULL COMMENT 'id',
  `name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '图片名称',
  `url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '图片链接',
  `image_type` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '图片类型',
  `type` int NOT NULL DEFAULT 0 COMMENT '显示类型：0-PC;1-phone;2-未知',
  `labels` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '标签',
  `w` int NULL DEFAULT NULL COMMENT '宽',
  `h` int NULL DEFAULT NULL COMMENT '高',
  `size` int NULL DEFAULT 0 COMMENT '图片大小，字节',
  `disabled` int NULL DEFAULT 0 COMMENT '是否禁用，0-可用；1-禁用',
  `show_date` datetime NULL DEFAULT NULL COMMENT '显示日期',
  `create_time` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of image
-- ----------------------------
INSERT INTO `image` VALUES (12548119182970265, '一个身穿白衣的女子', 'https://w.wallhaven.cc/full/ex/wallhaven-exwgw8.png', 'png', 0, '[\"长发\",\"女孩\",\"微笑\",\"厚涂\"]', 2160, 1215, 3907440, 0, NULL, '2023-08-10 15:01:45', '2023-08-10 15:01:45');
INSERT INTO `image` VALUES (12561138579407268, '残破的手术室', 'https://w.wallhaven.cc/full/ex/wallhaven-exw528.jpg', 'jpg', 0, '[\"CGI\",\"医院\",\"混乱\",\"残破\"]', 3840, 1883, 1881163, 0, NULL, '2023-08-10 15:53:29', '2023-08-10 15:53:29');
INSERT INTO `image` VALUES (12561139988693426, '下水抓鱼的喵', 'https://w.wallhaven.cc/full/qz/wallhaven-qzmlj5.png', 'png', 0, '[\"猫\",\"鱼\",\"喵\",\"水下\"]', 2560, 1536, 4424942, 0, NULL, '2023-08-10 15:53:29', '2023-08-10 15:53:29');
INSERT INTO `image` VALUES (12561139992888953, '两个喝咖啡的女孩', 'https://w.wallhaven.cc/full/l8/wallhaven-l82pwp.jpg', 'jpg', 0, '[\"女孩\",\"咖啡\",\"明日方舟\"]', 4096, 2013, 1252048, 0, NULL, '2023-08-10 15:53:29', '2023-08-10 15:53:29');
INSERT INTO `image` VALUES (12561139997082671, '残破站台', 'https://w.wallhaven.cc/full/vq/wallhaven-vqdpzm.jpg', 'jpg', 0, '[\"残破\",\"空中\",\"站台\"]', 4000, 2371, 13006424, 0, NULL, '2023-08-10 15:53:29', '2023-08-10 15:53:29');
INSERT INTO `image` VALUES (12561140001278655, '银狼', 'https://w.wallhaven.cc/full/gp/wallhaven-gp2gpe.png', 'png', 0, '[\"紫发\",\"银狼\"]', 1334, 827, 1169818, 0, '2023-08-30 00:00:00', '2023-08-10 15:53:29', '2023-08-29 13:33:58');
INSERT INTO `image` VALUES (12561140005470641, '紫发女孩', 'https://w.wallhaven.cc/full/d6/wallhaven-d6wlgg.jpg', 'jpg', 1, '[\"动漫\",\"女孩\",\"紫发\"]', 1434, 2559, 306013, 0, NULL, '2023-08-10 15:53:29', '2023-08-10 15:53:29');
INSERT INTO `image` VALUES (12561140009668528, '教室JK女孩', 'https://w.wallhaven.cc/full/m3/wallhaven-m3g2vm.jpg', 'jpg', 1, '[\"动漫\",\"女孩\",\"黄发\",\"JK\"]', 1500, 2491, 4950275, 0, NULL, '2023-08-10 15:53:29', '2023-08-10 15:53:29');
INSERT INTO `image` VALUES (12561140013859457, '雷神拔刀', 'https://w.wallhaven.cc/full/5g/wallhaven-5gr275.jpg', 'jpg', 1, '[\"原神\",\"雷神\",\"拔刀\",\"紫发\"]', 816, 1456, 200395, 0, NULL, '2023-08-10 15:53:29', '2023-08-10 15:53:29');
INSERT INTO `image` VALUES (12561140013859458, '可爱学生', 'https://w.wallhaven.cc/full/gp/wallhaven-gpmmmq.jpg', 'jpg', 1, '[\"女孩\",\"学生\",\"沙发\"]', 1500, 2490, 3536139, 0, NULL, '2023-08-10 15:53:29', '2023-08-10 15:53:29');
INSERT INTO `image` VALUES (12561140018056125, '恶魔学生妹', 'https://w.wallhaven.cc/full/yx/wallhaven-yx2jod.jpg', 'jpg', 1, '[\"恶魔\",\"JK\",\"黑丝\"]', 2441, 4096, 1752407, 0, NULL, '2023-08-10 15:53:29', '2023-08-10 15:53:29');
INSERT INTO `image` VALUES (19196851269929089, '原神合照', 'https://w.wallhaven.cc/full/p9/wallhaven-p97e9e.jpg', 'jpg', 0, '[\"原神\"]', 2560, 1440, 2787059, 0, NULL, '2023-08-28 23:21:26', '2023-08-29 12:02:17');
INSERT INTO `image` VALUES (19389515839835873, 'Jason Lan', 'https://w.wallhaven.cc/full/ex/wallhaven-exeqyk.jpg', 'jpg', 1, '[\"连衣裙\",\"大长腿\"]', 1638, 2048, 478863, 0, NULL, '2023-08-29 12:07:01', '2023-08-29 12:07:01');
INSERT INTO `image` VALUES (19408616989655384, '绿意盎然的房间', 'https://w.wallhaven.cc/full/yx/wallhaven-yxgyyx.jpg', 'jpg', 0, '[\"画\",\"植物\",\"室内\"]', 2360, 1327, 658646, 0, NULL, '2023-08-29 13:22:55', '2023-08-29 13:30:27');
INSERT INTO `image` VALUES (19408617417476688, '晒太阳的小男孩', 'https://w.wallhaven.cc/full/vq/wallhaven-vq5193.jpg', 'jpg', 0, '[\"阳光\",\"猫\",\"狗\",\"悠闲\"]', 2837, 1947, 4144663, 0, NULL, '2023-08-29 13:22:55', '2023-08-29 13:29:29');
INSERT INTO `image` VALUES (19474522121961865, '崩坏3-布洛妮娅·扎伊切克', 'https://w.wallhaven.cc/full/p9/wallhaven-p97zmj.png', 'png', 0, '[\"崩坏3\",\"白发\"]', 4759, 2677, 10340570, 0, NULL, '2023-08-29 17:44:48', '2023-08-29 17:44:48');

-- ----------------------------
-- Table structure for level
-- ----------------------------
DROP TABLE IF EXISTS `level`;
CREATE TABLE `level`  (
  `id` bigint NOT NULL COMMENT 'id',
  `labels` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '标签组',
  `tip` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '标签标识',
  `sort` int NOT NULL DEFAULT 0 COMMENT '排序依据',
  `create_time` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of level
-- ----------------------------
INSERT INTO `level` VALUES (11867932787215171, '[\"成仙\",\"渡劫\",\"大乘\",\"合体\",\"炼虚\",\"化神\",\"元婴\",\"结丹\",\"筑基\",\"练气\"]', '修仙', 3, '2023-08-08 17:58:56', '2023-08-08 17:58:56');

-- ----------------------------
-- Table structure for product
-- ----------------------------
DROP TABLE IF EXISTS `product`;
CREATE TABLE `product`  (
  `id` bigint NOT NULL COMMENT 'id',
  `article_id` bigint NULL DEFAULT NULL COMMENT '作品简介文档ID',
  `name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '作品名称',
  `tip` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '作品简介',
  `logo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '作品logo',
  `comp_date` datetime NULL DEFAULT NULL COMMENT '完成日期',
  `stars` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT 'github stars 数',
  `disabled` int NULL DEFAULT 0 COMMENT '是否显示，0-正常；1-隐藏',
  `create_time` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;


-- ----------------------------
-- Table structure for product_label
-- ----------------------------
DROP TABLE IF EXISTS `product_label`;
CREATE TABLE `product_label`  (
  `id` bigint NOT NULL COMMENT 'id',
  `product_id` bigint NULL DEFAULT NULL COMMENT '作品ID',
  `icon` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '图标',
  `label` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '标签',
  `color` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '颜色',
  `link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '链接',
  `sort` int NULL DEFAULT 0 COMMENT '排序依据',
  `disabled` int NULL DEFAULT 0 COMMENT '可用性',
  `create_time` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for role
-- ----------------------------
DROP TABLE IF EXISTS `role`;
CREATE TABLE `role`  (
  `id` bigint NOT NULL COMMENT 'id',
  `username` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '用户名',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '密码',
  `role` int NULL DEFAULT 1 COMMENT '角色，0-所有者；1-管理',
  `disabled` int NULL DEFAULT 0 COMMENT '状态，0-正常；1-禁用',
  `create_time` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of role
-- 根用户：可自行设置
-- username: root_admin
-- password: 123456（md5加密）
-- ----------------------------
INSERT INTO `role` VALUES (14762905601838761, 'root_admin', 'e10adc3949ba59abbe56e057f20f883e', 0, 0, '2023-08-16 17:45:57', '2023-08-16 17:45:57');

-- ----------------------------
-- Table structure for sentence
-- ----------------------------
DROP TABLE IF EXISTS `sentence`;
CREATE TABLE `sentence`  (
  `id` bigint NOT NULL COMMENT 'id',
  `content` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '句子内容',
  `show_date` datetime NULL DEFAULT NULL COMMENT '显示日期',
  `disabled` int NULL DEFAULT 0 COMMENT '是否可以，0-可用；1-禁用',
  `create_time` datetime NULL DEFAULT NULL COMMENT '创建日期',
  `update_time` datetime NULL DEFAULT NULL COMMENT '更新日期',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of sentence
-- ----------------------------
INSERT INTO `sentence` VALUES (11944639326061179, '如果我是木乃伊的话，那么属于我的那座金字塔在哪？', NULL, 0, '2023-08-08 23:03:44', '2023-08-25 18:46:33');
INSERT INTO `sentence` VALUES (12187722441559288, '连我你都不在乎，那你在乎什么，在呼伦贝尔吗？', '2023-08-10 00:00:00', 0, '2023-08-09 15:09:39', '2023-08-09 17:34:03');
INSERT INTO `sentence` VALUES (12187724148641613, '打野玩的好你叫野王，法师玩的好你叫法王，我辅助玩的好你能叫我一声父王吗？', NULL, 0, '2023-08-09 15:09:40', '2023-08-09 15:09:40');
INSERT INTO `sentence` VALUES (12187724379325538, '什么是成功？马云都没有我微信，你有!', NULL, 0, '2023-08-09 15:09:40', '2023-08-09 15:09:40');
INSERT INTO `sentence` VALUES (12187724484183383, '总之岁月漫长，然而值得等待。', NULL, 0, '2023-08-09 15:09:40', '2023-08-09 15:09:40');
INSERT INTO `sentence` VALUES (12187724488377649, '你说孤独，就像很久以前，长星照耀十三个州府。', NULL, 0, '2023-08-09 15:09:40', '2023-08-09 15:09:40');
INSERT INTO `sentence` VALUES (12187724492571478, '用大把的时间迷茫，在几个瞬间成长。', NULL, 0, '2023-08-09 15:09:40', '2023-08-09 15:09:40');
INSERT INTO `sentence` VALUES (12187724492571479, '月光还是少年的月光，九州一色还是李白的霜。', NULL, 0, '2023-08-09 15:09:40', '2023-08-09 15:09:40');
INSERT INTO `sentence` VALUES (12187724496768260, '我喜欢不热闹的热闹。', NULL, 0, '2023-08-09 15:09:40', '2023-08-09 15:09:40');
INSERT INTO `sentence` VALUES (12187724500961421, '廊柱分立才能撑起庙宇', NULL, 0, '2023-08-09 15:09:40', '2023-08-09 15:09:40');
INSERT INTO `sentence` VALUES (12187724505156558, '也许，机会只是留给有机会的人，气运，也只专属于有气运之人，事已至此，好好吃饭吧', NULL, 0, '2023-08-09 15:09:40', '2023-08-09 15:09:40');
INSERT INTO `sentence` VALUES (12187724505156559, '我行四方，以日以年', NULL, 0, '2023-08-09 15:09:40', '2023-08-09 15:09:40');
INSERT INTO `sentence` VALUES (12303148559368376, '不妨说，说谎与沉默是现代人类社会的两大罪过，而我们经常说谎往往沉默。', NULL, 0, '2023-08-09 22:48:19', '2023-08-09 22:48:19');
INSERT INTO `sentence` VALUES (12304767783013480, '我们听过无数的道理，却仍旧过不好这一生。', NULL, 0, '2023-08-09 22:54:45', '2023-08-09 22:54:45');
INSERT INTO `sentence` VALUES (12306313635693869, '淡淡的来，静静的去，心若无一物，何处惹尘埃。', NULL, 0, '2023-08-09 23:00:54', '2023-08-09 23:00:54');
INSERT INTO `sentence` VALUES (15409384809039517, '好的职业就像艾滋病一样，只在母婴、血液、性之间传播。', NULL, 0, '2023-08-18 12:31:24', '2023-08-18 12:31:24');
INSERT INTO `sentence` VALUES (18040912588308925, '有人，生在罗马；有人，生来就是骡马。', NULL, 0, '2023-08-25 18:48:09', '2023-08-25 18:48:09');
INSERT INTO `sentence` VALUES (18042762163127475, '一直觉得，答题卡这个名字不太吉利，建议改为答题顺。', NULL, 0, '2023-08-25 18:55:30', '2023-08-25 18:55:30');
INSERT INTO `sentence` VALUES (18112668116191336, '生鱼片是死鱼片，等红灯是等绿灯，咖啡因来自咖啡果。', NULL, 0, '2023-08-25 23:33:17', '2023-08-25 23:33:17');
INSERT INTO `sentence` VALUES (18115606666544717, '和我做朋友很简单，你真心把我当朋友，那我们就是朋友了。', NULL, 0, '2023-08-25 23:44:57', '2023-08-25 23:44:57');
INSERT INTO `sentence` VALUES (18115608075829347, '如果你是负二，我是负五，那我们在一起就是夫妻吖', NULL, 0, '2023-08-25 23:44:58', '2023-08-25 23:44:58');

-- ----------------------------
-- Table structure for social
-- ----------------------------
DROP TABLE IF EXISTS `social`;
CREATE TABLE `social`  (
  `id` bigint NOT NULL COMMENT 'ID',
  `user_id` bigint NOT NULL COMMENT '用户ID',
  `icon` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '图标',
  `label` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '标签',
  `link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NULL DEFAULT NULL COMMENT '链接',
  `disabled` int NULL DEFAULT 0 COMMENT '是否可用，0-可用；1-禁用',
  `sort` int NULL DEFAULT 0 COMMENT '排序依据',
  `create_time` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of social
-- ----------------------------
INSERT INTO `social` VALUES (11802776434640331, 11506737211245885, 'github', 'github', 'https://github.com/skmcj/release', 0, 5, '2023-08-08 13:40:01', '2023-08-26 23:11:29');

-- ----------------------------
-- Table structure for user
-- ----------------------------
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user`  (
  `id` bigint NOT NULL COMMENT 'id',
  `nickname` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '昵称',
  `address` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '所在地',
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '头像',
  `sex` int NULL DEFAULT 0 COMMENT '性别',
  `level` int NULL DEFAULT NULL COMMENT '境界分数；0-100',
  `year` int NOT NULL COMMENT '年份',
  `author` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL COMMENT '作者',
  `start_time` datetime NOT NULL COMMENT '开始时间',
  `create_time` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime NULL DEFAULT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_general_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of user
-- ----------------------------
INSERT INTO `user` VALUES (11506737211245885, 'SKMCJ', 'Make In China', 'image/134b296/500185af02bceff5de648708f89b6e5a.jpg', 1, 21, 2023, 'SKMCJ', '2023-08-07 00:00:00', '2023-08-07 18:03:40', '2023-08-08 12:52:51');

SET FOREIGN_KEY_CHECKS = 1;

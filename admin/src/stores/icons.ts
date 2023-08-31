const socialIcons = [
  { label: 'Logo', value: 'logo' },
  { label: 'github', value: 'github-fill' },
  { label: 'github', value: 'github' },
  { label: 'github', value: 'github-line' },
  { label: 'github', value: 'github-alt' },
  { label: 'git', value: 'git' },
  { label: 'bilibili', value: 'bilibili' },
  { label: '哔哩哔哩', value: 'bilibili-b' },
  { label: '哔哩哔哩', value: 'bilibili-tv' },
  { label: '微信', value: 'wechat' },
  { label: '微信', value: 'wechat-line' },
  { label: 'QQ', value: 'qq' },
  { label: 'vue', value: 'vue' },
  { label: 'CSS3', value: 'css3' },
  { label: 'JS', value: 'js' },
  { label: '爱发电', value: 'aifadian-line' },
  { label: 'X', value: 'x' },
  { label: '力扣', value: 'leetcode' },
  { label: '爱发电', value: 'aifadian' },
  { label: 'Soul', value: 'soul' },
  { label: '博客园', value: 'bokey' },
  { label: 'gitlab', value: 'gitlab' },
  { label: 'coding', value: 'coding' },
  { label: 'Steam', value: 'steam' },
  { label: 'Epic', value: 'epic' },
  { label: '谷歌邮箱', value: 'gmail' },
  { label: 'YouTube', value: 'youtube' },
  { label: '脸书', value: 'facebook' },
  { label: '豆瓣', value: 'douban' },
  { label: '知乎', value: 'zhihu' },
  { label: '酷狗', value: 'kugou' },
  { label: '微博', value: 'weibo' },
  { label: '网易云', value: 'wangyiyun' },
  { label: 'QQ音乐', value: 'qq-music' },
  { label: 'CSDN', value: 'csdn' },
  { label: '贴吧', value: 'tieba' },
  { label: 'Telegram', value: 'telegram' },
  { label: '知识星球', value: 'zhishixingqiu' },
  { label: 'Minecraft', value: 'minecraft' },
  { label: '抖音', value: 'douyin' },
  { label: '推特', value: 'twitter' },
  { label: '牛客', value: 'niuke' },
  { label: '闲鱼', value: 'xianyu' },
  { label: '小红书', value: 'xiaohongshu' },
  { label: '码云', value: 'gitee' },
  { label: '陌陌', value: 'momo' }
];

// 星座图标
const cStarIcons = [
  { label: '白羊座', value: 'aries' },
  { label: '金牛座', value: 'taurus' },
  { label: '双子座', value: 'gemini' },
  { label: '巨蟹座', value: 'cacer' },
  { label: '狮子座', value: 'leo' },
  { label: '处女座', value: 'virgo' },
  { label: '天秤座', value: 'libra' },
  { label: '天蝎座', value: 'scorpio' },
  { label: '射手座', value: 'sagittarius' },
  { label: '摩羯座', value: 'capricom' },
  { label: '水瓶座', value: 'aquarius' },
  { label: '双鱼座', value: 'pisces' },
  { label: '白羊座', value: 'aries-line' },
  { label: '金牛座', value: 'taurus-line' },
  { label: '双子座', value: 'gemini-line' },
  { label: '巨蟹座', value: 'cacer-line' },
  { label: '狮子座', value: 'leo-line' },
  { label: '处女座', value: 'virgo-line' },
  { label: '天秤座', value: 'libra-line' },
  { label: '天蝎座', value: 'scorpio-line' },
  { label: '射手座', value: 'sagittarius-line' },
  { label: '摩羯座', value: 'capricom-line' },
  { label: '水瓶座', value: 'aquarius-line' },
  { label: '双鱼座', value: 'pisces-line' }
];

// 其它
const otherIcons = [
  { label: '重置', value: 'refresh' },
  { label: '性别', value: 'sex' },
  { label: '男孩', value: 'boy' },
  { label: '女孩', value: 'girl' },
  { label: 'IP', value: 'ip' },
  { label: '搜索', value: 'search' },
  { label: '地址', value: 'address' },
  { label: '加', value: 'add' },
  { label: '叉', value: 'close' },
  { label: '勾', value: 'checked' },
  { label: '下载', value: 'download' },
  { label: '图片', value: 'image' },
  { label: '作品', value: 'product' },
  { label: '角色', value: 'role' },
  { label: '编辑', value: 'sentence' },
  { label: '用户', value: 'user' },
  { label: '用户', value: 'nickname' },
  { label: '留言', value: 'comment' },
  { label: '打坐', value: 'level' },
  { label: '太极', value: 'taiji' },
  { label: '抽签', value: 'lots' },
  { label: '代码', value: 'code' },
  { label: '方向', value: 'dir' },
  { label: '提示', value: 'info' },
  { label: '提示', value: 'info-line' },
  { label: '问号', value: 'q-fill' },
  { label: '问号', value: 'q-circle' },
  { label: '问号', value: 'q-line' },
  { label: '井号', value: 'shape' },
  { label: '暗', value: 'dark' },
  { label: '光', value: 'sun' },
  { label: '介绍', value: 'detail-line' },
  { label: '邮箱', value: 'email' },
  { label: '链接', value: 'link' },
  { label: '版权', value: 'copyright-line' },
  { label: '空气', value: 'air' },
  { label: '空气', value: 'air-line' },
  { label: '摄氏度', value: 'cg' }
];

/**
 * 获取社交图标列表
 */
export const getSocialIcons = function () {
  return socialIcons;
};

/**
 * 获取所有图标
 */
export const getAllIcons = function () {
  return [...otherIcons, ...cStarIcons, ...socialIcons];
};

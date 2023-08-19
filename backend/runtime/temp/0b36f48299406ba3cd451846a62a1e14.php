<?php /*a:1:{s:73:"G:\Code\project\release\release\backend\app\view\article\get_profile.html";i:1692262238;}*/ ?>
<!DOCTYPE html>
<html lang="zh-CN">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlentities($title); ?></title>
    <link rel="shortcut icon" href="http://localhost:8080/oss/image/134b296/favicon.ico">
    <link rel="stylesheet" href="/static/css/base.css" />
    <link rel="stylesheet" href="/static/css/editormd.min.css" />
    <link rel="stylesheet" href="/static/css/article.css" />
    <link rel="stylesheet" href="/static/css/ntheme.css" />
    <!-- 引入相关javascript -->
    <script src="/static/lib/jquery-3.7.0.min.js"></script>
    <script src="/static/lib/underscore.min.js"></script>
    <script src="/static/lib/raphael.min.js"></script>
    <script src="/static/lib/prettify.min.js"></script>
    <script src="/static/lib/sequence-diagram.min.js"></script>
    <script src="/static/lib/marked.min.js"></script>
    <script src="/static/js/editormd.min.js"></script>

</head>

<body class="<?php echo htmlentities($theme); ?>">
    <header>
        <img src="<?php echo htmlentities($cover); ?>" alt="header-bg" />
        <div class="title-box">
            <span class="title"><?php echo htmlentities($title); ?></span>
            <span class="tip"><?php echo htmlentities($tip); ?></span>
        </div>
        <div id="waves">
            <svg class="waves" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                viewBox="0 24 150 28" preserveAspectRatio="none" shape-rendering="auto">
                <defs>
                    <path id="gentle-wave" d="M-160 44c30 0 58-18 88-18s 58 18 88 18 58-18 88-18 58 18 88 18 v44h-352z">
                    </path>
                </defs>
                <g class="parallax">
                    <use xlink:href="#gentle-wave" x="48" y="0"></use>
                    <use xlink:href="#gentle-wave" x="48" y="3"></use>
                    <use xlink:href="#gentle-wave" x="48" y="5"></use>
                    <use xlink:href="#gentle-wave" x="48" y="7"></use>
                </g>
            </svg>
        </div>
    </header>
    <main>
        <div class="inner <?php echo htmlentities($themeClass); ?>">
            <div id="sidebar" class="affix"></div>
            <div id="markdown-view" class="article-main">
                <textarea style="display:none;" id="markdown-doc"><?php echo htmlentities($main); ?></textarea>
            </div>
        </div>

    </main>

</body>
<script>
    $(function() {
        editormd.markdownToHTML("markdown-view",{
            htmlDecode: "style,script,iframe", // filter tags decode
            emoji: true,
            taskList: true,
            previewTheme: "github",
            tex: true, // 默认不解析
            flowChart: false, // 默认不解析
            sequenceDiagram: true, // 默认不解析
            path: "/static/lib/",
            tocm: true,    // Using [TOCM]
            tocContainer: "#sidebar", // 自定义 ToC 容器层
        });
    });
</script>

</html>
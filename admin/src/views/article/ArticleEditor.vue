<template>
  <div id="article-editor">
    <textarea style="display: none"></textarea>
  </div>
</template>

<script setup lang="ts">
import { nextTick, onBeforeMount, onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';
import { useScript } from '@/hooks/useScript';
import { useLink } from '@/hooks/useLink';
import { useViewParamsStore } from '@/stores/viewparams';
import { getArticleContentApi, editArticleContentApi } from '@/api/articleApi';
import { showMessage } from '@/utils/commonUtil';

const router = useRouter();
const back = () => {
  router.back();
};

const isLoading = ref(true);

const content = ref('');
const { articleId } = useViewParamsStore();
const getArticleContent = (id: string) => {
  getArticleContentApi(id)
    .then(res => {
      if (res.code === 214) {
        content.value = res.data;
      } else {
        showMessage(res.msg, 'warning');
      }
    })
    .finally(() => {
      nextTick(() => {
        if (editor) editor.setMarkdown(content.value);
        else initEditor();
      }).catch(err => {
        initEditor();
      });
    });
};
onBeforeMount(() => {
  initEditor();
  if (articleId) getArticleContent(articleId);
});

const saveMd = () => {
  const saveContent = editor.getMarkdown();
  editArticleContentApi({
    id: articleId,
    content: saveContent
  }).then(res => {
    if (res.code === 213) {
      showMessage('文章内容保存成功', 'success');
    } else {
      showMessage(res.msg, 'warning');
    }
  });
};

let editor: EditorMd;
const theme = ref('default');
const themeList: any = { default: 'default', dark: 'twilight' };
const changeTheme = (cm: any, icon: any) => {
  theme.value = theme.value === 'default' ? 'dark' : 'default';
  icon[0].className = theme.value === 'default' ? 'fa fa-toggle-off' : 'fa fa-toggle-on';
  editor.setTheme(theme.value);
  editor.setEditorTheme(themeList[theme.value]);
  editor.setPreviewTheme(theme.value);
};

useLink({ href: '/static/css/editormd.min.css' });
useLink({ href: '/static/css/ntheme.css' });
useScript({ src: '/static/lib/jquery-3.7.0.min.js' });
useScript({ src: '/static/lib/prettify.min.js' });
useScript({ src: '/static/lib/marked.min.js' });
useScript({ src: '/static/lib/raphael.min.js' });
useScript({ src: '/static/lib/underscore.min.js' });
useScript({ src: '/static/lib/sequence-diagram.min.js' });
const { toPromise } = useScript({ src: '/static/lib/editormd.min.js' });
const initEditor = () => {
  toPromise().then(res => {
    editor = editormd('article-editor', {
      // width: '100%',
      height: '100%',
      markdown: content.value,
      syncScrolling: 'single',
      emoji: true,
      htmlDecode: true,
      // theme: 'string',
      // editorTheme: 'string',
      // previewTheme: theme.value,
      placeholder: '在这里输入您的内容',
      toolbarIcons: function () {
        // Or return editormd.toolbarModes[name]; // full, simple, mini
        // Using "||" set icons align right.
        return [
          'undo',
          'redo',
          '|',
          'bold',
          'italic',
          'del',
          'quote',
          'code',
          'ucwords',
          'uppercase',
          'lowercase',
          '|',
          'h1',
          'h2',
          'h3',
          'h4',
          'h5',
          'h6',
          '|',
          'list-ul',
          'list-ol',
          'table',
          '|',
          'emoji',
          'html-entities',
          'pagebreak',
          'hr',
          '|',
          'preview',
          'watch',
          'fullscreen',
          'cmode',
          'help',
          '|',
          'save-md',
          'back'
        ];
      },
      toolbarIconsClass: {
        cmode: 'fa-toggle-off',
        'save-md': 'fa-floppy-o', // 指定一个FontAawsome的图标类
        back: 'fa-sign-out'
      },
      toolbarIconTexts: {
        cmode: '主题',
        'save-md': '保存', // 如果没有图标，则可以这样直接插入内容，可以是字符串或HTML标签
        back: '退出'
      },
      lang: {
        toolbar: {
          cmode: '更改主题',
          'save-md': '保存',
          back: '退出编辑'
        }
      },
      // // 自定义工具栏按钮的事件处理
      toolbarHandlers: {
        cmode: changeTheme,
        'save-md': saveMd,
        back: back
      },
      path: '/static/lib/'
    });
  });
};
</script>

<style lang="scss" scoped>
#article-editor {
  width: 100%;
  height: 100%;
  z-index: 8;
}
</style>

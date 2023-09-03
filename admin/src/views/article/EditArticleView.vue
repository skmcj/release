<template>
  <div class="edit-article-view page-view">
    <div class="page-inner">
      <el-form :model="form" :rules="rules" label-width="120" ref="formDom">
        <el-form-item label="标题：" prop="title">
          <el-input v-model="form.title" style="width: 240px" />
        </el-form-item>
        <el-form-item label="类别：" prop="cate">
          <el-input v-model="form.cate" style="width: 240px" />
        </el-form-item>
        <el-form-item label="描述：" prop="description">
          <el-input v-model="form.description" />
        </el-form-item>
        <el-form-item label="封面：" prop="cover">
          <template #label>
            <el-tooltip effect="dark" content="是否网络图片" placement="top">
              <el-checkbox v-model="coverMode" style="margin-right: 6px" />
            </el-tooltip>
            <span>封面：</span>
          </template>
          <div class="cover-input" v-if="!coverMode">
            <input
              ref="coverInput"
              type="file"
              hidden
              @change="selectFile"
              accept="image/gif,image/jpeg,image/jpg,image/png" />
            <img class="cover-pv" v-show="form.cover && coverUrl" :src="coverUrl" alt="cover" />
            <div class="cover-tool" @click="openImage">
              <i>{{ form.cover ? '编辑' : '选择上传' }}</i>
            </div>
          </div>
          <div class="web-cover-input" v-else>
            <el-input v-if="!webCoverVisible" v-model="form.cover" style="width: 360px" />
            <el-image
              v-else
              style="width: 12rem; height: 8rem; border-radius: 8px"
              :src="calcWallhavenSmallImg(form.cover ?? '')"
              hide-on-click-modal
              preview-teleported
              :preview-src-list="[form.cover ?? '']"
              fit="cover" />
            <el-button type="primary" link @click="webCoverVisible = !webCoverVisible">{{
              webCoverVisible ? '取消预览' : '预览'
            }}</el-button>
          </div>
        </el-form-item>
        <el-form-item label="标签" prop="tags" class="tag-box">
          <!-- 标签列表 -->
          <el-tag
            v-for="tag in form.tags"
            :key="tag"
            closable
            :disable-transitions="false"
            @close="handleTagClose(tag)"
            >{{ tag }}</el-tag
          >
          <!-- 标签输入框 -->
          <el-input
            v-if="tagInputVisible"
            ref="tagInputDom"
            v-model="inputValue"
            size="small"
            style="width: 72px"
            @keyup.enter="handleTagInput" />
          <!-- 标签添加按钮 -->
          <el-button v-else style="width: 72px" size="small" @click="showTagInput"> + 添加 </el-button>
        </el-form-item>
        <el-form-item label="字数" prop="count">
          <el-input-number v-model="form.count" :min="0" controls-position="right" />
        </el-form-item>
        <el-form-item label="阅读量" prop="eyes">
          <el-input-number v-model="form.eyes" :min="0" controls-position="right" />
        </el-form-item>
        <el-form-item label="可用性：" prop="disabled">
          <el-switch v-model="form.disabled" style="--el-switch-on-color: #e83929"></el-switch>
        </el-form-item>
      </el-form>
      <div class="page-form-bt">
        <el-button type="primary" @click="confirm(formDom)">{{ confirmText }}</el-button>
        <el-button @click="back">取消</el-button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { addArticleApi, editArticleApi, getArticleByIdApi } from '@/api/articleApi';
import { uploadImageApi } from '@/api/imageApi';
import { useViewParamsRef } from '@/stores/viewparams';
import type { ArticleInfo } from '@/types';
import { showMessage, calcWallhavenSmallImg } from '@/utils/commonUtil';
import type { FormInstance, FormRules } from 'element-plus';
import { nextTick, onBeforeMount, reactive, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import {
  ElForm,
  ElFormItem,
  ElInput,
  ElSwitch,
  ElTag,
  ElButton,
  ElInputNumber,
  ElCheckbox,
  ElTooltip,
  ElImage
} from 'element-plus';

// 标签输入
const inputValue = ref('');
const tagInputVisible = ref(false);
const tagInputDom = ref<InstanceType<typeof ElInput>>();

const handleTagClose = (tag: string) => {
  form.tags!.splice(form.tags!.indexOf(tag), 1);
};

const showTagInput = () => {
  tagInputVisible.value = true;
  nextTick(() => {
    tagInputDom.value!.input!.focus();
  });
};

const handleTagInput = () => {
  if (form.tags?.length === 16) {
    showMessage('标签不能超过16个', 'warning');
  } else if (inputValue.value) {
    if (form.tags?.length === 0 || form.tags!.indexOf(inputValue.value) === -1) {
      form.tags!.push(inputValue.value);
    } else {
      showMessage('标签已存在');
    }
  }
  tagInputVisible.value = false;
  inputValue.value = '';
};

// 文件上传
const coverMode = ref(false);
const webCoverVisible = ref(false);
const coverInput = ref();
const coverUrl = ref('');
const openImage = () => {
  coverInput.value.click();
};
const selectFile = (e: any) => {
  let file = e.target.files[0];
  let filename = file.name;
  uploadImage(file, filename);
};
const uploadImage = (file: File, filename: string) => {
  uploadImageApi(file, filename).then(res => {
    if (res.code === 200) {
      // 上传成功
      form.cover = res.data.name;
      coverUrl.value = res.data.url;
    } else {
      showMessage(res.msg, 'warning');
    }
  });
};

const nowDay = new Date(new Date().toLocaleDateString()).getTime();

const form = reactive<ArticleInfo>({
  title: '',
  description: '',
  cover: '',
  tags: [],
  cate: ''
});
const rules = reactive<FormRules<ArticleInfo>>({
  title: [
    { required: true, message: '文章标题不能为空', trigger: 'blur' },
    { max: 64, message: '标题不能超过64个字符', trigger: 'blur' }
  ],
  tags: [{ type: 'array', max: 16, message: '标签不能超过16个', trigger: 'blur' }],
  description: [{ max: 255, message: '描述不能超过255个字符', trigger: 'blur' }]
});
const formDom = ref<FormInstance>();

const route = useRoute();
const router = useRouter();
const back = () => {
  router.back();
};

// 确认按钮文字
const confirmText = ref('确认添加');

// 页面传参
const { articleId } = useViewParamsRef();
onBeforeMount(() => {
  const name = route.name;
  if (articleId.value && name === 'editArticle') {
    getArticleById(articleId.value);
    confirmText.value = '确认编辑';
  }
  if (name === 'addArticle') {
    confirmText.value = '确认添加';
  }
});

let editForm: any = {};
const getArticleById = (id: string) => {
  getArticleByIdApi(id).then(res => {
    console.log(res);
    if (res.data) {
      if (!res.data.tags) res.data.tags = [];
      editForm = res.data;
      coverUrl.value = res.data.cover;
      Object.assign(form, res.data);
    }
  });
};

// 添加用户
const confirm = (frm: FormInstance | undefined) => {
  if (!frm) return;
  // avatarVisible.value = true;
  frm.validate((valid, fields) => {
    if (valid) {
      if (route.name === 'addArticle') {
        // 新增
        save();
      } else {
        // 编辑
        edit();
      }
    }
  });
};

// 新增
const save = () => {
  addArticleApi(form).then(res => {
    if (res.code === 211) {
      showMessage('文章添加成功', 'success');
      back();
    } else {
      showMessage(res.msg, 'warning');
    }
  });
};

// 编辑
const edit = () => {
  // 整理待修改表单
  const data: any = {};
  let key: keyof ArticleInfo;
  for (key in form) {
    if (form[key] !== editForm[key]) {
      data[key] = form[key];
    }
  }
  data['id'] = editForm['id'];
  editArticleApi(data).then(res => {
    if (res.code === 213) {
      showMessage('文章修改成功', 'success');
      back();
    } else {
      showMessage(res.msg, 'warning');
    }
  });
};
</script>

<style lang="scss" scoped>
.cover-input {
  position: relative;
  width: 12rem;
  height: 8rem;
  border-radius: 8px;
  border: 1px dashed $rlbd-default;
  overflow: hidden;
  display: flex;
  align-items: center;
  justify-content: center;
  .cover-pv {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  .cover-tool {
    user-select: none;
    cursor: pointer;
    width: 100%;
    height: 1.5rem;
    position: absolute;
    bottom: 0;
    line-height: 1.5rem;
    font-size: 0.8rem;
    color: $rlt-primary;
    text-align: center;
    background-color: rgba(0, 0, 0, 0.3);
    transition: transform 0.3s ease-in-out;
    transform: translateY(100%);
  }
  &:hover {
    .cover-tool {
      transform: translateY(0);
    }
  }
}
.web-cover-input {
  display: flex;
  align-items: center;
  column-gap: 8px;
}
</style>

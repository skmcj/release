<template>
  <div class="edit-image-view page-view">
    <div class="page-inner">
      <div class="page-lrbox">
        <el-form :model="form" :rules="rules" label-width="120" ref="formDom" style="width: 420px; flex-shrink: 0">
          <el-form-item label="图片名称：" prop="name">
            <el-input v-model="form.name" style="width: 240px" />
          </el-form-item>
          <el-form-item label="图片地址：" prop="url">
            <el-input v-model="form.url" style="width: 240px" @blur="urlInputBlur" />
            <el-checkbox v-model="isPreview" label="预览" style="margin-left: 8px" />
          </el-form-item>
          <el-form-item label="显示类型：" prop="type">
            <el-radio-group v-model="form.type">
              <el-radio label="pc"><span>🖥️</span>桌面</el-radio>
              <el-radio label="phone"><span>📱</span>手机</el-radio>
              <el-radio label="other"><span>🔗</span>其它</el-radio>
            </el-radio-group>
          </el-form-item>
          <el-form-item label="图片类型：" prop="imageType">
            <el-select v-model="form.imageType" placeholder="选择关键词" clearable style="width: 210px">
              <el-option v-for="item in imgTypeList" :key="item.id" :label="item.label" :value="item.value" />
            </el-select>
          </el-form-item>
          <el-form-item label="宽：" prop="w">
            <el-input type="number" v-model.number="form.w" :min="0" style="width: 240px">
              <template #suffix>
                <span>px</span>
              </template>
            </el-input>
          </el-form-item>
          <el-form-item label="高：" prop="h">
            <el-input type="number" v-model.number="form.h" :min="0" style="width: 240px">
              <template #suffix>
                <span>px</span>
              </template>
            </el-input>
          </el-form-item>
          <el-form-item label="大小：" prop="size">
            <el-input type="number" v-model.number="form.size" :min="0" style="width: 240px">
              <template #suffix>
                <span>字节</span>
              </template>
            </el-input>
          </el-form-item>
          <el-form-item prop="labels" class="tag-box">
            <!-- 标签内容 -->
            <template #label>
              <el-tooltip effect="dark" content="点击添加按钮，输入标签内容，回车确认添加" placement="top">
                <i class="ir-q-circle tip-icon"></i>
              </el-tooltip>
              <span>标签：</span>
            </template>
            <!-- 标签列表 -->
            <el-tag
              v-for="tag in form.labels"
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
          <el-form-item label="可用性：" prop="disabled">
            <el-switch v-model="form.disabled" style="--el-switch-on-color: #e83929"></el-switch>
          </el-form-item>
          <el-form-item label="显示日期" prop="showDate">
            <el-date-picker
              v-model="(form.showDate as string)"
              type="date"
              placeholder="选择特定展示日期"
              value-format="YYYY-MM-DD"
              :disabled-date="(date: Date) => date.getTime() < nowDay.getTime()" />
          </el-form-item>
        </el-form>
        <div class="preview-image-box">
          <el-image
            v-if="isPreview"
            style="border-radius: 8px; max-width: 81%; max-height: 81%"
            :src="calcWallhavenSmallImg(form.url ?? '')"
            hide-on-click-modal
            preview-teleported
            :preview-src-list="[form.url ?? '']"
            fit="cover" />
        </div>
      </div>

      <div class="page-form-bt">
        <el-button type="primary" @click="confirm(formDom)">{{ confirmText }}</el-button>
        <el-button @click="back">取消</el-button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { nextTick, onBeforeMount, reactive, ref } from 'vue';
import {
  ElForm,
  ElFormItem,
  ElInput,
  ElRadioGroup,
  ElRadio,
  ElSelect,
  ElOption,
  ElDatePicker,
  ElButton,
  ElSwitch,
  ElImage,
  ElCheckbox,
  ElTag,
  ElTooltip,
  type FormInstance,
  type FormRules
} from 'element-plus';
import type { ImageInfo } from '@/types';
import { addImageApi, editImageApi, getImageByIdApi, getImageSizeApi } from '@/api/imageApi';
import { showMessage, calcWallhavenSmallImg, validateImgUrl, getImageType, getWebImgWH } from '@/utils/commonUtil';
import { useViewParamsRef } from '@/stores/viewparams';
import { useRouter, useRoute } from 'vue-router';

const imgTypeList = [
  { id: '8ee11d2c950308c8aecdf7e99a0beea2', label: 'jpg', value: 'jpg' },
  { id: 'e0132b742ad6c46037757ff498603000', label: 'png', value: 'png' },
  { id: '4fd3be8a9897270f91f31c0bced53dfe', label: 'gif', value: 'gif' },
  { id: '2263f6093e628829955e7f6c85b8bb53', label: 'jpeg', value: 'jpeg' },
  { id: 'ba6562ad735628aa48abe25ec2010691', label: 'apng', value: 'apng' },
  { id: 'ed25a873bd3659c32207f508898e62ae', label: 'avif', value: 'avif' },
  { id: '1bf1d1d979fb0b4ee4060eb2438c3024', label: 'svg', value: 'svg' },
  { id: '4615f4bdc587980dc8736387e0c34a22', label: 'webp', value: 'webp' },
  { id: '4fd356fde197e08180cba5bf643f3ca0', label: 'jif', value: 'jif' },
  { id: 'd636300aea4e76e9b3fd0da77cc592a3', label: 'ico', value: 'ico' },
  { id: 'f55ecad2197656183e9f4c25a440635b', label: 'tiff', value: 'tiff' }
];

// 是否预览图片
const isPreview = ref(false);

// 标签输入
const inputValue = ref('');
const tagInputVisible = ref(false);
const tagInputDom = ref<InstanceType<typeof ElInput>>();

const handleTagClose = (tag: string) => {
  form.labels!.splice(form.labels!.indexOf(tag), 1);
};

const showTagInput = () => {
  tagInputVisible.value = true;
  nextTick(() => {
    tagInputDom.value!.input!.focus();
  });
};

const handleTagInput = () => {
  if (form.labels?.length === 12) {
    showMessage('标签不能超过12个', 'warning');
  } else if (inputValue.value) {
    if (form.labels?.length === 0 || form.labels!.indexOf(inputValue.value) === -1) {
      form.labels!.push(inputValue.value);
    } else {
      showMessage('标签已存在');
    }
  }
  tagInputVisible.value = false;
  inputValue.value = '';
};

// 表单
const nowDay = new Date();

const form = reactive<ImageInfo>({
  name: '',
  url: '',
  type: 'pc',
  labels: [],
  w: 0,
  h: 0,
  size: 0,
  disabled: false,
  imageType: 'jpg',
  showDate: ''
});
const rules = reactive<FormRules<ImageInfo>>({
  url: [{ required: true, message: '图片地址不能为空', trigger: 'blur' }],
  type: [{ required: true, message: '显示类型不能为空', trigger: 'blur' }],
  labels: [{ type: 'array', max: 12, message: '标签不能超过12个', trigger: 'blur' }],
  w: [{ type: 'number', message: '图片宽度需为有效数字', trigger: 'blur' }],
  h: [{ type: 'number', message: '图片高度需为有效数字', trigger: 'blur' }],
  size: [{ type: 'number', message: '图片大小需为有效数字', trigger: 'blur' }]
});
const formDom = ref<FormInstance>();

const route = useRoute();
const router = useRouter();
const back = () => {
  router.back();
};

// url输入框失焦
const urlInputBlur = () => {
  calcImageInfo(form.url ?? '');
};

// 计算图片宽高大小
const calcImageInfo = (url: string) => {
  if (!url) return;
  if (validateImgUrl(url)) {
    getWebImgWH(url).then((res: any) => {
      if (res) {
        form.w = res.w;
        form.h = res.h;
      }
    });
    getImageSizeApi(url).then(res => {
      if (res.data) {
        form.size = res.data;
      }
    });
    const t = getImageType(url);
    if (t) form.imageType = t;
  }
};

// 确认按钮文字
const confirmText = ref('确认添加');

// 页面传参
const { imageId } = useViewParamsRef();
onBeforeMount(() => {
  const name = route.name;
  if (imageId.value && name === 'editImage') {
    getImageById(imageId.value);
    confirmText.value = '确认编辑';
  }
  if (name === 'addImage') {
    confirmText.value = '确认添加';
  }
});

let editForm: any = {};
const getImageById = (id: string) => {
  getImageByIdApi(id).then(res => {
    if (res.data) {
      if (res.data.labels === null) res.data.labels = [];
      editForm = res.data;
      Object.assign(form, res.data);
    }
  });
};

// 添加用户
const confirm = (frm: FormInstance | undefined) => {
  if (!frm) return;
  frm.validate((valid, fields) => {
    if (valid) {
      if (route.name === 'addImage') {
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
  const data: any = {};
  let key: keyof ImageInfo;
  for (key in form) {
    if (form[key] !== '') {
      data[key] = form[key];
    }
  }
  addImageApi(data).then(res => {
    if (res.code === 211) {
      showMessage('图片添加成功', 'success');
      back();
    } else {
      console.log('img', res);
      showMessage(res.msg, 'warning');
    }
  });
};

// 编辑
const edit = () => {
  // 整理待修改表单
  const data: any = {};
  let key: keyof ImageInfo;
  for (key in form) {
    if (form[key] !== editForm[key]) {
      data[key] = form[key];
    }
  }
  data['id'] = editForm['id'];
  editImageApi(data).then(res => {
    if (res.code === 213) {
      showMessage('图片修改成功', 'success');
      back();
    } else {
      showMessage(res.msg, 'warning');
    }
  });
};
</script>

<style lang="scss" scoped>
.preview-image-box {
  flex-grow: 1;
  margin-top: 64px;
  display: flex;
  align-items: center;
  justify-content: center;
}
</style>

<template>
  <div class="edit-product-view page-view">
    <div class="page-inner">
      <el-form :model="form" :rules="rules" label-width="120" ref="formDom">
        <el-form-item label="名称：" prop="name">
          <el-input v-model="form.name" style="width: 240px" />
        </el-form-item>
        <el-form-item label="简介：" prop="tip">
          <el-input v-model="form.tip" />
        </el-form-item>
        <el-form-item label="Logo：" prop="logo">
          <div class="logo-input">
            <input
              ref="logoInput"
              type="file"
              hidden
              @change="selectFile"
              accept="image/gif,image/jpeg,image/jpg,image/png" />
            <img class="logo-pv" v-show="form.logo" :src="form.logoUrl" alt="logo" />
            <div class="logo-tool" @click="openImage">
              <i>{{ form.logo ? '编辑' : '选择上传' }}</i>
            </div>
          </div>
        </el-form-item>
        <el-form-item label="github Stars：" prop="stars">
          <el-input v-model="form.stars" style="width: 360px" />
        </el-form-item>
        <el-form-item label="可用性：" prop="disabled">
          <el-switch v-model="form.disabled" style="--el-switch-on-color: #e83929"></el-switch>
        </el-form-item>
        <el-form-item label="介绍文档ID：" prop="articleId">
          <el-select v-model="form.articleId" placeholder="选择已有文章">
            <el-option v-for="item in articleIDList" :key="item.id" :label="item.title" :value="item.id" />
          </el-select>
        </el-form-item>
        <el-form-item label="完成日期：" prop="compDate">
          <el-date-picker v-model="form.compDate" type="date" placeholder="选择日期" value-format="YYYY-MM-DD" />
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
import { addProductApi, editProductApi, getProductByIdApi } from '@/api/productApi';
import { useViewParamsRef } from '@/stores/viewparams';
import type { ArticleShortInfo, ProductInfo } from '@/types';
import { showMessage } from '@/utils/commonUtil';
import type { FormInstance, FormRules } from 'element-plus';
import { onBeforeMount, reactive, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { ElForm, ElFormItem, ElInput, ElSwitch, ElDatePicker, ElSelect, ElOption, ElButton } from 'element-plus';
import { uploadImageApi } from '@/api/imageApi';

// 文件上传
const logoInput = ref();
const openImage = () => {
  logoInput.value.click();
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
      form.logo = res.data.name;
      form.logoUrl = res.data.url;
    } else {
      showMessage(res.msg, 'warning');
    }
  });
};

// 文章ID列表
const articleIDList = ref<ArticleShortInfo[]>([]);

const nowDay = new Date(new Date().toLocaleDateString()).getTime();

const form = reactive<ProductInfo>({});
const rules = reactive<FormRules<ProductInfo>>({
  name: [{ required: true, message: '作品名称不能为空', trigger: 'blur' }],
  logo: [{ required: true, message: 'logo不能为空', trigger: 'blur' }],
  compDate: [{ required: true, message: '完成日期不能为空', trigger: 'blur' }]
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
const { productId } = useViewParamsRef();
onBeforeMount(() => {
  const name = route.name;
  if (productId.value && name === 'editProduct') {
    getProductById(productId.value);
    confirmText.value = '确认编辑';
  }
  if (name === 'addProduct') {
    confirmText.value = '确认添加';
  }
});

let editForm: any = {};
const getProductById = (id: string) => {
  getProductByIdApi(id).then(res => {
    if (res.data) {
      editForm = res.data;
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
      if (route.name === 'addProduct') {
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
  addProductApi(form).then(res => {
    if (res.code === 211) {
      showMessage('作品添加成功', 'success');
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
  let key: keyof ProductInfo;
  for (key in form) {
    if (form[key] !== editForm[key]) {
      data[key] = form[key];
    }
  }
  data['id'] = editForm['id'];
  editProductApi(data).then(res => {
    if (res.code === 213) {
      showMessage('作品修改成功', 'success');
      back();
    } else {
      showMessage(res.msg, 'warning');
    }
  });
};
</script>

<style lang="scss" scoped>
.logo-input {
  position: relative;
  width: 10rem;
  height: 10rem;
  border-radius: 8px;
  border: 1px dashed $rlbd-default;
  overflow: hidden;
  display: flex;
  align-items: center;
  justify-content: center;
  .logo-pv {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  .logo-tool {
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
    .logo-tool {
      transform: translateY(0);
    }
  }
}
</style>

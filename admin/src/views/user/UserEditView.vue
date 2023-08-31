<template>
  <div class="user-edit-view page-view">
    <div class="page-inner">
      <el-form :model="form" :rules="rules" label-width="120" ref="formDom">
        <el-form-item label="头像：" prop="avatar" style="align-items: center">
          <div class="avatar-input">
            <input
              ref="avatarInput"
              type="file"
              hidden
              @change="selectFile"
              accept="image/gif,image/jpeg,image/jpg,image/png" />
            <img class="avatar-pv" v-show="form.avatar" :src="form.avatar" alt="avatar" />
            <div class="avatar-tool" @click="openImage"><i>编辑</i></div>
          </div>
        </el-form-item>
        <el-form-item label="昵称：" prop="nickname">
          <el-input v-model="form.nickname" style="width: 240px" />
        </el-form-item>
        <el-form-item label="地址：" prop="address">
          <el-input v-model="form.address" style="width: 240px" />
        </el-form-item>
        <el-form-item label="作者：" prop="author">
          <el-input v-model="form.author" style="width: 240px" />
        </el-form-item>
        <el-form-item label="性别：" prop="sex">
          <el-radio-group v-model="form.sex">
            <el-radio :label="0" class="sex"><i class="ir-sex"></i>保密</el-radio>
            <el-radio :label="1" class="boy"><i class="ir-boy"></i>男</el-radio>
            <el-radio :label="2" class="girl"><i class="ir-girl"></i>女</el-radio>
          </el-radio-group>
        </el-form-item>
        <el-form-item label="境界：" prop="level">
          <el-slider v-model="form.level" style="width: 300px" />
        </el-form-item>
        <el-form-item label="展示年份：" prop="year">
          <el-date-picker
            v-model="form.year"
            type="year"
            placeholder="选择年份"
            value-format="YYYY"
            :disabled-date="(date: Date) => date.getFullYear() > (nowDay.getFullYear())" />
        </el-form-item>
        <el-form-item label="开始日期：" prop="startTime">
          <el-date-picker
            v-model="form.startTime"
            type="date"
            placeholder="选择日期"
            value-format="YYYY-MM-DD"
            :disabled-date="disabledDate" />
        </el-form-item>
      </el-form>
      <div class="page-form-bt">
        <el-button type="primary" @click="confirm(formDom)">{{ confirmText }}</el-button>
        <el-button @click="back">取消</el-button>
      </div>
    </div>
    <el-dialog v-model="avatarVisible" title="头像编辑">
      <div class="cropper-box">
        <vue-cropper
          ref="cropper"
          :canMoveBox="false"
          :img="avatarUrl"
          fixedBox
          autoCrop
          centerBox
          autoCropWidth="256"
          autoCropHeight="256"
          outputType="png"></vue-cropper>
      </div>
      <div class="cropper-options">
        <el-button @click="cancelPick">取消</el-button>
        <el-button @click="refreshPick" type="primary"><i class="ir-refresh"></i></el-button>
        <el-button @click="rotatePick" type="primary"><i class="ir-rotate2"></i></el-button>
        <el-button @click="pickAvatar" type="primary">确认</el-button>
      </div>
    </el-dialog>
  </div>
</template>

<script setup lang="ts">
import { reactive, ref, onBeforeMount } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { VueCropper } from 'vue-cropper';
import 'vue-cropper/dist/index.css';
import {
  ElForm,
  ElFormItem,
  ElInput,
  ElRadioGroup,
  ElRadio,
  ElSlider,
  ElDatePicker,
  ElButton,
  ElDialog,
  type FormInstance,
  type FormRules
} from 'element-plus';
import type { UserInfo } from '@/types';
import { useViewParamsRef } from '@/stores/viewparams';
import { showMessage } from '@/utils/commonUtil';
import { uploadImageApi } from '@/api/imageApi';
import { addUserApi, getUserByIdApi, editUserApi } from '@/api/userApi';

// 禁止选择日期
const disabledDate = (date: Date) => {
  return date.getFullYear() != form.year;
};

const nowDay = new Date();

const form = reactive<UserInfo>({
  nickname: '',
  address: '',
  avatar: '',
  sex: 0,
  level: 0,
  year: '2023',
  author: '',
  startTime: ''
});
const rules = reactive<FormRules<UserInfo>>({
  nickname: [{ required: true, message: '昵称不能为空', trigger: 'blur' }],
  address: [{ required: true, message: '地址不能为空', trigger: 'blur' }],
  avatar: [{ required: true, message: '头像不能为空', trigger: 'blur' }],
  level: [{ required: true, message: '境界不能为空', trigger: 'blur' }],
  year: [{ required: true, message: '开始年份不能为空', trigger: 'blur' }],
  author: [{ required: true, message: '作者不能为空', trigger: 'blur' }],
  startTime: [{ required: true, message: '开始时间不能为空', trigger: 'blur' }]
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
const { userId } = useViewParamsRef();
onBeforeMount(() => {
  const name = route.name;
  if (userId.value && name === 'editUser') {
    getUserById(userId.value);
    confirmText.value = '确认编辑';
  }
  if (name === 'addUser') {
    confirmText.value = '确认添加';
  }
});

let editForm: any = {};
const getUserById = (id: string) => {
  getUserByIdApi(id).then(res => {
    if (res.data) {
      editForm = res.data;
      Object.assign(form, res.data);
      form.year = String(form.year);
    }
  });
};

// 添加用户
const confirm = (frm: FormInstance | undefined) => {
  if (!frm) return;
  // avatarVisible.value = true;
  frm.validate((valid, fields) => {
    if (valid) {
      if (route.name === 'addUser') {
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
  addUserApi(form).then(res => {
    if (res.code === 200) {
      showMessage('用户添加成功', 'success');
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
  let key: keyof UserInfo;
  for (key in form) {
    if (form[key] !== editForm[key]) {
      data[key] = form[key];
    }
  }
  data['id'] = editForm['id'];
  editUserApi(data).then(res => {
    if (res.code === 213) {
      showMessage('用户修改成功', 'success');
      back();
    } else {
      showMessage(res.msg, 'warning');
    }
  });
};

// 头像
const avatarUrl = ref('');
const avatarInput = ref();
const cropper = ref();
const openImage = () => {
  avatarInput.value.click();
};

let filesize = '';
let filename = '';

const selectFile = (e: any) => {
  let file = e.target.files[0];
  filesize = file.size;
  filename = file.name;
  let reader = new FileReader();
  reader.readAsDataURL(file);
  reader.onload = e => {
    let imgcode = e.target?.result;
    avatarUrl.value = imgcode as string;
    avatarVisible.value = true;
  };
};

// 刷新裁剪视图
const refreshPick = () => {
  cropper.value?.refresh();
};

// 旋转裁剪视图
const rotatePick = () => {
  cropper.value?.rotateLeft();
};

// 取消裁剪
const cancelPick = () => {
  avatarVisible.value = false;
};

// 确认裁剪
const pickAvatar = () => {
  // 获取裁剪后的图片数据，上传
  cropper.value.getCropBlob((data: Blob) => {
    // do something
    // console.log('pick', data);
    uploadImageApi(data, filename)
      .then(res => {
        if (res.data) {
          form.avatar = res.data.url;
        }
      })
      .finally(() => {
        avatarVisible.value = false;
      });
  });
};

const avatarVisible = ref(false);
</script>

<style lang="scss" scoped>
:deep(.el-radio) {
  &.is-checked {
    &.sex {
      .el-radio__label {
        color: $rlt-sex;
      }
    }
    &.boy {
      .el-radio__label {
        color: $rlt-boy;
      }
    }
    &.girl {
      .el-radio__label {
        color: $rlt-girl;
      }
    }
  }
}
.avatar-input {
  position: relative;
  width: 4.5rem;
  height: 4.5rem;
  border-radius: 50%;
  box-sizing: border-box;
  border: 1px solid $rlbd-default;
  overflow: hidden;
  display: flex;
  align-items: center;
  justify-content: center;
  .avatar-pv {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  .avatar-tool {
    user-select: none;
    cursor: pointer;
    width: 100%;
    height: 1.2rem;
    position: absolute;
    bottom: 0;
    line-height: 1.2rem;
    font-size: 0.7rem;
    color: $rlt-primary;
    text-align: center;
    background-color: rgba(0, 0, 0, 0.3);
    transition: transform 0.3s ease-in-out;
    transform: translateY(100%);
  }
  &:hover {
    .avatar-tool {
      transform: translateY(0);
    }
  }
}
.cropper-box {
  width: 100%;
  height: 300px;
}
.cropper-options {
  margin-top: 18px;
  display: flex;
  align-items: center;
  justify-content: center;
}
</style>

<template>
  <!-- 登录视图 -->
  <div class="login-view">
    <!-- 标题 -->
    <div class="login-title">release 管理后台</div>
    <!-- form -->
    <div class="login-form">
      <el-form ref="formDom" :model="form" label-width="72px" :rules="rules">
        <el-form-item label="用户名" prop="username">
          <el-input class="ntair" v-model="form.username" placeholder="请输入用户名" />
        </el-form-item>
        <el-form-item label="密码" prop="password">
          <el-input class="ntair" v-model="form.password" type="password" placeholder="请输入密码" />
        </el-form-item>
      </el-form>
    </div>
    <!-- btn -->
    <div class="login-btns">
      <VmButton class="login-btn" @click.stop="login(formDom)">登录</VmButton>
    </div>
  </div>
</template>

<script setup lang="ts">
import { reactive, ref } from 'vue';
import { useRouter } from 'vue-router';
import { ElForm, ElFormItem, ElInput, type FormRules, type FormInstance } from 'element-plus';
import VmButton from '@/components/VmButton.vue';
import { loginApi } from '@/api/roleApi';
import { showMessage } from '@/utils/commonUtil';
import { useRoleInfoStore } from '@/stores/roleinfo';

interface LoginForm {
  username: string;
  password: string;
}

const rules = reactive<FormRules<LoginForm>>({
  username: [
    { required: true, message: '用户名不能为空', trigger: 'blur' },
    { min: 8, message: '用户名不能低于8个字符', trigger: 'blur' }
  ],
  password: [{ required: true, message: '密码不能为空', trigger: 'blur' }]
});

const form = reactive<LoginForm>({
  username: '',
  password: ''
});

const formDom = ref<FormInstance>();

const router = useRouter();
const login = (frm: FormInstance | undefined) => {
  if (!frm) return;
  frm.validate((valid, fields) => {
    if (valid) {
      loginApi(form.username, form.password)
        .then(res => {
          if (res.code === 220) {
            showMessage(res.msg, 'success');
            const { setRoleInfo, setLoginFlag } = useRoleInfoStore();
            setRoleInfo(res.data);
            setLoginFlag(true);
            router.push('/');
          } else {
            showMessage(res.msg, 'warning');
          }
        })
        .catch(err => {});
    }
  });
};
</script>

<style lang="scss" scoped>
.login-view {
  width: 100vw;
  height: 100vh;
  background-color: $rlbg-default;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  .login-title {
    font-family: 'YouSheBiaoTiHei';
    font-size: 2rem;
    color: $rlt-title;
    text-transform: uppercase;
  }
  .login-form {
    min-width: 20rem;
    margin: 27px 0;
    box-sizing: border-box;
    padding: 0 18px;
    padding-top: 30px;
    padding-bottom: 12px;
    border-radius: 12px;
    box-shadow: -6px -6px 10px -1px $wshadow70, 6px 6px 10px -1px $bshadow15;
  }
  .login-btns {
    min-width: 20rem;
  }
  .login-btn {
    width: 100%;
  }
}
</style>

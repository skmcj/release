<template>
  <div class="vm-lword-form">
    <div class="vm-lword-form-header">
      <div class="box nickname">
        <input class="input" placeholder="昵称" v-model="nickname" />
      </div>
      <div class="box email">
        <input class="input" type="email" placeholder="邮箱" v-model="email" />
      </div>
    </div>
    <div class="vm-lword-form-content">
      <textarea class="input textarea" v-model="text" placeholder="留下属于你的足迹吧~"></textarea>
    </div>
    <div class="vm-lword-form-send">
      <VMButton text="发送" radius="12px" width="72px" height="36px" @on-click="send" />
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import VMButton from './VMButton.vue';
import ESMessage from './EasyMessage';
import { validateEmail } from '@/utils/validateUtil';

const nickname = ref('');
const email = ref('');
const text = ref('');

// 初始化表单
const initForm = () => {
  nickname.value = '';
  email.value = '';
  text.value = '';
};

// 验证表单
const validateForm = () => {
  if (nickname.value === '') {
    showMessage('昵称不能为空！');
    return false;
  }
  if (!validateEmail(email.value)) {
    showMessage('邮箱为空或格式错误！');
    return false;
  }
  if (text.value === '') {
    showMessage('内容不能为空！');
    return false;
  }
  return true;
};

// 显示提示消息
const showMessage = (text: string) => {
  ESMessage({
    message: text
  });
};

// 发送留言
const send = () => {
  if (validateForm()) {
    // 验证通过发送
    initForm();
  }
};
</script>

<style lang="less" scoped>
.vm-lword-form {
  width: 100%;
  height: 100%;
  display: flex;
  flex-direction: column;
}
.vm-lword-form-header {
  width: 100%;
  height: 18%;
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 10px;
  .nickname {
    width: 34%;
    margin-right: 10px;
  }
  .email {
    flex-grow: 1;
  }
  .box {
    box-sizing: border-box;
    padding: 0 8px;
    height: 100%;
    border-radius: 12px;
    box-shadow: inset -3px -3px 6px 0px var(--wshadow70), inset 3px 3px 6px 0px var(--bshadow15);
  }
}
.vm-lword-form-content {
  position: relative;
  width: 100%;
  flex-grow: 1;
  box-sizing: border-box;
  padding: 8px;
  border-radius: 12px;
  box-shadow: inset -3px -3px 6px 0px var(--wshadow70), inset 3px 3px 6px 0px var(--bshadow15);
}
.vm-lword-form-send {
  position: absolute;
  bottom: 18px;
  right: 18px;
}
.input {
  outline: none;
  width: 100%;
  height: 100%;
  background: none;
  border: none;
  font-size: 16px;
  color: var(--primary-text);
  font-family: 'zcool-kuaile';
  &::-webkit-input-placeholder {
    color: var(--placeholder-text);
  }
}
.textarea {
  resize: none;
  &::-webkit-scrollbar {
    width: 6px;
    height: 6px;
  }
  &::-webkit-scrollbar-thumb {
    background: var(--thumb-bg);
    border-radius: 3px;
  }
}
</style>

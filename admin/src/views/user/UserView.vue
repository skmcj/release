<template>
  <div class="user-view page-view">
    <div class="page-top">
      <div class="page-tl">
        <!-- 指定前台展示用户 -->
        <div class="cl-item">
          <span class="label">当前用户：</span>
          <el-select
            v-model="currentUserId"
            placeholder="选择展示用户"
            style="width: 150px; margin-right: 12px"
            @change="changeCurrent">
            <el-option v-for="item in userList" :key="item.id" :label="item.nickname" :value="item.id"></el-option>
          </el-select>
          <el-button v-show="ccfBtnFlag" @click="updateCurrent">修改</el-button>
        </div>
      </div>
      <div class="page-tr">
        <el-button type="primary" @click="linkRouter('/user/add')">添加用户</el-button>
      </div>
    </div>
    <div class="page-mid">
      <el-table :data="tableData" stripe style="min-width: 1000px" row-key="id">
        <el-table-column prop="nickname" label="昵称" width="120" />
        <el-table-column prop="address" label="地址" width="160" />
        <el-table-column prop="avatar" label="头像" width="120">
          <template #default="{ row }">
            <img class="table-avatar" :src="row.avatar" alt="avatar" />
          </template>
        </el-table-column>
        <el-table-column prop="sex" label="性别" width="96">
          <template #default="{ row }">
            <el-tooltip placement="top" :content="sexText[row.sex]">
              <i class="sex-icon" :class="`ir-${sexIcon[row.sex]}`"></i>
            </el-tooltip>
          </template>
        </el-table-column>
        <el-table-column prop="level" label="境界" width="96" />
        <el-table-column prop="year" label="开始年份" />
        <el-table-column prop="author" label="作者" />
        <el-table-column prop="startTime" label="开始时间">
          <template #default="{ row }">
            <span class="text">{{ row.startTime.split(' ')[0] }}</span>
          </template>
        </el-table-column>
        <el-table-column align="center" label="操作">
          <template #default="{ row }">
            <el-button type="primary" link @click="linkRouter('/user/edit', row.id)">编辑</el-button>
            <el-popconfirm
              width="220"
              confirm-button-text="确认"
              cancel-button-text="取消"
              icon-color="#e83929"
              title="您是否确认删除该项？"
              @confirm="deleteUser(row.id)">
              <template #reference>
                <el-button type="danger" link>删除</el-button>
              </template>
            </el-popconfirm>
          </template>
        </el-table-column>
      </el-table>
    </div>
    <div class="page-bottom">
      <el-pagination
        v-model:current-page="currentPage"
        v-model:page-size="pageSize"
        :page-sizes="[5, 10, 15, 20]"
        background
        layout="sizes, prev, pager, next"
        :total="total"
        hide-on-single-page
        @size-change="handleSizeChange"
        @current-change="handleCurrentChange" />
    </div>
  </div>
</template>

<script setup lang="ts">
import { onBeforeMount, ref } from 'vue';
import { useRouter } from 'vue-router';
import {
  ElTable,
  ElTableColumn,
  ElTooltip,
  ElButton,
  ElSelect,
  ElOption,
  ElPagination,
  ElPopconfirm
} from 'element-plus';
import { useCurrentInfoStore } from '@/stores/currentinfo';
import { getUserPageApi, delUserApi, getUserListApi } from '@/api/userApi';
import { setCurrentApi } from '@/api/currentApi';
import type { UserInfo, UserShortInfo } from '@/types';
import { useViewParamsStore } from '@/stores/viewparams';
import { showMessage } from '@/utils/commonUtil';

const sexIcon = ['sex', 'boy', 'girl'];
const sexText = ['未知', '男', '女'];

const { getCurrentUserId, setCurrentUserId, getCurrentId } = useCurrentInfoStore();

const currentUserId = ref('');
const ccfBtnFlag = ref(false);

// 用户缩略列表
const userList = ref<UserShortInfo[]>([]);

const getUserList = () => {
  currentUserId.value = getCurrentUserId();
  getUserListApi().then(res => {
    if (res.data) {
      userList.value = res.data;
    }
  });
};

const changeCurrent = () => {
  if (currentUserId.value === getCurrentUserId()) ccfBtnFlag.value = false;
  else ccfBtnFlag.value = true;
};

const updateCurrent = () => {
  setCurrentApi({
    id: getCurrentId(),
    userId: currentUserId.value
  }).then(res => {
    if (res.code === 213) {
      showMessage('指向用户修改成功', 'success');
      setCurrentUserId(currentUserId.value);
      changeCurrent();
    } else {
      showMessage(res.msg, 'warning');
    }
  });
};

// 页面传参
const { setUserId } = useViewParamsStore();

const router = useRouter();

// 跳转路由
const linkRouter = (link: string | any, id: string | undefined = undefined) => {
  if (id) setUserId(id);
  router.push(link);
};

// 表格数据
const tableData = ref<UserInfo[]>([]);
const currentPage = ref(1);
const pageSize = ref(5);
const total = ref(0);

const getPage = () => {
  getUserPageApi(currentPage.value, pageSize.value).then(res => {
    if (res.data) {
      tableData.value = res.data.list;
      total.value = res.data.total;
    }
  });
};

const deleteUser = (id: string) => {
  delUserApi(id).then(res => {
    if (res.code === 212) {
      showMessage('删除成功', 'success');
      getPage();
    } else {
      showMessage(res.msg, 'warning');
    }
  });
};

onBeforeMount(() => {
  getUserList();
  getPage();
});

const handleSizeChange = (val: number) => {
  pageSize.value = val;
  getPage();
};
const handleCurrentChange = (val: number) => {
  currentPage.value = val;
  getPage();
};
</script>

<style lang="scss" scoped></style>

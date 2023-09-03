<template>
  <div class="article-view page-view">
    <div class="page-top">
      <div class="page-tl">
        <el-input
          v-model="searchKey"
          placeholder="请输入标题关键词"
          clearable
          style="width: 180px; margin-right: 12px"></el-input>
        <el-button type="primary" @click="search">搜索</el-button>
      </div>
      <div class="page-rl">
        <el-button type="primary" @click="linkRouter('/article/add')">添加文章</el-button>
      </div>
    </div>
    <div class="page-mid">
      <el-table :data="tableData" stripe style="min-width: 1000px" row-key="id">
        <el-table-column prop="id" label="ID" width="180" />
        <el-table-column prop="title" label="标题" width="160" />
        <el-table-column prop="cover" label="封面" width="180" class-name="preview-img">
          <template #default="{ row }">
            <el-image
              v-if="row.cover"
              style="width: 160px; height: 100px; border-radius: 8px"
              :src="calcWallhavenSmallImg(row.cover)"
              hide-on-click-modal
              preview-teleported
              :preview-src-list="[row.cover]"
              fit="cover" />
          </template>
        </el-table-column>
        <el-table-column prop="description" label="描述" min-width="200" />
        <el-table-column prop="tags" label="标签" width="160" class-name="labels">
          <template #default="{ row }">
            <el-tag v-for="(tag, index) in row.tags" :key="`${row.id}-${index}`">
              {{ tag }}
            </el-tag>
          </template>
        </el-table-column>
        <el-table-column prop="count" label="字数" width="81" class-name="inline-center" />
        <el-table-column prop="eyes" label="阅读量" width="81" class-name="inline-center" />
        <el-table-column prop="disabled" label="可用性" width="72" class-name="inline-center">
          <template #default="{ row }">
            <span>{{ row.disabled ? '❌' : '✅' }}</span>
          </template>
        </el-table-column>
        <el-table-column align="center" label="操作" width="180" class-name="inline-center clean">
          <template #default="{ row }">
            <el-button type="info" link @click="linkPage(row.path)">查看</el-button>
            <el-button type="primary" link @click="linkRouter('/article/edit', row.id)">编辑</el-button>
            <el-button type="success" link @click="linkRouter('/article/editor', row.id)">编写</el-button>
            <el-popconfirm
              width="220"
              confirm-button-text="确认"
              cancel-button-text="取消"
              icon-color="#f6ad49"
              :title="`您是否确认${row.disabled ? '启用' : '禁用'}该项？`"
              @confirm="editDisabled(row)">
              <template #reference>
                <el-button type="warning" link>{{ row.disabled ? '启用' : '禁用' }}</el-button>
              </template>
            </el-popconfirm>
            <el-popconfirm
              width="220"
              confirm-button-text="确认"
              cancel-button-text="取消"
              icon-color="#e83929"
              title="您是否确认删除该项？"
              @confirm="delArticle(row.id)">
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
import { ElInput, ElButton, ElTable, ElTableColumn, ElPagination, ElImage, ElPopconfirm, ElTag } from 'element-plus';
import type { ArticleInfo } from '@/types';
import { delArticleApi, editArticleApi, getArticlePageApi } from '@/api/articleApi';
import { showMessage, calcWallhavenSmallImg } from '@/utils/commonUtil';
import { useViewParamsStore } from '@/stores/viewparams';

// 跳转路由
const router = useRouter();
const { setArticleId } = useViewParamsStore();
const linkRouter = (link: string | any, id: string | undefined = undefined) => {
  if (id) setArticleId(id);
  router.push(link);
};

const linkPage = (link: string) => {
  if (link) window.open(link, '_blank');
};

const searchKey = ref('');
const search = () => {
  getPage();
};

// 列表
const tableData = ref<ArticleInfo[]>([]);
const currentPage = ref(1);
const pageSize = ref(5);
const total = ref(0);

const getPage = () => {
  getArticlePageApi(currentPage.value, pageSize.value, searchKey.value).then(res => {
    if (res.code === 214) {
      tableData.value = res.data.list;
      total.value = res.data.total;
    } else {
      tableData.value = [];
      total.value = 0;
    }
  });
};
onBeforeMount(() => {
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

// 删除
const delArticle = (id: string) => {
  delArticleApi(id).then(res => {
    if (res.code === 212) {
      showMessage('删除文章成功', 'success');
      getPage();
    } else {
      showMessage(res.msg, 'warning');
    }
  });
};

// 禁用启用
const editDisabled = (data: ArticleInfo) => {
  editArticleApi({
    id: data.id,
    disabled: !data.disabled
  }).then(res => {
    if (res.code === 213) {
      showMessage(`${data.disabled ? '启用' : '禁用'}文章成功`, 'success');
      getPage();
    } else {
      showMessage(res.msg, 'warning');
    }
  });
};
</script>

<style lang="scss" scoped></style>

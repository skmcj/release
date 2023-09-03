<template>
  <div class="product-view page-view">
    <div class="page-top">
      <div class="page-tl">
        <el-input
          v-model="searchKey"
          placeholder="请输入作品关键词"
          clearable
          style="width: 180px; margin-right: 12px"></el-input>
        <el-button type="primary" @click="search">搜索</el-button>
      </div>
      <div class="page-rl">
        <el-button type="primary" @click="linkRouter('/product/add')">添加作品</el-button>
      </div>
    </div>
    <div class="page-mid">
      <el-table :data="tableData" stripe style="min-width: 1000px" row-key="id">
        <el-table-column prop="id" label="ID" width="180" />
        <el-table-column prop="logoUrl" label="Logo" width="96" class-name="preview-img">
          <template #default="{ row }">
            <el-image style="width: 81px; height: 81px; border-radius: 8px" :src="row.logoUrl" fit="cover" />
          </template>
        </el-table-column>
        <el-table-column prop="name" label="名称" width="120" />
        <el-table-column prop="tip" label="简介" min-width="180" />
        <el-table-column prop="compDate" label="完成日期" width="120" />
        <el-table-column prop="disabled" label="可用性" width="72" class-name="inline-center">
          <template #default="{ row }">
            <span>{{ row.disabled ? '❌' : '✅' }}</span>
          </template>
        </el-table-column>
        <el-table-column prop="articleId" label="介绍文档" width="81" class-name="inline-center">
          <template #default="{ row }">
            <el-button v-if="row.articleId" type="primary" link @click="viewArticle(row.articleId)">查看</el-button>
          </template>
        </el-table-column>
        <el-table-column prop="updateTime" label="更新时间" width="180" />
        <el-table-column align="center" label="操作" width="120" class-name="inline-center clean">
          <template #default="{ row }">
            <el-button type="info" link @click="linkRouter('/product/label', row.id)">标签</el-button>
            <el-button type="primary" link @click="linkRouter('/product/edit', row.id)">编辑</el-button>
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
              @confirm="delProduct(row.id)">
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
import { ElButton, ElInput, ElTable, ElTableColumn, ElPagination, ElPopconfirm, ElImage } from 'element-plus';
import { delProductApi, editProductApi, getProductPageApi } from '@/api/productApi';
import type { ProductInfo } from '@/types';
import { showMessage } from '@/utils/commonUtil';
import { useViewParamsStore } from '@/stores/viewparams';
import { getArticleByIdApi } from '@/api/articleApi';

const router = useRouter();

const { setProductId } = useViewParamsStore();

// 跳转路由
const linkRouter = (link: string | any, id: string | undefined = undefined) => {
  if (id) setProductId(id);
  router.push(link);
};
const linkPage = (link: string) => {
  if (link) window.open(link, '_blank');
};

/**
 * 跳转查看文章
 * @param id
 */
const viewArticle = (id: string) => {
  getArticleByIdApi(id).then(res => {
    if (res.code === 214) {
      linkPage(res.data.path);
    } else {
      showMessage(res.msg, 'warning');
    }
  });
};

const searchKey = ref('');
const search = () => {
  getPage();
};

// 列表
const tableData = ref<ProductInfo[]>([]);
const currentPage = ref(1);
const pageSize = ref(5);
const total = ref(0);

const getPage = () => {
  getProductPageApi(currentPage.value, pageSize.value, searchKey.value).then(res => {
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
const delProduct = (id: string) => {
  delProductApi(id).then(res => {
    if (res.code === 212) {
      showMessage('删除留言成功', 'success');
      getPage();
    } else {
      showMessage(res.msg, 'warning');
    }
  });
};

// 禁用启用
const editDisabled = (data: ProductInfo) => {
  editProductApi({
    id: data.id,
    disabled: !data.disabled
  }).then(res => {
    if (res.code === 213) {
      showMessage(`${data.disabled ? '启用' : '禁用'}作品成功`, 'success');
      getPage();
    } else {
      showMessage(res.msg, 'warning');
    }
  });
};
</script>

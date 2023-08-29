<template>
  <div class="image-view page-view">
    <div class="page-top">
      <div class="page-tl">
        <!-- 指定前台展示用户 -->
        <div class="cl-item">
          <span class="label">当前类型：</span>
          <el-select
            v-model="currentType"
            placeholder="选择展示类型"
            style="width: 150px; margin-right: 12px"
            @change="changeCurrent">
            <el-option v-for="item in typeList" :key="item.id" :label="item.label" :value="item.value"></el-option>
          </el-select>
          <el-button v-show="ccfBtnFlag" @click="updateCurrent">修改</el-button>
        </div>
      </div>
      <div class="page-tr">
        <el-select
          v-model="searchType"
          placeholder="搜索类型"
          style="width: 108px"
          @change="
            () => {
              searchKey = '';
            }
          ">
          <el-option v-for="item in searchTypeList" :key="item.id" :label="item.label" :value="item.value" />
        </el-select>
        <el-input
          v-if="searchType === 'name' || searchType === 'label'"
          v-model="searchKey"
          placeholder="内容关键词(回车搜索)"
          @keyup.enter="
            (e: any) => {
              getPage();
              e.target.blur();
            }
          "
          style="width: 210px">
          <template #prefix>
            <i class="ir-search"></i>
          </template>
        </el-input>
        <el-select
          v-else
          ref="selectKeyDom"
          v-model="searchKey"
          placeholder="选择关键词"
          clearable
          style="width: 210px"
          @change="(e: any) => {
              getPage();
              selectKeyDom.blur();
            }">
          <el-option v-for="item in searchKeyList" :key="item.id" :label="item.label" :value="item.value" />
        </el-select>
        <el-button type="primary" @click="linkRouter('/image/add')">添加图片</el-button>
        <el-button type="warning" @click="linkRouter('/image/batch')">批量添加</el-button>
      </div>
    </div>
    <div class="page-mid">
      <el-table :data="tableData" stripe style="min-width: 1000px" row-key="id">
        <el-table-column prop="id" label="ID" width="180" />
        <el-table-column prop="name" label="名称" />
        <el-table-column prop="url" label="预览" width="180" class-name="preview-img">
          <template #default="{ row }">
            <el-image
              style="width: 160px; height: 100px; border-radius: 8px"
              :src="calcWallhavenSmallImg(row.url)"
              hide-on-click-modal
              preview-teleported
              :preview-src-list="[row.url]"
              fit="cover" />
          </template>
        </el-table-column>
        <el-table-column prop="type" label="显示类型" width="81" class-name="inline-center">
          <template #default="{ row }">
            <el-tooltip effect="dark" :content="row.type" placement="top">
              <i>{{ row.type === 'pc' ? '🖥️' : row.type === 'phone' ? '📱' : '🔗' }}</i>
            </el-tooltip>
          </template>
        </el-table-column>
        <el-table-column prop="w" label="宽×高" width="120" class-name="inline-center">
          <template #default="{ row }">
            <span>{{ `${row.w ?? '暂无'} × ${row.h ?? '暂无'}` }}</span>
          </template>
        </el-table-column>
        <el-table-column prop="size" label="大小(字节)" width="100" />
        <el-table-column prop="imageType" label="图片类型" width="100" class-name="inline-center" />
        <el-table-column prop="disabled" label="可用性" width="72" class-name="inline-center">
          <template #default="{ row }">
            <span>{{ row.disabled ? '❌' : '✅' }}</span>
          </template>
        </el-table-column>
        <el-table-column prop="labels" label="标签" width="120" class-name="labels">
          <template #default="{ row }">
            <el-tag v-for="item in row.labels" :key="item" effect="plain" size="small">{{ item }}</el-tag>
          </template>
        </el-table-column>
        <el-table-column prop="showDate" label="显示日期" width="120" />
        <el-table-column align="center" label="操作">
          <template #default="{ row }">
            <el-button type="primary" link @click="linkRouter('/image/edit', row.id)">编辑</el-button>
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
              @confirm="delImage(row.id)">
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
import { computed, onBeforeMount, ref } from 'vue';
import { useRouter } from 'vue-router';
import {
  ElSelect,
  ElButton,
  ElInput,
  ElImage,
  ElTag,
  ElOption,
  ElTable,
  ElTableColumn,
  ElPagination,
  ElPopconfirm,
  ElTooltip
} from 'element-plus';
import { useCurrentInfoStore } from '@/stores/currentinfo';
import { setCurrentApi } from '@/api/currentApi';
import { showMessage, calcWallhavenSmallImg } from '@/utils/commonUtil';
import { getImagePageApi, delImageApi, editImageApi } from '@/api/imageApi';
import type { ImageInfo } from '@/types';
import { useViewParamsStore } from '@/stores/viewparams';

const { setImageId } = useViewParamsStore();
const router = useRouter();

// 跳转路由
const linkRouter = (link: string | any, id: string | undefined = undefined) => {
  if (id) setImageId(id);
  router.push(link);
};

// 每日一图展示类型
const currentType = ref(0);
const ccfBtnFlag = ref(false);

const typeList = [
  {
    id: 'e584dfec9756800de07aa88300038776',
    value: 0,
    label: '日常'
  },
  {
    id: '1567a46e79f69f159edcbeba19683b9f',
    value: 1,
    label: '随机'
  }
];

const { getCurrentId, getCurrentImageType, setCurrentImageType } = useCurrentInfoStore();

const changeCurrent = () => {
  if (currentType.value === getCurrentImageType()) ccfBtnFlag.value = false;
  else ccfBtnFlag.value = true;
};

const updateCurrent = () => {
  setCurrentApi({
    id: getCurrentId(),
    imageType: currentType.value
  }).then(res => {
    if (res.code === 213) {
      showMessage('图片展示类型修改成功', 'success');
      setCurrentImageType(currentType.value);
      changeCurrent();
    } else {
      showMessage(res.msg, 'warning');
    }
  });
};

// 搜索
const selectKeyDom = ref();
const searchKey = ref('');
const searchType = ref('name');
const searchTypeList = [
  { id: '38c86f4825eab14d11fb43397b634cf7', label: '名称', value: 'name' },
  { id: '28ff04937d1474c684ac613f48406f52', label: '标签', value: 'label' },
  { id: '03b32e5dddeb076ce316de7aa817ec0d', label: '显示类型', value: 'type' },
  { id: 'dc68d31fb9b2f8c5c18bc8b77ebbf4ad', label: '图片类型', value: 'imageType' }
];
const searchKeyList = computed(() => {
  if (searchType.value === 'type') {
    return [
      { id: '169b3e32cf457a3d0e36d7c4aa13ed0c', label: '🖥️', value: 'pc' },
      { id: 'a3e6349d6bf885d9215f207174d4a17f', label: '📱', value: 'phone' },
      { id: '81acfd1c72f871c63c1a3066ce274bc1', label: '🔗', value: 'other' }
    ];
  } else if (searchType.value === 'imageType') {
    return [
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
  } else return [];
});

// 表格数据
const tableData = ref<ImageInfo[]>([]);
const currentPage = ref(1);
const pageSize = ref(5);
const total = ref(0);

const getPage = () => {
  getImagePageApi(currentPage.value, pageSize.value, searchKey.value, searchType.value).then(res => {
    if (res.data) {
      tableData.value = res.data.list;
      total.value = res.data.total;
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

// 禁用启用
const editDisabled = (data: ImageInfo) => {
  editImageApi({
    id: data.id,
    disabled: !data.disabled
  }).then(res => {
    if (res.code === 213) {
      showMessage(`图片${data.disabled ? '启用' : '禁用'}成功`, 'success');
      getPage();
    } else {
      showMessage(res.msg, 'warning');
    }
  });
};

// 删除图片
const delImage = (id: string) => {
  delImageApi(id).then(res => {
    if (res.code === 212) {
      showMessage('图片删除成功', 'success');
      getPage();
    } else {
      showMessage(res.msg, 'warning');
    }
  });
};
</script>

<style lang="scss" scoped></style>

<template>
  <div class="app-container">
    <div class="filter-container">
      <el-form>
        <el-form-item>
          <el-select
            v-model="listItemQuery.project_id"
            class="filter-item"
            placeholder="Chọn dự án"
            name="project_id"
          >
            <el-option
              v-for="project in list"
              :key="project.id"
              :label="project.id + ' --- ' + project.project_name"
              :value="project.id"
            />
          </el-select>
          <el-button
            class="filter-item"
            type="success"
            icon="el-icon-info"
            @click="handleShowItems()"
          >
          </el-button>
        </el-form-item>
      </el-form>
      <el-button
        class="filter-item"
        type="primary"
        icon="el-icon-plus"
        @click="handleCreate()"
      >
        Thêm danh mục
      </el-button>
    </div>

    <el-table
      :key="tableKey"
      v-loading="listItemLoading"
      :data="listParentItem"
      border
      fit
      highlight-current-row
      style="width: 100%"
      @sort-change="sortChange"
    >
      <el-table-column
        label="Thao tác"
        width="200"
        class-name="small-padding fixed-width"
      >
        <template slot-scope="{ row }">
          <el-button
            size="mini"
            type="primary"
            icon="el-icon-edit"
            @click="handleUpdate(row)"
          >
            Sửa
          </el-button>
          <el-button
            size="mini"
            type="danger"
            icon="el-icon-delete"
            @click="handleDelete(row)"
          >
            Xóa
          </el-button>
        </template>
      </el-table-column>
      <el-table-column label="ID" align="center" width="60">
        <template slot-scope="{ row }">
          <span>{{ row.id }}</span>
        </template>
      </el-table-column>
      <el-table-column label="Độ ưu tiên" width="100">
        <template slot-scope="{ row }">
          <el-tag>{{ row.order }}</el-tag>
        </template>
      </el-table-column>
      <el-table-column label="Mục cha" width="200">
        <template slot-scope="{ row }">
          <span class="link-type" @click="handleUpdate(row)">
            {{ row.name }}
          </span>
        </template>
      </el-table-column>
      <el-table-column label="Mục con" width="540">
        <el-table
          :key="tableKey"
          slot-scope="{ row }"
          v-loading="listItemLoading"
          :data="row.children"
          border
          fit
          highlight-current-row
          style="width: 100%"
          @sort-change="sortChange"
        >
          <el-table-column label="ID" align="center" width="68">
            <template slot-scope="{ row }">
              <span>{{ row.id }}</span>
            </template>
          </el-table-column>
          <el-table-column label="Độ ưu tiên" width="100">
            <template slot-scope="{ row }">
              <el-tag>{{ row.order }}</el-tag>
            </template>
          </el-table-column>
          <el-table-column label="Tên" width="200">
            <template slot-scope="{ row }">
              <span class="link-type" @click="handleUpdate(row)">
                {{ row.name }}
              </span>
            </template>
          </el-table-column>
          <el-table-column
            label="Thao tác"
            width="150"
            class-name="small-padding fixed-width"
          >
            <template slot-scope="{ row }">
              <el-button
                size="mini"
                type="primary"
                icon="el-icon-edit"
                @click="handleUpdate(row)"
              >
                Sửa
              </el-button>
              <el-button
                size="mini"
                type="danger"
                icon="el-icon-delete"
                @click="handleDelete(row)"
              >
                Xóa
              </el-button>
            </template>
          </el-table-column>
        </el-table>
      </el-table-column>
    </el-table>

    <el-dialog
      v-if="dialogStatus === 'create'"
      :title="textMap[dialogStatus]"
      :visible.sync="dialogFormVisible"
    >
      <el-form
        ref="dataForm"
        :rules="rules"
        :model="temp"
        label-position="left"
        label-width="150px"
        style="width: 400px margin-left: 50px"
      >
        <el-form-item label="Mục cha">
          <el-select
            v-model="temp.parent_id"
            class="filter-item"
            placeholder="Chọn mục cha"
            name="parent_id"
          >
            <el-option
              v-for="item in listParentItem"
              :key="item.id"
              :label="item.id + ' --- ' + item.name"
              :value="item.id"
            />
          </el-select>
        </el-form-item>
        <el-form-item label="Tên mục" prop="name">
         <el-input v-model="temp.name" name="name" />
        </el-form-item>
        <el-form-item label="Độ ưu tiên" prop="order">
         <el-input v-model="temp.order" type="number" name="name" />
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false"> Cancel </el-button>
        <el-button type="primary" @click="createData()"> Xác nhận </el-button>
      </div>
    </el-dialog>

    <el-dialog
      v-if="dialogStatus === 'update'"
      :title="textMap[dialogStatus]"
      :visible.sync="dialogFormVisible"
    >
      <el-form
        ref="dataForm"
        :rules="rules"
        :model="temp"
        label-position="left"
        label-width="150px"
        style="width: 400px margin-left: 50px"
      >
        <el-form-item label="Tên mục" prop="name">
          <el-input v-model="temp.name" name="name" />
        </el-form-item>
        <el-form-item label="Độ ưu tiên" prop="order">
          <el-input v-model="temp.order" type="number" name="name" />
        </el-form-item>
        <el-form-item label="Mục cha" prop="name">
          <el-select
            v-if="temp.parent_id !== null"
            v-model="temp.parent_id"
            class="filter-item"
            placeholder="Chọn mục cha"
            name="parent_id"
          >
            <el-option
              v-for="item in listParentItem"
              :key="item.id"
              :label="item.id + ' --- ' + item.name"
              :value="item.id"
            />
          </el-select>
          <el-select
            v-else
            v-model="temp.parent_id"
            class="filter-item"
            placeholder="Chọn mục cha"
            name="parent_id"
            disabled
          >
          </el-select>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false"> Cancel </el-button>
        <el-button type="primary" @click="updateData()"> Xác nhận </el-button>
      </div>
    </el-dialog>

    <pagination
      v-show="total > 0"
      :total="total"
      :page.sync="listQuery.page"
      :limit.sync="listQuery.limit"
      @pagination="getList"
    />
  </div>
</template>

<script>
import { getProjects } from '@/api/project'
import { getItems, updateItem, createItem, deleteItem } from '@/api/item'
import waves from '@/directive/waves' // waves directive
import { parseTime } from '@/utils'
import Pagination from '@/components/Pagination' // secondary package based on el-pagination

const calendarRoleOptions = []

const calendarRoleKeyValue = calendarRoleOptions.reduce((acc, cur) => {
  acc[cur.key] = cur.display_name
  return acc
}, {})

export default {
  name: 'Item',
  components: { Pagination },
  directives: { waves },
  filters: {
    statusFilter(status) {
      const statusMap = {
        published: 'success',
        draft: 'info',
        deleted: 'danger'
      }
      return statusMap[status]
    },
    typeFilter(role) {
      return calendarRoleKeyValue[role]
    }
  },
  data() {
    return {
      tableKey: 0,
      list: null,
      listParentItem: null,
      detail: null,
      total: 0,
      listLoading: false,
      detailLoading: true,
      listItemLoading: false,
      userOptions: null,
      listQuery: {
        project_name: '',
        sort: '+id'
      },
      listItemQuery: {
        page: 1,
        limit: 10,
        project_id: ''
      },
      calendarRoleOptions,
      sortOptions: [
        { label: 'ID Ascending', key: '+id' },
        { label: 'ID Descending', key: '-id' }
      ],
      showReviewer: false,
      temp: {
        id: undefined,
        importance: 1,
        name: '',
        order: '',
        parent_id: '',
        project_id: ''
      },
      dialogFormVisible: false,
      dialogStatus: '',
      textMap: {
        update: 'Chỉnh sửa',
        create: 'Tạo mới'
      },
      dialogPvVisible: false,
      pvData: [],
      rules: {
        name: [{ required: true, message: 'Tên mục không được để trống' }],
        order: [{ required: true, message: 'Hãy chọn mức độ ưu tiên' }]
      },
      downloadLoading: false
    }
  },
  created() {
    this.getList()
  },
  methods: {
    getList() {
      this.listLoading = true
      getProjects(this.listQuery).then((response) => {
        this.list = response.data
        // Just to simulate the time of the request
        setTimeout(() => {
          this.listLoading = false
        }, 1.5 * 1000)
      })
    },
    handleShowItems() {
      this.listItemLoading = false
      getItems(this.listItemQuery).then((response) => {
        this.listParentItem = response.data
        this.total = response.data.length
        // Just to simulate the time of the request
        setTimeout(() => {
          this.listItemLoading = false
        }, 1.5 * 1000)
      })
    },
    handleFilter() {
      this.listQuery.page = 1
      this.getList()
    },
    sortChange(data) {
      const { prop, order } = data
      if (prop === 'id') {
        this.sortByID(order)
      }
    },
    sortByID(order) {
      if (order === 'ascending') {
        this.listQuery.sort = '+id'
      } else {
        this.listQuery.sort = '-id'
      }
      this.handleFilter()
    },
    resetTemp() {
      this.temp = {
        id: undefined,
        importance: 1,
        name: '',
        order: '',
        parent_id: '',
        project_id: ''
      }
    },
    handleCreate() {
      this.resetTemp()
      this.dialogStatus = 'create'
      this.dialogFormVisible = true
      this.$nextTick(() => {
        this.$refs['dataForm'].clearValidate()
      })
    },
    createData() {
      this.$refs['dataForm'].validate((valid) => {
        if (valid) {
          this.temp.id = parseInt(Math.random() * 100) + 1024
          createItem(this.listItemQuery.project_id, this.temp).then((response) => {
            this.dialogFormVisible = false
            this.$notify({
              title: response.message,
              type: 'success',
              duration: 2000
            })
            this.rules = response.data
          })
          getItems(this.listItemQuery).then((response) => {
            this.listParentItem = response.data
            this.listItemLoading = false
          })
        }
      })
    },
    handleUpdate(row) {
      this.temp = Object.assign({}, row)
      this.dialogStatus = 'update'
      this.dialogFormVisible = true
      this.$nextTick(() => {
        this.$refs['dataForm'].clearValidate()
      })
    },
    updateData() {
      this.$refs['dataForm'].validate((valid) => {
        if (valid) {
          const tempData = Object.assign({}, this.temp)
          updateItem(this.temp.id, this.temp.project_id, tempData).then((response) => {
            this.dialogFormVisible = false
            this.$notify({
              title: response.message,
              type: 'success',
              duration: 2000
            })
          })
          getItems(this.listItemQuery).then((response) => {
            this.listParentItem = response.data
            this.listItemLoading = false
          })
        }
      })
    },
    handleDelete(row) {
      deleteItem(row.id).then((response) => {
        this.$notify({
          title: response.message,
          type: 'success',
          duration: 2000
        })
      })
      getItems(this.listItemQuery).then((response) => {
        this.listParentItem = response.data
        this.listItemLoading = false
      })
    },
    formatJson(filterVal) {
      return this.list.map((v) =>
        filterVal.map((j) => {
          if (j === 'timestamp') {
            return parseTime(v[j])
          } else {
            return v[j]
          }
        })
      )
    },
    getSortClass: function(key) {
      const sort = this.listQuery.sort
      return sort === `+${key}` ? 'ascending' : 'descending'
    }
  }
}
</script>

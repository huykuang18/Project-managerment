<template>
  <div class="app-container">
    <div class="filter-container">
      <el-input
        v-model="listQuery.project_name"
        placeholder="Tên dự án"
        style="width: 200px"
        class="filter-item"
        @keyup.enter.native="handleFilter"
      />
      <el-button
        v-waves
        class="filter-item"
        type="primary"
        icon="el-icon-search"
        @click="handleFilter"
      >
      </el-button>
      <el-button
        class="filter-item"
        style="margin-left: 10px"
        type="primary"
        icon="el-icon-plus"
        @click="handleCreate"
      >
        Thêm
      </el-button>
    </div>

    <el-table
      :key="tableKey"
      v-loading="listLoading"
      :data="list"
      border
      fit
      highlight-current-row
      style="width: 100%"
      @sort-change="sortChange"
    >
      <el-table-column
        label="ID"
        prop="id"
        sortable="custom"
        align="center"
        width="80"
        :class-name="getSortClass('id')"
      >
        <template slot-scope="{ row }">
          <span>{{ row.id }}</span>
        </template>
      </el-table-column>
      <el-table-column label="Tên dự án" width="200px">
        <template slot-scope="{ row }">
          <span class="link-type" @click="handleUpdate(row)">{{
            row.project_name
          }}</span>
        </template>
      </el-table-column>
      <!-- <el-table-column label="Quản lý dự án" width="150px">
        <template slot-scope="{ row }">
          <span>{{ row.manager_id }}</span>
        </template>
      </el-table-column> -->
      <el-table-column label="Khách hàng" min-width="200px">
        <template slot-scope="{ row }">
          <span>{{ row.customer }}</span>
        </template>
      </el-table-column>
      <el-table-column label="Bắt đầu" min-width="100px">
        <template slot-scope="{ row }">
          <el-tag>{{ row.date_start }}</el-tag>
        </template>
      </el-table-column>
      <el-table-column label="Kết thúc" min-width="100px">
        <template slot-scope="{ row }">
          <el-tag>{{ row.date_end }}</el-tag>
        </template>
      </el-table-column>
      <el-table-column label="Dev(s)" min-width="100px">
        <template slot-scope="{ row }">
          <span>{{ row.dev_quantity }}</span>
        </template>
      </el-table-column>
      <el-table-column label="Tester(s)" min-width="100px">
        <template slot-scope="{ row }">
          <span>{{ row.test_quantity }}</span>
        </template>
      </el-table-column>
      <el-table-column
        label="Thao tác"
        width="200"
        class-name="small-padding fixed-width"
      >
        <template slot-scope="{ row, $index }">
          <el-button type="primary" size="mini" @click="handleUpdate(row)">
            Cập nhật
          </el-button>
          <el-button
            v-if="row.dev_quantity === 0 && row.test_quantity === 0"
            size="mini"
            type="danger"
            @click="handleDelete(row, $index)"
          >
            Xóa
          </el-button>
        </template>
      </el-table-column>
    </el-table>

    <pagination
      v-show="total > 0"
      :total="total"
      :page.sync="listQuery.page"
      :limit.sync="listQuery.limit"
      @pagination="getList"
    />

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
        <el-form-item label="Tên dự án" prop="project_name">
          <el-input v-model="temp.project_name" name="project_name" />
        </el-form-item>
        <el-form-item label="Khách hàng" prop="customer">
          <el-input v-model="temp.customer" name="customer" />
        </el-form-item>
        <el-form-item label="Ngày bắt đầu" prop="date_start">
          <el-input v-model="temp.date_start" type="date" name="date_start" />
        </el-form-item>
        <el-form-item label="Ngày kết thúc" prop="date_end">
          <el-input v-model="temp.date_end" type="date" name="date_end" />
        </el-form-item>
        <el-form-item label="Mô tả" prop="description">
          <el-input
            v-model="temp.description"
            type="textarea"
            name="description"
          />
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false"> Cancel </el-button>
        <el-button type="primary" @click="createData()"> Xác nhận </el-button>
      </div>
    </el-dialog>

    <el-dialog
      v-else
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
        <el-form-item label="Tên dự án" prop="project_name">
          <el-input v-model="temp.project_name" name="project_name" />
        </el-form-item>
        <el-form-item label="Khách hàng">
          <el-input v-model="temp.customer" name="customer" disabled />
        </el-form-item>
        <el-form-item label="Ngày bắt đầu" prop="date_start">
          <el-input v-model="temp.date_start" type="date" name="date_start" />
        </el-form-item>
        <el-form-item label="Ngày kết thúc" prop="date_end">
          <el-input v-model="temp.date_end" type="date" name="date_end" />
        </el-form-item>
        <el-form-item label="Mô tả" prop="description">
          <el-input
            v-model="temp.description"
            type="textarea"
            name="description"
          />
        </el-form-item>
        <el-form-item label="Quản lý dự án">
          <el-input v-model="temp.manager" name="manager" disabled />
        </el-form-item>
        <el-form-item label="Thành viên tham gia">
          <el-table
            :key="tableKey"
            v-loading="detailLoading"
            :data="detail"
            border
            fit
            highlight-current-row
            style="width: 100%"
            max-height="250"
            @sort-change="sortChange"
          >
            <el-table-column label="Vai trò" align="center" width="110">
              <template slot-scope="{ row }">
                <el-tag>{{ row.role }}</el-tag>
              </template>
            </el-table-column>
            <el-table-column label="Họ tên" width="170px" align="center">
              <template slot-scope="{ row }">
                <span>{{ row.name }}</span>
              </template>
            </el-table-column>
            <el-table-column label="Tài khoản" width="100px" align="center">
              <template slot-scope="{ row }">
                <span>{{ row.username }}</span>
              </template>
            </el-table-column>
            <el-table-column
              label="Thao tác"
              align="center"
              width="104"
              class-name="small-padding fixed-width"
            >
              <template slot-scope="{ row, $index }">
                <el-button
                  v-if="row.role != 'admin'"
                  size="mini"
                  type="danger"
                  @click="handleRemove(row, temp.id, $index)"
                >
                  Xóa
                </el-button>
              </template>
            </el-table-column>
          </el-table>
          <el-form style="margin-top: 10px">
            <el-form-item>
              <el-select
                v-model="temp.user_id"
                class="filter-item"
                placeholder="Thêm thành viên"
                name="user_id"
              >
                <el-option
                  v-for="item in userOptions"
                  :key="item.id"
                  :label="item.role +' --- '+ item.username"
                  :value="item.id"
                />
              </el-select>
              <el-button
                class="filter-item"
                style="margin-left: 10px"
                type="primary"
                icon="el-icon-plus"
                @click="handleAddMember(temp.id)"
              >
              </el-button>
            </el-form-item>
          </el-form>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false"> Cancel </el-button>
        <el-button type="primary" @click="updateData()"> Xác nhận </el-button>
      </div>
    </el-dialog>

    <el-dialog :visible.sync="dialogPvVisible" title="Reading statistics">
      <el-table
        :data="pvData"
        border
        fit
        highlight-current-row
        style="width: 100%"
      >
        <el-table-column prop="key" label="Channel" />
        <el-table-column prop="pv" label="Pv" />
      </el-table>
      <span slot="footer" class="dialog-footer">
        <el-button
          type="primary"
          @click="dialogPvVisible = false"
        >Confirm
        </el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
import {
  getProjects,
  getMembers,
  createProject,
  deleteProject,
  updateProject,
  addMember,
  removeMember
} from '@/api/project'
import { getUserOptions } from '@/api/user'
import waves from '@/directive/waves' // waves directive
import { parseTime } from '@/utils'
import Pagination from '@/components/Pagination' // secondary package based on el-pagination

const calendarRoleOptions = []

const calendarRoleKeyValue = calendarRoleOptions.reduce((acc, cur) => {
  acc[cur.key] = cur.display_name
  return acc
}, {})

export default {
  name: 'AdminProject',
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
      detail: null,
      total: 0,
      listLoading: true,
      detailLoading: true,
      userOptions: null,
      listQuery: {
        page: 1,
        limit: 10,
        project_name: '',
        sort: '+id'
      },
      importanceOptions: [1, 2, 3],
      calendarRoleOptions,
      sortOptions: [
        { label: 'ID Ascending', key: '+id' },
        { label: 'ID Descending', key: '-id' }
      ],
      showReviewer: false,
      temp: {
        id: undefined,
        importance: 1,
        project_name: '',
        customer: '',
        description: '',
        date_start: '',
        date_end: '',
        intend_time: '',
        user_id: '',
        dev_quantity: '',
        test_quantity: ''
      },
      dialogFormVisible: false,
      dialogStatus: '',
      textMap: {
        update: 'Chỉnh sửa',
        create: 'Tạo dự án'
      },
      dialogPvVisible: false,
      pvData: [],
      rules: {
        project_name: [
          { required: true, message: 'Tên dự án không được để trống' }
        ],
        customer: [
          { required: true, message: 'Khách hàng không được để trống' }
        ],
        date_start: [
          { required: true, message: 'Thời gian bắt đầu không được để trống' }
        ],
        date_end: [
          { required: true, message: 'Thời gian kết thúc không được để trống' }
        ],
        description: [{ required: true, message: 'Cần nhập mô tả cho dự án' }]
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
        this.total = response.data.length
        // Just to simulate the time of the request
        setTimeout(() => {
          this.listLoading = false
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
        project_name: '',
        manager: '',
        customer: '',
        description: '',
        date_start: '',
        date_end: '',
        intend_time: '',
        dev_quantity: 0,
        test_quantity: 0
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
          createProject(this.temp).then((response) => {
            this.list.unshift(this.temp)
            this.dialogFormVisible = false
            this.$notify({
              title: response.message,
              type: 'success',
              duration: 2000
            })
            this.rules = response.data
          })
        }
      })
    },
    handleUpdate(row) {
      getMembers(row.id).then((response) => {
        this.temp = response.data[0]
        this.detail = response.data[0]['users']
        this.detailLoading = false
        this.temp.manager = this.detail[0]['name']
      })
      this.dialogStatus = 'update'
      this.dialogFormVisible = true
      this.$nextTick(() => {
        this.$refs['dataForm'].clearValidate()
      })
      getUserOptions(row.id).then((response) => {
        this.userOptions = response.data
      })
    },
    updateData() {
      this.$refs['dataForm'].validate((valid) => {
        if (valid) {
          const tempData = Object.assign({}, this.temp)
          updateProject(this.temp.id, tempData).then((response) => {
            const index = this.list.findIndex((v) => v.id === this.temp.id)
            this.list.splice(index, 1, this.temp)
            this.dialogFormVisible = false
            this.$notify({
              title: response.message,
              type: 'success',
              duration: 2000
            })
          })
        }
      })
    },
    handleDelete(row, index) {
      deleteProject(row.id).then((response) => {
        this.$notify({
          title: response.message,
          type: 'success',
          duration: 2000
        })
      })
      this.list.splice(index, 1)
    },
    handleRemove(row, id, index) {
      removeMember(id, row.id).then((response) => {
        this.$notify({
          title: response.message,
          type: 'success',
          duration: 2000
        })
      })
      this.detail.splice(index, 1)
    },
    handleAddMember(id) {
      addMember(this.temp, id).then((response) => {
        this.$notify({
          title: response.message,
          type: 'success',
          duration: 2000
        })
        this.detail.unshift(response.data)
      })
    },
    handleDownload() {
      this.downloadLoading = true
      import('@/vendor/Export2Excel').then((excel) => {
        const tHeader = ['timestamp', 'Họ tên', 'Tài khoản', 'Vai trò']
        const filterVal = ['timestamp', 'Họ tên', 'Tài khoản', 'Vai trò']
        const data = this.formatJson(filterVal)
        excel.export_json_to_excel({
          header: tHeader,
          data,
          filename: 'table-list-user'
        })
        this.downloadLoading = false
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

<template>
  <div class="app-container">
    <div class="filter-container">
      <el-form>
        <el-form-item>
          <el-select
            v-model="listIssueQuery.project_id"
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
            @click="handleShowIssues()"
          >
          </el-button>
        </el-form-item>
      </el-form>
    </div>

    <el-table
      :key="tableKey"
      v-loading="listIssueLoading"
      :data="listIssue"
      border
      fit
      highlight-current-row
      style="width: 100%"
      @sort-change="sortChange"
    >
      <el-table-column label="ID" align="center" width="50">
        <template slot-scope="{ row }">
          <span>{{ row.id }}</span>
        </template>
      </el-table-column>
      <el-table-column label="Tên mục" width="110">
        <template slot-scope="{ row }">
          <span>
            {{ row.name }}
          </span>
        </template>
      </el-table-column>
      <el-table-column label="Danh sách công việc" min-width="850">
        <el-table
          :key="tableKey"
          slot-scope="{ row }"
          v-loading="listIssueLoading"
          :data="row.issue"
          border
          fit
          highlight-current-row
          style="width: 100%"
          @sort-change="sortChange"
        >
          <el-table-column label="ID" align="center" width="50">
            <template slot-scope="{ row }">
              <span>{{ row.id }}</span>
            </template>
          </el-table-column>
          <el-table-column label="Ưu tiên" width="78">
            <template slot-scope="{ row }">
              <el-tag>{{ row.priority }}</el-tag>
            </template>
          </el-table-column>
          <el-table-column label="Tên công việc" min-width="200">
            <template slot-scope="{ row }">
              <span class="link-type" @click="handleUpdate(row)">
                {{ row.issue_name }}
              </span>
            </template>
          </el-table-column>
          <el-table-column label="Người được giao" width="150">
            <template slot-scope="{ row }">
              <span>
                {{ row.users.username }}
              </span>
            </template>
          </el-table-column>
          <el-table-column label="Hạn cuối" width="100">
            <template slot-scope="{ row }">
              <span>
                {{ row.deadline }}
              </span>
            </template>
          </el-table-column>
          <el-table-column label="Trạng thái" width="90">
            <template slot-scope="{ row }">
              <el-tag :type="row.status | statusFilter">
                {{ row.status }}
              </el-tag>
            </template>
          </el-table-column>
          <el-table-column
            label="Thao tác"
            width="160"
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
      <el-table-column
        label="Thao tác"
        width="100"
        class-name="small-padding fixed-width"
      >
        <template slot-scope="{ row }">
          <el-button
            size="mini"
            type="primary"
            icon="el-icon-plus"
            @click="handleCreate(row)"
          >
            Thêm
          </el-button>
        </template>
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
        <el-form-item label="Người được giao">
          <el-select
            v-model="temp.user_id"
            class="filter-item"
            placeholder="Chọn người thực hiện"
            name="user_id"
          >
            <el-option
              v-for="item in listMember"
              :key="item.user_id"
              :label="item.role + ' --- ' + item.name"
              :value="item.user_id"
            />
          </el-select>
        </el-form-item>
        <el-form-item label="Tên công việc" prop="issue_name">
          <el-input v-model="temp.issue_name" name="issue_name" />
        </el-form-item>
        <el-form-item label="Độ ưu tiên" prop="priority">
          <el-input v-model="temp.priority" type="number" name="priority" />
        </el-form-item>
        <el-form-item label="Thời hạn" prop="deadline">
          <el-input v-model="temp.deadline" type="date" name="deadline" />
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false"> Hủy bỏ </el-button>
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
        <el-form-item label="Người được giao">
          <el-select
            v-model="temp.user_id"
            class="filter-item"
            placeholder="Chọn người thực hiện"
            name="user_id"
          >
            <el-option
              v-for="item in listMember"
              :key="item.user_id"
              :label="item.role + ' --- ' + item.name"
              :value="item.user_id"
            />
          </el-select>
        </el-form-item>
        <el-form-item label="Tên công việc" prop="issue_name">
          <el-input v-model="temp.issue_name" name="issue_name" />
        </el-form-item>
        <el-form-item label="Độ ưu tiên" prop="priority">
          <el-input v-model="temp.priority" type="number" name="priority" />
        </el-form-item>
        <el-form-item label="Thời hạn" prop="deadline">
          <el-input v-model="temp.deadline" type="date" name="deadline" />
        </el-form-item>
        <el-form-item label="Trạng thái">
          <el-select
            v-model="temp.status"
            class="filter-item"
            placeholder="Trạng thái công việc"
            name="status"
          >
            <el-option
              v-for="item in calendarRoleOptions"
              :key="item.key"
              :label="item.value"
              :value="item.value"
            />
          </el-select>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false"> Hủy bỏ </el-button>
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
import { getIssues, updateIssue, createIssue, deleteIssue, memberOptions } from '@/api/issue'
import waves from '@/directive/waves' // waves directive
import { parseTime } from '@/utils'
import Pagination from '@/components/Pagination' // secondary package based on el-pagination

const calendarRoleOptions = [
  { key: 'OP', value: 'open' },
  { key: 'IP', value: 'inprogress' },
  { key: 'RS', value: 'resolved' },
  { key: 'CL', value: 'closed' }
]

const calendarRoleKeyValue = calendarRoleOptions.reduce((acc, cur) => {
  acc[cur.key] = cur.value
  return acc
}, {})

export default {
  name: 'Item',
  components: { Pagination },
  directives: { waves },
  filters: {
    statusFilter(status) {
      const statusMap = {
        open: 'info',
        inprogress: 'warning',
        resolved: 'success',
        closed: 'danger'
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
      listMember: null,
      listIssue: null,
      detail: null,
      total: 0,
      listLoading: false,
      detailLoading: true,
      listIssueLoading: false,
      calendarRoleOptions,
      userOptions: null,
      listQuery: {
        project_name: '',
        sort: '+id'
      },
      listIssueQuery: {
        page: 1,
        limit: 10,
        project_id: ''
      },
      temp: {
        id: undefined,
        importance: 1,
        issue_name: '',
        priority: '',
        user_id: '',
        item_id: '',
        status: '',
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
        issue_name: [{ required: true, message: 'Tên công việc không được để trống' }],
        priority: [{ required: true, message: 'Mức độ ưu tiên không được để trống' }],
        user_id: [{ required: true, message: 'Người được giao không được để trống' }],
        deadline: [{ required: true, message: 'Thời hạn không được để trống' }]
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
    handleShowIssues() {
      this.listIssueLoading = false
      getIssues(this.listIssueQuery).then((response) => {
        this.listIssue = response.data
        this.total = response.data.length
        // Just to simulate the time of the request
        setTimeout(() => {
          this.listIssueLoading = false
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
        issue_name: '',
        priority: '',
        user_id: '',
        status: '',
        project_id: ''
      }
    },
    handleCreate(row) {
      memberOptions(this.listIssueQuery.project_id).then((response) => {
        this.listMember = response.data
      })
      this.resetTemp()
      this.temp.item_id = row.id
      this.temp.user_id = this.listMember['0']['user_id']
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
          createIssue(this.temp).then((response) => {
            this.dialogFormVisible = false
            this.$notify({
              title: response.message,
              type: 'success',
              duration: 2000
            })
            this.rules = response.data
          })
          getIssues(this.listIssueQuery).then((response) => {
            this.listIssue = response.data
            this.listIssueLoading = false
          })
        }
      })
    },
    handleUpdate(row) {
      memberOptions(this.listIssueQuery.project_id).then((response) => {
        this.listMember = response.data
      })
      this.temp.item_id = row.id
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
          updateIssue(this.temp.id, tempData).then((response) => {
            this.dialogFormVisible = false
            this.$notify({
              title: response.message,
              type: 'success',
              duration: 2000
            })
          })
          getIssues(this.listIssueQuery).then((response) => {
            this.listIssue = response.data
            this.listIssueLoading = false
          })
        }
      })
    },
    handleDelete(row) {
      deleteIssue(row.id).then((response) => {
        this.$notify({
          title: response.message,
          type: 'success',
          duration: 2000
        })
      })
      getIssues(this.listIssueQuery).then((response) => {
        this.listIssue = response.data
        this.listIssueLoading = false
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

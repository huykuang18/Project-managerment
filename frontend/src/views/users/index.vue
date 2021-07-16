<template>
  <div class="app-container">
    <div class="filter-container">
      <el-input
        v-model="listQuery.name"
        placeholder="Họ tên"
        style="width: 200px"
        class="filter-item"
        @keyup.enter.native="handleFilter"
      />
      <!-- <el-select v-model="listQuery.importance" placeholder="Imp" clearable style="width: 90px" class="filter-item">
        <el-option v-for="item in importanceOptions" :key="item" :label="item" :value="item" />
      </el-select> -->
      <el-select
        v-model="listQuery.role"
        placeholder="Vai trò"
        clearable
        class="filter-item"
        style="width: 130px"
      >
        <el-option
          v-for="item in calendarRoleOptions"
          :key="item.key"
          :label="item.display_name"
          :value="item.display_name"
        />
      </el-select>
      <!-- <el-select v-model="listQuery.sort" style="width: 140px" class="filter-item" @change="handleFilter">
        <el-option v-for="item in sortOptions" :key="item.key" :label="item.label" :value="item.key" />
      </el-select> -->
      <el-button
        v-waves
        class="filter-item"
        type="primary"
        icon="el-icon-search"
        @click="handleFilter"
      >
        Tìm kiếm
      </el-button>
      <el-button
        class="filter-item"
        style="margin-left: 10px"
        type="primary"
        icon="el-icon-edit"
        @click="handleCreate"
      >
        Thêm
      </el-button>
      <el-button
        v-waves
        :loading="downloadLoading"
        class="filter-item"
        type="primary"
        icon="el-icon-download"
        @click="handleDownload"
      >
        Xuất file
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
      <el-table-column label="Tài khoản" width="150px" align="center">
        <template slot-scope="{ row }">
          <span>{{ row.username }}</span>
        </template>
      </el-table-column>
      <el-table-column label="Họ tên" min-width="150px">
        <template slot-scope="{ row }">
          <span>{{ row.name }}</span>
        </template>
      </el-table-column>
      <el-table-column label="Vai trò" class-name="status-col" width="100">
        <template slot-scope="{ row }">
          <el-tag>
            {{ row.role }}
          </el-tag>
        </template>
      </el-table-column>
      <el-table-column
        label="Thao tác"
        align="center"
        width="230"
        class-name="small-padding fixed-width"
      >
        <template slot-scope="{ row, $index }">
          <el-button type="primary" size="mini" @click="handleUpdate(row)">
            Cập nhật
          </el-button>
          <!-- <el-button
            v-if="row.status != 'published'"
            size="mini"
            type="success"
            @click="handleModifyStatus(row, 'published')"
          >
            Publish
          </el-button> -->
          <!-- <el-button
            v-if="row.status != 'draft'"
            size="mini"
            @click="handleModifyStatus(row, 'draft')"
          >
            Draft
          </el-button> -->
          <el-button
            v-if="row.status != 'deleted'"
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

    <el-dialog :title="textMap[dialogStatus]" :visible.sync="dialogFormVisible">
      <el-form
        ref="dataForm"
        :rules="rules"
        :model="temp"
        label-position="left"
        label-width="150px"
        style="width: 400px margin-left: 50px"
      >
        <el-form-item label="Họ tên" prop="name">
          <el-input v-model="temp.name" name="name" />
        </el-form-item>
        <el-form-item label="Tài khoản" prop="username">
          <el-input
            v-if="dialogStatus === 'create'"
            v-model="temp.username"
            name="username"
          />
          <el-input v-else v-model="temp.username" name="username" disabled />
        </el-form-item>
        <el-form-item
          v-if="dialogStatus === 'create'"
          label="Mật khẩu"
          prop="password"
        >
          <el-input v-model="temp.password" type="password" name="password" />
        </el-form-item>
        <el-form-item
          v-if="dialogStatus === 'create'"
          label="Nhập lại mật khẩu"
          prop="repassword"
        >
          <el-input
            v-model="temp.repassword"
            type="password"
            name="repassword"
          />
        </el-form-item>
        <el-form-item label="Vai trò" prop="role">
          <el-select
            v-model="temp.role"
            class="filter-item"
            placeholder="Chọn vai trò"
            name="role"
          >
            <el-option
              v-for="item in calendarRoleOptions"
              :key="item.key"
              :label="item.display_name"
              :value="item.display_name"
            />
          </el-select>
        </el-form-item>
      </el-form>
      <div slot="footer" class="dialog-footer">
        <el-button @click="dialogFormVisible = false"> Cancel </el-button>
        <el-button
          type="primary"
          @click="dialogStatus === 'create' ? createData() : updateData()"
        >
          Xác nhận
        </el-button>
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
import { getUsers, deleteUser, createUser, updateUser } from '@/api/user'
import waves from '@/directive/waves' // waves directive
import { parseTime } from '@/utils'
import Pagination from '@/components/Pagination' // secondary package based on el-pagination

const calendarRoleOptions = [
  { key: 'AD', display_name: 'admin' },
  { key: 'DV', display_name: 'developer' },
  { key: 'TS', display_name: 'tester' },
  { key: 'VS', display_name: 'visitor' }
]

const calendarRoleKeyValue = calendarRoleOptions.reduce((acc, cur) => {
  acc[cur.key] = cur.display_name
  return acc
}, {})

export default {
  name: 'User',
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
      total: 0,
      listLoading: true,
      listQuery: {
        page: 1,
        limit: 10,
        name: undefined,
        role: undefined,
        username: undefined,
        password: undefined,
        sort: '+id'
      },
      importanceOptions: [1, 2, 3],
      calendarRoleOptions,
      sortOptions: [
        { label: 'ID Ascending', key: '+id' },
        { label: 'ID Descending', key: '-id' }
      ],
      statusOptions: ['published', 'draft', 'deleted'],
      showReviewer: false,
      temp: {
        id: undefined,
        importance: 1,
        name: '',
        username: '',
        password: '',
        role: 'visitor'
      },
      dialogFormVisible: false,
      dialogStatus: '',
      textMap: {
        update: 'Chỉnh sửa',
        create: 'Thêm người dùng'
      },
      dialogPvVisible: false,
      pvData: [],
      rules: {
        username: [
          { required: true, message: 'Tên đăng nhập không được để trống' }
        ],
        name: [
          {
            required: true,
            message: 'Họ và tên không được để trống'
          }
        ],
        password: [{ required: true, message: 'Mật khẩu không được để trống' }],
        repassword: [
          { required: true, message: 'Xác nhận mật khẩu chưa đúng' }
        ]
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
      getUsers(this.listQuery).then((response) => {
        this.list = response.data.data
        this.total = response.data.total

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
    handleModifyStatus(row, status) {
      this.$message({
        message: 'Success',
        type: 'success'
      })
      row.status = status
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
        remark: '',
        name: '',
        role: 'visitor',
        username: '',
        password: ''
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
          createUser(this.temp).then((response) => {
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
    // createData() {
    //   this.temp.id = parseInt(Math.random() * 100) + 1024
    //   createUser(this.temp)
    //     .then((response) => {
    //       this.list.unshift(this.temp)
    //       this.dialogFormVisible = false
    //       this.$notify({
    //         title: response.message,
    //         type: 'success',
    //         duration: 2000,
    //       })
    //     })
    //     .catch((error) => {
    //       console.error(error)
    //       this.rules = error.data
    //       this.$refs['dataForm'].validate()
    //     })
    // },
    handleUpdate(row) {
      this.temp = Object.assign({}, row) // copy obj
      this.temp.timestamp = new Date(this.temp.timestamp)
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
          updateUser(this.temp.id, tempData).then((response) => {
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
      deleteUser(row.id).then((response) => {
        console.log(response.message)
        this.$notify({
          title: response.message,
          type: 'success',
          duration: 2000
        })
      })
      this.list.splice(index, 1)
    },
    // handleFetchPv(pv) {
    //   fetchPv(pv).then((response) => {
    //     this.pvData = response.data.pvData
    //     this.dialogPvVisible = true
    //   })
    // },
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

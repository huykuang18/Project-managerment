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
          <span class="link-type" @click="handleInfo(row)">{{
            row.project_name
          }}</span>
        </template>
      </el-table-column>
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
        label="Chi tiết"
        width="100"
        class-name="small-padding fixed-width"
      >
        <template slot-scope="{ row }">
          <el-button
            icon="el-icon-info"
            type="primary"
            size="mini"
            @click="handleInfo(row)"
          >
            Xem
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
        <el-form-item label="Tên dự án">
          <span>{{ temp.project_name }}</span>
        </el-form-item>
        <el-form-item label="Khách hàng">
          <span>{{ temp.customer }}</span>
        </el-form-item>
        <el-form-item label="Ngày bắt đầu">
          <span>{{ temp.date_start }}</span>
        </el-form-item>
        <el-form-item label="Ngày kết thúc">
          <span>{{ temp.date_end }}</span>
        </el-form-item>
        <el-form-item label="Mô tả">
          <el-input
            v-model="temp.description"
            type="textarea"
            name="description"
            disabled
          />
        </el-form-item>
        <el-form-item label="Quản lý dự án">
          <span>{{ temp.manager }}</span>
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
            <el-table-column label="ID" width="100" align="center">
              <template slot-scope="{ row }">
                <span>{{ row.id }}</span>
              </template>
            </el-table-column>
            <el-table-column label="Vai trò" align="center" width="110">
              <template slot-scope="{ row }">
                <el-tag>{{ row.role }}</el-tag>
              </template>
            </el-table-column>
            <el-table-column label="Họ tên" width="170" align="center">
              <template slot-scope="{ row }">
                <span>{{ row.name }}</span>
              </template>
            </el-table-column>
            <el-table-column label="Tài khoản" width="104" align="center">
              <template slot-scope="{ row }">
                <span>{{ row.username }}</span>
              </template>
            </el-table-column>
          </el-table>
        </el-form-item>
      </el-form>
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
import { getProjects, getMembers } from '@/api/project'
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
  name: 'OtherProject',
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
        info: 'Chi tiết'
      },
      dialogPvVisible: false,
      pvData: [],
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
    handleInfo(row) {
      getMembers(row.id).then((response) => {
        this.temp = response.data[0]
        this.detail = response.data[0]['users']
        this.detailLoading = false
        this.temp.manager = this.detail[0]['name']
      })
      this.dialogStatus = 'info'
      this.dialogFormVisible = true
      this.$nextTick(() => {
        this.$refs['dataForm'].clearValidate()
      })
      getUserOptions(row.id).then((response) => {
        this.userOptions = response.data
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

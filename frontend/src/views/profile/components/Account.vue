<template>
  <el-form>
    <el-form-item label="Họ tên">
      <el-input v-model.trim="user.name" />
    </el-form-item>
    <el-form-item label="Tên đăng nhập">
      <el-input v-model.trim="user.username" />
    </el-form-item>
    <el-form-item>
      <el-button type="primary" @click="submit">Cập nhật</el-button>
    </el-form-item>
  </el-form>
</template>

<script>
export default {
  props: {
    user: {
      type: Object,
      default: () => {
        return {
          name: '',
          username: ''
        }
      }
    }
  },
  methods: {
    submit() {
      this.$message({
        message: 'User information has been updated successfully',
        type: 'success',
        duration: 5 * 1000
      })
    }
  }
}
</script>

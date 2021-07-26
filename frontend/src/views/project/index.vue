<template>
  <div class="dashboard-container">
    <component :is="currentRole" />
  </div>
</template>

<script>
import { mapGetters } from 'vuex'
import AdminProject from './admin'
import OtherProject from './other'

export default {
  name: 'Project',
  components: { AdminProject, OtherProject },
  data() {
    return {
      currentRole: 'AdminProject'
    }
  },
  computed: {
    ...mapGetters([
      'roles'
    ])
  },
  created() {
    if (!this.roles.includes('admin')) {
      this.currentRole = 'OtherProject'
    }
  }
}
</script>

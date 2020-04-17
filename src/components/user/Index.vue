<template>
  <el-row class="index">
    <el-col :md="12">
      <el-row>
        <el-col :md="6">ID：</el-col>
        <el-col :md="18">{{ user.id }}</el-col>
      </el-row>
      <el-row>
        <el-col :md="6">用户名：</el-col>
        <el-col :md="18">{{ user.username }}</el-col>
      </el-row>
      <el-row>
        <el-col :md="6">邮箱</el-col>
        <el-col :md="18">{{ user.email }}</el-col>
      </el-row>
      <el-row>
        <el-col :md="6">角色：</el-col>
        <el-col :md="18">{{ user.role }}</el-col>
      </el-row>
    </el-col>
  </el-row>
</template>

<script>
import { mapState } from "vuex";
export default {
  name: "user-index",
  computed: {
    ...mapState("user", ["user"])
  }
};
</script>

<style lang="scss" scoped>
.index {
  .el-row {
    margin-bottom: 0.7rem;
  }
}
</style>

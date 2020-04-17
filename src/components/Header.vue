<template>
  <header class="header">
    <img src="../assets/smxy-logo.png" alt="SMXY-Logo" />
    <el-menu default-active="1" mode="horizontal">
      <el-menu-item index="1">处理中心</el-menu-item>
      <el-submenu index="2">
        <template slot="title">我的工作台</template>
        <el-menu-item index="2-1">选项1</el-menu-item>
        <el-menu-item index="2-2">选项2</el-menu-item>
        <el-menu-item index="2-3">选项3</el-menu-item>
        <el-submenu index="2-4">
          <template slot="title">选项4</template>
          <el-menu-item index="2-4-1">选项1</el-menu-item>
          <el-menu-item index="2-4-2">选项2</el-menu-item>
          <el-menu-item index="2-4-3">选项3</el-menu-item>
        </el-submenu>
      </el-submenu>
    </el-menu>
    <el-dropdown @command="handleCommand">
      <span class="el-dropdown-link">
        <i class="el-icon-user"></i>
        {{ user.username }}
      </span>
      <el-dropdown-menu slot="dropdown">
        <el-dropdown-item command="showInfo">个人信息</el-dropdown-item>
        <el-dropdown-item command="updateInfo">更新信息</el-dropdown-item>
        <el-dropdown-item divided command="logout">登出</el-dropdown-item>
      </el-dropdown-menu>
    </el-dropdown>
  </header>
</template>

<script>
import { mapState, mapActions } from "vuex";
export default {
  name: "ex-header",
  computed: {
    ...mapState("user", ["user"])
  },
  methods: {
    ...mapActions("user", ["logout"]),
    handleCommand(command) {
      if (command === "showInfo") {
        this.$router.push({ path: "/user/index" });
      }
      if (command === "updateInfo") {
        this.$router.push({ path: "/user/update" });
      }
      if (command === "logout") {
        this.logout()
          .then(res => {
            this.logouting = false;
            this.$router.push({ path: "/" });
          })
          .catch(err => {
            this.logouting = false;
            this.$notify.error({
              title: "登出失败",
              message: err.data.error
            });
          });
      }
    }
  }
};
</script>

<style lang="scss" scoped>
.header {
  height: 60px;
  display: flex;
  align-items: center;
  padding: 0 1rem;
  background-color: #ffffff;

  img {
    height: 80%;
    margin-right: 3rem;
  }

  .el-dropdown {
    flex: 1;
    text-align: right;

    .el-dropdown-link {
      cursor: pointer;
    }
  }
}
</style>

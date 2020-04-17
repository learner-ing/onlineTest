<template>
  <div class="user-container">
    <ex-header></ex-header>
    <el-card class="el-container">
      <el-col :md="6" class="left">
        <h4>个人中心</h4>
        <el-menu
          :default-active="path"
          :router="true"
          active-text-color="#ffffff"
        >
          <el-menu-item index="index">
            <i class="el-icon-user"></i>
            <span slot="title">个人信息</span>
          </el-menu-item>
          <el-menu-item index="update">
            <i class="el-icon-edit-outline"></i>
            <span slot="title">更新信息</span>
          </el-menu-item>
        </el-menu>
      </el-col>
      <el-col :md="18" class="right">
        <user-index v-if="path === 'index'"></user-index>
        <user-update v-if="path === 'update'"></user-update>
      </el-col>
    </el-card>
  </div>
</template>

<script>
import Header from "../components/Header";
import UserIndex from "../components/user/Index";
import UserUpdate from "../components/user/Update";
export default {
  name: "user",
  components: {
    "ex-header": Header,
    "user-index": UserIndex,
    "user-update": UserUpdate
  },
  computed: {
    path() {
      return this.$route.params.path || "index";
    }
  },
  methods: {}
};
</script>

<style lang="scss" scoped>
.el-card {
  margin-top: 2rem;
  min-height: 80vh;

  .left {
    border-right: solid 1px #e6e6e6;
    height: 100%;
  }

  .el-menu {
    border: none;

    .el-menu-item {
      padding: 0;
      height: 44px;
      line-height: 44px;

      &.is-active {
        background: #409eff;
      }
    }
  }

  h4 {
    text-align: center;
    margin: 0.7rem 0 1.5rem 0;
  }

  .right {
    padding: 3rem;
    height: 100%;
  }
}
</style>

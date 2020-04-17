<template>
  <el-row class="login-container">
    <el-col :md="12">
      <img src="../assets/smxy-logo.png" alt="SMXY-Logo" />
    </el-col>
    <el-col :md="12">
      <el-form
        ref="loginForm"
        class="login-form el-shadow"
        :model="formData"
        :rules="formRule"
        status-icon
        hide-required-asterisk
        label-position="left"
        label-width="20%"
      >
        <h3 class="title">系统登录</h3>
        <el-form-item prop="email" label="邮箱">
          <el-input type="text" v-model="formData.email" placeholder="邮箱" />
        </el-form-item>
        <el-form-item prop="password" label="密码">
          <el-input
            type="password"
            v-model="formData.password"
            placeholder="密码"
          />
        </el-form-item>
        <el-form-item class="remember-me">
          <el-checkbox v-model="formData.rememberMe">
            记住密码
          </el-checkbox>
        </el-form-item>
        <el-form-item class="submit" label-width="0">
          <el-button type="primary" @click="submit" :loading="logining">
            登录
          </el-button>
        </el-form-item>
      </el-form>
    </el-col>
  </el-row>
</template>

<script>
import { mapActions } from "vuex";
export default {
  name: "login",
  data() {
    return {
      logining: false,
      formData: {
        email: "admin@ixk.me",
        password: "",
        rememberMe: true
      },
      formRule: {
        email: [
          {
            required: true,
            message: "请输入邮箱",
            trigger: "blur"
          },
          {
            type: "email",
            message: "必须是邮箱",
            trigger: "blur"
          }
        ],
        password: [{ required: true, message: "请输入密码", trigger: "blur" }]
      },
      checked: false
    };
  },
  methods: {
    ...mapActions("user", ["login"]),
    async submit(e) {
      this.$refs.loginForm.validate(valid => {
        if (valid) {
          this.logining = true;
          this.login(this.formData)
            .then(res => {
              this.logining = false;
              this.$router.push({ path: this.$route.query.redirect || "/" });
            })
            .catch(err => {
              this.logining = false;
              this.$refs.loginForm.resetFields();
              this.$notify.error({
                title: "登录失败",
                message: err.data.error
              });
            });
        } else {
          console.log("error submit!");
          return false;
        }
      });
    }
  }
};
</script>

<style scoped lang="scss">
.login-container {
  width: 100vw;
  height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  flex-wrap: wrap;
  background-image: url("../assets/smxy-bg.png");
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  padding: 0 10%;

  > .el-col {
    display: flex;
    justify-content: center;
    align-items: center;
  }
}

.title {
  margin-top: 0;
}

.submit {
  width: 100%;

  button {
    width: 100%;
  }
}

.remember-me {
  margin-bottom: 7px;
  margin-top: -15px;
}
</style>

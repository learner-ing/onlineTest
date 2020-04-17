<template>
  <el-row class="register-container">
    <el-col :md="12">
      <img src="../assets/smxy-logo.png" alt="SMXY-Logo" />
    </el-col>
    <el-col :md="12">
      <el-form
        ref="registerForm"
        class="register-form el-shadow"
        :model="formData"
        :rules="formRule"
        status-icon
        hide-required-asterisk
        label-position="left"
        label-width="20%"
      >
        <h3 class="title">系统注册</h3>
        <el-form-item prop="username" label="姓名">
          <el-input
            type="text"
            v-model="formData.username"
            placeholder="姓名"
          />
        </el-form-item>
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
        <el-form-item prop="password_confirm" label="确认密码">
          <el-input
            type="password"
            v-model="formData.password_confirm"
            placeholder="确认密码"
          />
        </el-form-item>
        <el-form-item prop="role" label="角色">
          <el-select v-model="formData.role" placeholder="请选择角色">
            <el-option label="学生" value="student"></el-option>
            <el-option label="老师" value="teacher"></el-option>
          </el-select>
        </el-form-item>
        <el-form-item class="submit" label-width="0">
          <el-button type="primary" @click="submit" :loading="registering">
            注册
          </el-button>
        </el-form-item>
      </el-form>
    </el-col>
  </el-row>
</template>

<script>
import { mapActions } from "vuex";
export default {
  name: "register",
  data() {
    return {
      registering: false,
      formData: {
        username: "",
        email: "",
        password: "",
        password_confirm: "",
        role: "student"
      },
      formRule: {
        username: [
          {
            required: true,
            message: "请输入姓名",
            trigger: "blur"
          }
        ],
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
        password: [
          {
            required: true,
            message: "请输入密码",
            trigger: "blur"
          },
          {
            min: 6,
            max: 20,
            message: "密码长度应在 6-20 之间",
            trigger: "blur"
          }
        ],
        password_confirm: [
          {
            required: true,
            message: "请再次输入密码",
            trigger: "blur"
          },
          {
            validator: (rule, value, callback) => {
              if (value !== this.formData.password) {
                callback(new Error("两次输入密码不一致!"));
              } else {
                callback();
              }
            },
            trigger: "blur"
          }
        ]
      },
      checked: false
    };
  },
  methods: {
    ...mapActions("user", ["register"]),
    submit(e) {
      this.$refs.registerForm.validate(valid => {
        if (valid) {
          this.registering = true;
          this.register(this.formData)
            .then(res => {
              this.$notify.success({
                title: "注册成功",
                message: "注册成功，即将跳转登录页",
                duration: 2000,
                onClose: () => {
                  this.$router.push({ path: "/login" });
                }
              });
            })
            .catch(err => {
              this.$notify.error({
                title: "注册失败",
                message: err.data.error
              });
            });
          this.registering = false;
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
.register-container {
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

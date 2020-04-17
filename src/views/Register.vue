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
            placeholder="确认"
          />
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
export default {
  name: "register",
  data() {
    return {
      registering: false,
      formData: {
        email: "",
        password: "",
        password_confirm: ""
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
    submit(e) {
      this.$refs.registerForm.validate(valid => {
        if (valid) {
          this.registering = true;
          console.log(this.formData);
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

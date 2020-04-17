<template>
  <el-form
    ref="passwordForm"
    class="password-form"
    :model="formData"
    :rules="formRule"
    status-icon
    hide-required-asterisk
    label-position="left"
    label-width="20%"
  >
    <el-form-item prop="old_password" label="旧密码">
      <el-input
        type="password"
        v-model="formData.old_password"
        placeholder="旧密码"
      />
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
    <el-form-item class="submit">
      <el-button type="primary" @click="submit" :loading="updateing">
        更新
      </el-button>
    </el-form-item>
  </el-form>
</template>

<script>
import { mapActions } from "vuex";
export default {
  name: "user-password",
  data() {
    return {
      updateing: false,
      formData: {
        old_password: "",
        password: "",
        password_confirm: ""
      },
      formRule: {
        old_password: [
          {
            required: true,
            message: "请输入旧密码",
            trigger: "blur"
          },
          {
            min: 6,
            max: 20,
            message: "密码长度应在 6-20 之间",
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
    ...mapActions("user", ["changePassword"]),
    submit(e) {
      this.$refs.passwordForm.validate(valid => {
        if (valid) {
          this.updateing = true;
          this.changePassword(this.formData)
            .then(res => {
              this.updateing = false;
              this.$notify.success({
                title: "修改密码成功"
              });
            })
            .catch(err => {
              this.updateing = false;
              this.$notify.error({
                title: "修改密码失败",
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

<style></style>

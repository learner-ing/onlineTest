<template>
  <el-form
    ref="updateForm"
    class="update-form"
    :model="formData"
    :rules="formRule"
    status-icon
    hide-required-asterisk
    label-position="left"
    label-width="20%"
  >
    <el-form-item prop="username" label="姓名">
      <el-input type="text" v-model="formData.username" placeholder="姓名" />
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
  name: "user-update",
  data() {
    return {
      updateing: false,
      formData: {
        username: this.$store.state.user.user.username,
        email: this.$store.state.user.user.email,
        password: ""
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
        ]
      },
      checked: false
    };
  },
  methods: {
    ...mapActions("user", ["update"]),
    submit(e) {
      this.$refs.updateForm.validate(valid => {
        if (valid) {
          this.updateing = true;
          this.update(this.formData)
            .then(res => {
              this.updateing = false;
              this.$notify.success({
                title: "更新成功"
              });
            })
            .catch(err => {
              this.updateing = false;
              this.$notify.error({
                title: "更新失败",
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

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
    <el-form-item prop="password_confirm" label="确认密码">
      <el-input
        type="password"
        v-model="formData.password_confirm"
        placeholder="确认"
      />
    </el-form-item>
    <el-form-item prop="role" label="角色">
      <el-select v-model="formData.role" placeholder="请选择角色">
        <el-option label="学生" value="student"></el-option>
        <el-option label="老师" value="teacher"></el-option>
      </el-select>
    </el-form-item>
    <el-form-item class="submit">
      <el-button type="primary" @click="submit" :loading="updateing">
        更新
      </el-button>
    </el-form-item>
  </el-form>
</template>

<script>
export default {
  name: "user-update",
  data() {
    return {
      updateing: false,
      formData: {
        username: this.$store.state.user.user.username,
        email: this.$store.state.user.user.email,
        password: "",
        password_confirm: "",
        role: this.$store.state.user.user.role
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
    submit(e) {
      this.$refs.updateForm.validate(valid => {
        if (valid) {
          this.updateing = true;
          // TODO: update
          this.updateing = false;
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

<template>
  <div id="app">
    <router-view />
  </div>
</template>

<script>
export default {
  name: "app",
  created() {}
};
</script>

<style lang="scss">
html,
body {
  padding: 0;
  margin: 0;
  background-color: #f8f8f8;
}

.el-shadow {
  border-radius: 5px;
  width: 350px;
  padding: 35px 35px 15px;
  background: #fff;
  border: 1px solid #eaeaea;
  box-shadow: 0 0 25px #cac6c6;
}

.el-container {
  margin: 0 auto;
  padding: 0 10px;
  width: 100%;
  @media (min-width: 576px) {
    max-width: 540px;
  }
  @media (min-width: 768px) {
    max-width: 720px;
  }
  @media (min-width: 992px) {
    max-width: 960px;
  }
  @media (min-width: 1200px) {
    max-width: 1140px;
  }
  @media (min-width: 1560px) {
    max-width: 1400px;
  }

  .el-card__body {
    width: 100%;
  }
}
</style>

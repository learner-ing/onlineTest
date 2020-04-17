<template>
  <div class="about">
    <h1>This is an about page</h1>
    <button @click="logout()">Logout</button>
  </div>
</template>

<script>
import { mapActions } from "vuex";
export default {
  name: "about",
  methods: {
    ...mapActions("user", ["logout"])
  }
};
</script>

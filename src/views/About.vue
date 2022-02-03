<template>
  <div class="about" v-if="authData.isAuth">
    <h1>This is an about page</h1>
  </div>
</template>

<script>
import Vuex from "vuex";
export default {
  name: "About",
  computed: {
    ...Vuex.mapState(["authData"]),
  },
};
</script>
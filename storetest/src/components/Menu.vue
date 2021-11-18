<template>
  <div class="menu">
    <router-link to="/">Home</router-link> |
    <router-link to="/register" v-if="!authData.isAuth">Register | </router-link> 
    <router-link to="/login" v-if="!authData.isAuth">Login | </router-link> 
    <router-link to="/about" v-if="authData.isAuth">About</router-link>
  </div>
</template>

<script>
import Vuex from "vuex";
export default {
  name: "Menu",
  data() {
    return {};
  },
  computed: {
    ...Vuex.mapState(["authData"]),
  },
};
</script>
<template>
  <div class="home">
    <h1 v-if="authData.isAuth" >Welcome back: {{authData.userName}} -> <span v-if="authData.isAdmin"> Admin </span> </h1> 
    <img alt="Vue logo" src="../assets/logo.png">
  </div>
</template>

<script>
import Vuex from "vuex";
export default {
  name: 'Home',
  computed: {
    ...Vuex.mapState(["authData"]),
  },
}
</script>

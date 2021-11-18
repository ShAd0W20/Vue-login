<template>
  <div id="nav">
    <Menu/>
    <router-view />
  </div>
</template>


<script>
import Vuex from 'vuex';
import Menu from "@/components/Menu.vue";
export default {
  components: {
    Menu
  },
  data() {
    return {};
  },
  computed: {
    isAuth() {
      return this.$route.path.match("auth");
    },
    ...Vuex.mapState(["authData"]),
  },
  mounted() {
    this.checkAuth();
  },
  methods: {
    ...Vuex.mapMutations(["set_authData"]),
    checkAuth() {
      var that = this;
      this.DoAjaxCall({ method: "auth" }, function (response) {
        if(response.result == true) {
          console.log(response);
          response.authObj.isAuth = true;
          that.set_authData(response.authObj);
        } else {
          that.set_authData({ isAuth: false, userName: false, isAdmin: false});
        }
      });
    },
  },
};
</script>

<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
}

#nav {
  padding: 30px;
}

#nav a {
  font-weight: bold;
  color: #2c3e50;
}

#nav a.router-link-exact-active {
  color: #42b983;
}
</style>

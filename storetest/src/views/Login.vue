<template>
  <div class="login">
    <h1 class="title">Login</h1>
    <form action class="form" @submit.prevent="doLogin">
      <label class="form-label" for="#username">Username:</label>
      <input
        v-model="userName"
        class="form-input"
        type="text"
        id="username"
        required
        placeholder="Username"
      />
      <label class="form-label" for="#password">Password:</label>
      <input
        v-model="password"
        class="form-input"
        type="password"
        id="password"
        placeholder="Password"
      />
      <input class="form-submit" type="submit" value="Login" />
    </form>
  </div>
</template>

<script>
import Vuex from "vuex";
export default {
  name: "Login",
  data() {
    return {
      userName: "",
      password: "",
      error: false,
    };
  },
  computed: {
    ...Vuex.mapState(["authData"]),
  },
  methods: {
    ...Vuex.mapMutations(["set_authData"]),
    doLogin() {
      var that = this;
      this.DoAjaxCall(
        { method: "login", userName: this.userName, password: this.password },
        function (data) {
          if (data.result == true) {
            data.authObj.isAuth = true;
            that.set_authData(data.authObj);
            that.$router.push("/");
          } else {
            that.set_authData({
              isAuth: false,
              userName: false,
              isAdmin: false,
            });
          }
        }
      );
    },
  },
};
</script>
<template>
  <div class="register">
    <h1 class="title">Sign Up</h1>
    <form action class="form" @submit.prevent="doRegister">
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
      <label class="form-label" for="#password-repeat"
        >Repite la contraeña:</label
      >
      <input
        v-model="passwordRepeat"
        class="form-input"
        type="password"
        id="password-repeat"
        placeholder="Password"
      />
      <input class="form-submit" type="submit" value="Sign Up" />
    </form>
  </div>
</template>

<script>
export default {
  name: "Register",
  data() {
    return {
      userName: "",
      password: "",
      passwordRepeat: "",
      error: false,
    };
  },
  methods: {
    doRegister() {
      var that = this;
      this.DoAjaxCall({method: "register", userName: this.userName, password: this.password}, function(data) {
        if(data.result == true) {
          that.$router.push("/")
        }
      })
    }
  },
};
</script>
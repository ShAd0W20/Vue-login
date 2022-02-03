<template>
  <div class="login">
    <div class="d-flex align-items-center h-100 gradient-custom-3 mt-4">
      <div class="container h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
          <div class="col-12 col-md-9 col-lg-7 col-xl-6">
            <div class="card" style="border-radius: 15px">
              <div class="card-body p-4">
                <h2 class="text-uppercase text-center mb-5 text-dark">
                  Welcome Back
                </h2>

                <form @submit.prevent="doLogin">
                  <div
                    class="alert alert-danger d-flex align-items-center"
                    role="alert"
                    v-if="error"
                  >
                    <b-icon icon="exclamation-triangle-fill"></b-icon>
                    <div class="ms-2">{{ errorCode }}</div>
                  </div>
                  <div class="form-floating mb-4">
                    <input
                      type="text"
                      class="form-control"
                      id="floatingInput"
                      placeholder="username"
                      v-model="userName"
                    />
                    <label for="floatingInput">Username</label>
                  </div>

                  <div class="form-floating mb-4">
                    <input
                      type="password"
                      class="form-control"
                      id="floatingPassword"
                      placeholder="Password"
                      v-model="password"
                    />
                    <label for="floatingPassword">Password</label>
                  </div>

                  <div class="col-12">
                    <div class="d-grid">
                      <button type="submit" class="btn btn-outline-success">
                        Login
                      </button>
                    </div>
                  </div>

                  <p class="text-center text-muted mt-5 mb-0">
                    Have not account yet?
                    <router-link
                      :to="{ name: 'Register' }"
                      class="fw-bold text-body"
                      exact
                    >
                      Signup
                    </router-link>
                  </p>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
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
      errorCode: "",
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
              userID: false,
              userName: false,
              isAdmin: false,
            });
            that.error = true;
            that.errorCode = data.errorCode;
          }
        }
      );
    },
  },
};
</script>
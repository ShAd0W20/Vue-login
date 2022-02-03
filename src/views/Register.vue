<template>
  <div class="register">
    <div class="d-flex align-items-center h-100 gradient-custom-3 mt-4">
      <div class="container h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
          <div class="col-12 col-md-9 col-lg-7 col-xl-6">
            <div class="card" style="border-radius: 15px">
              <div class="card-body p-4">
                <h2 class="text-uppercase text-center mb-5 text-dark">
                  Create an account
                </h2>

                <form @submit.prevent="doRegister">
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

                  <div class="form-floating mb-4">
                    <input
                      type="password"
                      class="form-control"
                      id="floatingPasswordRepeat"
                      placeholder="Repeat password"
                      v-model="passwordRepeat"
                    />
                    <label for="floatingPasswordRepeat">Repeat password</label>
                  </div>

                  <div class="col-12">
                    <div class="d-grid">
                      <button type="submit" class="btn btn-outline-success">
                        Register
                      </button>
                    </div>
                  </div>

                  <p class="text-center text-muted mt-5 mb-0">
                    Have already an account?
                    <router-link :to="{name: 'Login'}" class="fw-bold text-body" exact> Login here </router-link>
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
export default {
  name: "Register",
  data() {
    return {
      userName: "",
      password: "",
      passwordRepeat: "",
      error: false,
      errorCode: "",
    };
  },
  methods: {
    doRegister() {
      var that = this;
      this.DoAjaxCall(
        {
          method: "register",
          userName: this.userName,
          password: this.password,
          repeatPassword: this.passwordRepeat,
        },
        function (data) {
          if (data.result == true) {
            that.$router.push("/login");
          } else {
            (that.error = true), (that.errorCode = data.errorCode);
          }
        }
      );
    },
  },
};
</script>
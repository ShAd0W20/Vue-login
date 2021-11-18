import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import axios from 'axios';
import VueAxios from 'vue-axios';
import $ from 'jquery';
import Vuex from "vuex";

const app = createApp(App).use(store).use(router).use(VueAxios, axios).use(Vuex);

app.mixin({
    methods: {
        DoAjaxCall: function (data, cb) {
            $.ajax({
                type: "POST",
                url: 'http://localhost/VueStoreTest/storetest/src/php/ajax.php',
                data: data,
                success: function (data) {
                    cb(JSON.parse(data));
                }
            })
        },
        isAdmin: function (authData) {
            if (authData.isAuth) {
                if (this.hasAdminPerms(authData)) {
                    return true;
                }
            }
            return false;
        },
        hasAdminPerms: function(authData)
        {
          return authData.isAuth && authData.isAdmin;
        },
    },
})

app.mount("#app");

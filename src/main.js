import Vue from "vue";
import App from "./App.vue";
import "./registerServiceWorker";
import vuetify from "./plugins/vuetify";
import axios from "axios";
import VueAxios from "vue-axios";
import VueRouter from "vue-router";
import Routes from "./routes";
import VueNoty from "vuejs-noty";

Vue.use(VueAxios, axios);
Vue.use(VueRouter);
Vue.use(VueNoty);
Vue.config.productionTip = false;

const router = new VueRouter({
  routes: Routes,
  mode: "history"
});

new Vue({
  vuetify,
  render: h => h(App),
  router: router
}).$mount("#app");

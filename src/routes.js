import Home from "./components/Home.vue";
import Search from "./components/Search.vue";
import Upload from "./components/Upload.vue";
import Login from "./components/Login.vue";
import Signup from "./components/Signup.vue";
import Read from "./components/Read.vue";

export default [
  { path: "/", component: Home },
  { path: "/login", component: Login },
  { path: "/signup", component: Signup },
  {
    path: "/search",
    component: Search,
    // props: route => ({ query: route.query.q })
  },
  { path: "/upload", component: Upload },
  { path: "/read/:id", component: Read },
];

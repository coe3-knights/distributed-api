<template>
  <span>
    <v-toolbar
      app     
      color="brown lighten-3"
    >
      <router-link  to="/"
        ><v-toolbar-title>DigiLib</v-toolbar-title></router-link
      >
      <v-spacer></v-spacer>
      <v-icon @click="showSearchBar = !showSearchBar"
        >mdi-magnify</v-icon
      >
      <div id="searchbar">
        <v-text-field
          dense
          solo
          clearable
          autofocus
          placeholder="Search"
          v-show="showSearchBar"
          v-model="query"
          @keypress.enter="search"
        ></v-text-field>
      </div>
      <div class="text-center">
        <v-menu open-on-hover top offset-y>
          <template v-slot:activator="{ on }">
            <v-btn
              color="#4e4e4eda"
              dark
              v-on="on"
              text
            >
              <v-icon>mdi-account-circle</v-icon>
            </v-btn>
          </template>

          <v-list>
            <v-list-item
              v-for="(item, index) in items"
              :key="index"
              @click="item.method"
            >
              <v-list-item-title>{{ item.title }}</v-list-item-title>
            </v-list-item>
          </v-list>
        </v-menu>
      </div>
    </v-toolbar>
  </span>
</template>
  
<script>
export default {
  name: "AppNavigation",
  props: {
    searchResults: null,
  },
  data: () => {
    return {
      query: "",
      showSearchBar: false,
    };
  },
  methods: {
    search() {
      this.$http
        .get(`https://knights-library.herokuapp.com/library/search/${this.query}`) //api goes here
        .then(res => {
          this.searchResults = res.data.slice(0,10);
          // eslint-disable-next-line no-console
          // console.log(this.searchResults);
          this.$emit('update-results', this.searchResults)
        });
    },
    goToLogin() {
      this.$router.push('login');
    },
    goToSignup() {
      this.$router.push('signup');
    },
    goToUpload() {
      this.$router.push('upload');
    },
    logout() {
      this.$root.auth = null;
      localStorage.setItem('auth', null);
      alert("You've been logged out!");
    }
  },
  computed: {
    items() {
      if (localStorage.getItem('auth')) {
        return [
          { title: "upload", method: this.goToUpload },
          { title: "logout", method: this.logout }
        ]
      }
      return [
        { title: "login", method: this.goToLogin },
        { title: "sign up", method: this.goToSignup }
      ]
    }
  },
};
</script>

<style lang="scss" scoped>
div #searchbar {
  justify-content: center;
  margin-block-start: 25px;
}
.v-application a {
  color: #4e4e4eda;
  text-decoration: none;
}
</style>

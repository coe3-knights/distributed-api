<template>
  <div class="home-hero">
    <v-container fluid>
      <v-layout
        justify-center
        align-center
        align-content-center
        align-content-space-between
        column
        pa-10
      >
        <div class="display-4 font-weight-black blue--text text-xs-center">
          <span class="red--text">HEALTHY</span> BOOKS
        </div>
        <div
          class="display-4 font-weight-black white--text text-xs-center mb-3"
        >
          FOR YOUR MIND
        </div>
        <div class="display-1 font-weight-normal white--text text-xs-center">
          Finally be a bookie at home with millions of content rich books
          literally a touch away.
        </div>
        <br />
        <v-spacer></v-spacer>
        <br />
        <div>
          <!-- <v-text-field class="display-3"  autofocus id="searchbar" height="100"
          rounded full-width placeholder="Search" solo v-model="query"
          hint="Search book title or author" clearable></v-text-field> -->
          <div
            data-v-8dc7cce2=""
            class="v-input display-3 theme--light v-text-field v-text-field--full-width v-text-field--single-line v-text-field--solo v-text-field--is-booted v-text-field--enclosed v-text-field--placeholder v-text-clearable v-text-field--rounded"
          >
            <div class="v-input__control">
              <div class="v-input__slot" style="height: 100px;">
                <div class="v-text-field__slot">
                  <input
                    autofocus="autofocus"
                    id="searchbar"
                    placeholder="Search"
                    type="text"
                    v-model="query"
                    @keypress.enter="search"
                  />
                </div>
              </div>
              <div class="v-text-field__details">
                <div class="v-messages theme--light">
                  <div class="v-messages__wrapper"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="button-div">
            <v-btn
              color="#070416"
              x-large
              dark
              @click="search"
              :disabled="disable"
              >Search</v-btn
            >
          </div>
        </div>
      </v-layout>
    </v-container>
  </div>
</template>

<script>
// import SearchView from "./Search"
// import NavBar from "./AppNavigation"
export default {
  name: "Dashboard",
  // components: {
  //   "search-view": SearchView,
  //   // "nav-bar": NavBar
  // },
  data: () => {
    return {
      query: "",
      renderComponents: false,
    };
  },
  methods: {
    search() {
      window.location.assign(`/search?q=${this.query}`);
      // this.searchResults = null;
      // this.$http
      //   .get("http://localhost:8088/search") //api goes here
      //   .then(res => {
      //     this.searchResults = res.body;
      // });
    },
  },
  computed: {
    disable: function() {
      if (this.query !== "") return false;
      return true;
    }
  }
};
</script>

<style lang="scss" scoped>
.home-hero {
  background: url("https://library.unc.edu/wp-content/uploads/2013/05/wilsonreading3.jpg");
  height: 100%;
  width: 100%;
}
.v-input input {
  max-height: min-content;
}
.v-text-field.v-text-field--enclosed {
  margin: 0;
  padding: 0;
}
.v-text-field--filled.v-text-field--single-line input,
.v-text-field--full-width.v-text-field--single-line input {
  margin-top: 0px;
}
.button-div {
  padding-inline-start: 40%;
}
</style>

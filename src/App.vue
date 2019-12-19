<template>
  <v-app>
    <v-content class="dark-primary-color" transition="slide-x-transition">
      <router-view></router-view>
    </v-content>
  </v-app>
</template>

<script>
export default {
  name: "App",
  data: () => {
    return {};
  },
  mounted(){
    this.$root.csrf = "ow7tcqweq98cq+w4a9w0e-vnbbq+øbqv+qwevr8q€tuccdsaæjlfæjvcgnncbg"
  }
};
</script>

<style lang="scss">
.dark-primary-color {
  background: url("https://library.unc.edu/wp-content/uploads/2013/05/wilsonreading3.jpg");
}
</style>

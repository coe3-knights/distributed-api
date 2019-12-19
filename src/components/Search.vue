<template>
  <div class="search-hero">
    <nav-bar :searchResults="searchResults" @update-results="updateResults"></nav-bar>
    <v-container fluid>
          <v-row class="container-row">
              <v-col cols="auto" v-for="(book, index) in paginatedResults" :key="index">
                <v-hover v-slot:default="{ hover }">
                <v-card class="booksize" @click="$router.push(`read/:${book.id}/${book.title}`)" :id="index" :elevation="hover ? 12 : 2">
                  <v-img src="../assets/pdf_image.png"></v-img>
                  <v-card-title> {{book.title}} </v-card-title>
                  <v-card-text> {{book.author}} </v-card-text>
                  <v-card-text v-show="hover ? true : false"> {{book.description}} </v-card-text>
                </v-card>
                </v-hover>
              </v-col>
            <v-spacer></v-spacer>
          </v-row>
    </v-container>
    <v-pagination circle :length="totalPages" v-model="currentPage"
      total-visible="10" color="success"></v-pagination>
  </div>
</template>
<script>
import AppNavigation from "./AppNavigation";
const pdfjsLib = require("pdfjs-dist");


export default {
  name: "Search-View",
  components: {
    "nav-bar": AppNavigation
  },
  props:{
    query: {
      type: String
    }
  },
  data() {
    return {
      searchResults: null,
      pageLength: 16,
      currentPage: 1,
      descriptionId: ""
    }
  },
  methods: {
    updateResults(newResults) {
      this.searchResults = newResults;
    },
    showDescription(id) {
      let card = document.getElementById(id).child[3];
      // eslint-disable-next-line no-console
        console.log(card);
      card.style.display = 'flex'
    },
    renderFilePage() {
      //console.log(this.data);
      let fileblob = new Blob([this.form.file], { type: "application/pdf" });
      pdfjsLib.getDocument(window.URL.createObjectURL(fileblob)).then(pdf =>
        pdf.getPage(1).then(page => {
          //let desiredWidth = 100;
          let viewport = page.getViewport({ scale: 0.25 });
          // let scale = desiredWidth / viewport.width;
          // let scaledViewport = page.getViewport({ scale: scale });

          let canvas = document.getElementById("file-canvas");
          let context = canvas.getContext("2d");
          // canvas.height = scaledViewport.height;
          // canvas.width = scaledViewport.width;

          canvas.height = viewport.height;
          canvas.width = viewport.width;

          let renderContext = {
            canvasContext: context,
            viewport: viewport
          };
          page.render(renderContext);
        })
      );
    }
  },
  created() {
    this.query = this.$router.query;
    this.searchResults = null;
    this.$http
      .get(`https://knights-library.herokuapp.com/library/search/${this.query}`) //api goes here
      .then(res => {
        this.searchResults = res.data;
        // eslint-disable-next-line no-console
        console.log(this.searchResults);
      });
  },
  computed: {
    totalPages() {
      return this.searchResults.length / this.pageLength;
    },
    paginatedResults() {
      let start = this.pageLength*(this.currentPage-1);
      let end = this.pageLength* this.currentPage;

      return this.searchResults.slice(start, end);
    }

  }
};
</script>
<style lang="scss" scoped>
.search-hero{
  background: #fff9c4!important;
  width: 100%;
  height: 100%;
}
 .booksize{
   width: 250px;
 }
 .container-row{
   margin-inline-start: 20px;
 }
</style>

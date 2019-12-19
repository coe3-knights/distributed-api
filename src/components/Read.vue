<template>
  <div class="read-hero">
    <v-toolbar color="#0b0b2c" flat dark>
      <router-link to="/"><v-toolbar-title>DigiLib</v-toolbar-title></router-link>
      <v-spacer></v-spacer>
      <v-toolbar-title>{{ bookTitle }}</v-toolbar-title>
      <v-spacer></v-spacer>
      <v-toolbar-title
        >Page {{ pageNumber }} of {{ pageCount }}</v-toolbar-title
      >
      <v-spacer></v-spacer>
    </v-toolbar>
    <container class="reader" fluid>
      <v-layout justify-center justify-space-between justify-space-around>
        <v-btn
          class="previous"
          rounded
          large
          dark
          color="#070416"
          @click="renderPreviousPage"
          :disabled="disablePrev"
          ><v-icon>mdi-chevron-left</v-icon></v-btn
        >
        <canvas id="reading-canvas"></canvas>
        <v-btn
          class="previous"
          rounded
          large
          dark
          color="#070416"
          @click="renderNextPage"
          :disabled="disableNext"
          ><v-icon>mdi-chevron-right</v-icon></v-btn
        >
      </v-layout>
    </container>
  </div>
</template>

<script>
// import sample from "../sample";
const pdfjsLib = require("pdfjs-dist");

// pdfjsLib.GlobalWorkerOptions.workerSrc = '//mozilla.github.io/pdf.js/build/pdf.worker.js';

export default {
  name: "Reader",
  data: () => {
    return {
      book: null,
      pageNumber: 1,
      pageRendering: false,
      pendingPageNum: null,
      pageCount: null,
      pdfData: null,
      bookTitle: null
    };
  },
  computed: {
    // disable the next button once end of book is reached
    disablePrev() {
      if (this.pageNumber == 1) return true;
      return false;
    },

    // disable the previous button at the book's start page
    disableNext() {
      if (this.pageNumber === this.pageCount) return true;
      return false;
    }
  },
  methods: {
    renderPage(pageNumber) { // takes a page number and renders it in the dispaly canvas
      this.pageRendering = true;
      this.book.getPage(pageNumber).then(page => {
        let viewport = page.getViewport({ scale: 1.5 });
        let canvas = document.getElementById("reading-canvas");
        canvas.width = viewport.width;
        canvas.height = viewport.height;
        let context = canvas.getContext("2d");

        let renderTask = page.render({
          canvasContext: context,
          viewport: viewport
        });

        renderTask.promise.then(() => {
          this.pageRendering = false;
          if (this.pendingPageNum !== null) { // render pages in the render queue
            this.renderPage(this.pendingPageNum);
            this.pendingPageNum = null;
          }
        });
      });
    },

    // If another page rendering in progress, waits until the rendering is
    // finised. Otherwise, executes rendering immediately.
    queueRenderPage(pageNumber) {
      if (this.pageRendering) this.pendingPageNum = pageNumber;
      else this.renderPage(pageNumber);
    },

    // display previous page
    renderPreviousPage() {
      if (this.pageNumber <= 1) return;
      this.pageNumber--;
      this.queueRenderPage(this.pageNumber);
    },

    //display next page
    renderNextPage() {
      if (this.pageNumber >= this.book.numPages) return;
      this.pageNumber++;
      this.queueRenderPage(this.pageNumber);
    }
  },
  // fetch book to be read before page is rendered
  beforeMount() {
    let book = this.$router.params
    this.$http.get(`https://knights-library.herokuapp.com/library/books/${book}`)
      .then(res => { 
        let response = res;
        let file = response.data;
        this.pdfData = atob(file); // get pdf from received base64 string
        this.bookTitle = response.title;
      });
  },
  // redender the books first page [coverpage] right after the web page skeleton renders
  mounted() { 
    pdfjsLib.getDocument({data: this.pdfData}).then(pdf => {
      this.book = pdf;
      this.pageCount = pdf.numPages;
      this.renderPage(this.pageNumber);
    });
  }
};
</script>

<style lang="scss" scoped>
#reading-canvas {
  color: inherit;
  min-width: 50%;
}
.read-hero {
  background-color: #0b0b2c;
  height: 100%;
  width: 100%;
}
.reader {
  margin-block-start: 10px;
  margin-block-end: 20px;
  display: block;
}
.previous,
.next {
  margin-block-start: 35%;
}
.v-application a {
  text-decoration: none;
  color: #ffffff;
}
</style>

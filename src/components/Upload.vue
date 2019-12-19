<template>
  <div class="form-hero">
    <v-container fluid>
      <v-form v-model="isValid" v-if="!uploadSuccess">
        <v-flex>
          <v-card id="form-container" class="yellow lighten-4">
            <v-row justify="center">
              <h1>Add your book to our collection!😀</h1>
            </v-row>
            <v-row>
              <v-col cols="8" md="8" sm="4">
                <v-card-title>Fill thís form</v-card-title>
                <v-text-field required hidden v-show="false" v-model="csrf"  :value="$root.csrf"></v-text-field>
                <v-text-field
                  label="Title"
                  v-model="form.title"
                  clearable
                  prepend-icon="mdi-book-open-page-variant"
                  required
                  :rules="field_rule"
                >
                </v-text-field>

                <v-text-field
                  shaped
                  label="Author"
                  v-model="form.author"
                  prepend-icon="mdi-account-tie"
                  clearable
                  required
                  :rules="field_rule"
                >
                </v-text-field>

                <v-text-field
                  v-model="form.tags"
                  label="Tags"
                  prepend-icon="mdi-tag-plus"
                  clearable
                >
                </v-text-field>

                <v-textarea
                  v-model="form.description"
                  label="Description"
                  prepend-icon="mdi-information"
                  clearable
                >
                </v-textarea>
              </v-col>
              <v-col cols="4">
                <v-row justify="center">
                  <v-img
                    v-if="!form.file"
                    src="../assets/upload-2.png"
                    transparent
                    class="mx-auto"
                    width="100"
                    max-width="230"
                    max-height="400"
                  ></v-img>
                  <canvas v-else id="file-canvas"></canvas>
                </v-row>
                <v-row justify="center">
                  <v-file-input
                    v-model="form.file"
                    required
                    shaped
                    label="File"
                    :rules="field_rule"
                    @change="renderFilePage"
                  ></v-file-input>
                  <!-- <v-spacer></v-spacer> -->
                </v-row>
                <v-row justify="center">
                  <v-card-actions
                    ><v-btn
                      large
                      :dark="color"
                      color="#4e4e4eda"
                      min-width="120"
                      id="upload-btn"
                      elevation="8"
                      @click="submit"
                      :disabled="!isValid"
                    >
                      upload
                      <v-icon>mdi-cloud-upload</v-icon>
                    </v-btn></v-card-actions
                  >
                </v-row>
              </v-col>
            </v-row>
          </v-card>
        </v-flex>
      </v-form>
      <v-card v-else>
        <v-card-title class="display-3">Upload Success!</v-card-title>
        <v-card-subtitle
          >Your book has been added to our collection.</v-card-subtitle
        >
        <v-card-action
          ><v-btn class="success-btn" color="#070416" dark
            >ok</v-btn
          ></v-card-action
        >
      </v-card>
    </v-container>
  </div>
</template>

<script>
//const pdfjsLib = require("pdfjs-dist");

export default {
  name: "Upload",
  data: () => {
    return {
      form: {
        csrf: "",
        title: "",
        author: "",
        tags: "",
        description: "",
        file: null
      },
      field_rule: [v => !!v || "Item is required"],
      isValid: true,
      response: null,
      uploadSuccess: false
    };
  },
  computed: {
    color: function() {
      if (!this.isValid) return false;
      return true;
    }
  },
  methods: {
    submit() {
      let formdata = new FormData();
      for (let item in this.form) {
        formdata.append(`${item}`, this.form[item]);
      }
      this.$http
        .post("https://knights-library.herokuapp.com/library/upload", formdata, {
          headers: {
            token: this.$root.auth,
            "Content-Type": "multipart/form-data"
          }
        })
        .then(resp => {
          this.response = resp.data;
          this.uploadSuccess = false;
        })
        .catch(() =>
          alert(
            "Your upload could cont be completed at this time. Please try again later"
          )
        );
    },

    /*------------version 2.0 🤣 -----------------------------------------------------
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
    ---------------------------------------------------------------------------------------*/
  }
};
</script>

<style lang="scss" scoped>
* {
  padding: 5%;
  padding-bottom: 2%;
  padding-top: 1%;
  margin-top: 0;
}
#form-container {
  padding-inline-start: 0%;
  margin-block-start: 0%;
}
.form-hero {
  background: url("https://library.unc.edu/wp-content/uploads/2013/05/wilsonreading3.jpg");
  width: 100%;
  height: 100%;
}
.success-btn {
  float: right;
}
</style>

<template>
  <v-container>
    <v-card class="mx-auto login-hero" color="yellow lighten-4" raised>
      <v-form v-model="isvalid">
        <v-card-title>Login</v-card-title>
        <v-divider></v-divider>
        <v-spacer></v-spacer>
        <v-text-field required v-show="false" hidden v-model="csrf"  :value="$root.csrf"></v-text-field>
        <v-card-text class="login-error" v-show="loginFailed"> {{errors}} </v-card-text>
        <v-text-field outlined shaped label="Username" required autofocus v-model="username" ></v-text-field>
        <v-text-field outlined shaped label="Password" required :append-icon="hidePassword ? 'mdi-eye' : 'mdi-eye-off'" :rules="fieldRules"
        @click:append="() => (hidePassword = !hidePassword)" :type="hidePassword ? 'password' : 'text'" v-model="password"></v-text-field>
        <v-card-action><v-btn :disabled="!isvalid" class="button" @click="login" :dark="!color" color="#4e4e4eda"> Log in </v-btn></v-card-action>
        <v-spacer></v-spacer>
        <v-card-text>Don't have an account? <router-link to="/signup">Sign up</router-link></v-card-text>
        <v-spacer></v-spacer>
      </v-form>
    </v-card>
  </v-container>
</template>

<script>
export default {
    name: "Login",
    data: () => {
        return {
            csrf: "",
            username: "",
            password: "",
            errors: "This credentials do not match our records",
            signUp: true,
            isvalid: true,
            hidePassword: true,
            loginFailed: false,
            fieldRules: [value => !!value || ""],
        }
    },
    computed: {
        color: function() {
            if (!this.isValid) return false;
            return true;
        }
    },
    methods: {
      login() {
          this.$http.post('https://knights-library.herokuapp.com/register/login', {
              username: this.username,
              password: this.password,
              // csrf: this.csrf
          })
          .then(res => {
                const data = res.data;
                //eslint-disable-next-line no-console
                console.log(data);
                localStorage.setItem('auth',JSON.stringify(data));
                this.$root.auth = data.token;
            
                alert("You are welcome");

                this.$router.push('/');

          })
          .catch(err => {
                if(err.status === 401){
                   this.loginFailed = true;
                }
                else{
                    alert("Oops, something went wrong!");
                }
          })
      }
  }
};
</script>

<style>
.login-hero{
  max-width: 50%;
  padding-inline-start: 5%;
  padding-inline-end: 5%;
  min-height: min-content;
  margin-block-start: 10%;
}
.v-card:not(.v-sheet--tile):not(.v-card--shaped) {
  border-radius: 25px;
}
.v-divider{
  max-height: 20px;
}
.spacer{
  height: 18px;
}
.login-error{
    color:rgb(49, 10, 3);
    font-weight: bold;
}
.button{
    position: relative;
    margin-left: 35%;
}
</style>

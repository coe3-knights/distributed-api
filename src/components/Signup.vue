<template>
  <v-container>
    <v-card class="mx-auto login-hero" color="yellow lighten-4" raised>
      <v-form v-model="isvalid">
        <v-card-title>Sign Up</v-card-title>
        <v-divider></v-divider>
        <v-spacer></v-spacer>
        <v-text-field required v-show="false" hidden v-model="csrf"  :value="$root.csrf"></v-text-field>
        <v-text-field outlined shaped label="Firstname*" required autofocus 
         v-model="firstname" :rules="fieldRules"></v-text-field>
        <v-text-field outlined shaped label="Lastname*" required 
         v-model="lastname" :rules="fieldRules"></v-text-field>
        <v-text-field outlined shaped label="Username*" required 
         v-model="username" :rules="fieldRules"></v-text-field>   
        <v-text-field outlined shaped label="Email*" type="email" required v-model="email" :rules="emailRules"></v-text-field>
        <v-text-field outlined shaped label="Password*" required :append-icon="hidePassword ? 'mdi-eye' : 'mdi-eye-off'"
        @click:append="() => (hidePassword = !hidePassword)" :type="hidePassword ? 'password' : 'text'" v-model="password"
        :rules="passwordRules"></v-text-field>
        <v-text-field outlined shaped label="Confirm Password*" required :append-icon="hidePassword ? 'mdi-eye' : 'mdi-eye-off'"
        @click:append="() => (hidePassword = !hidePassword)" :type="hidePassword ? 'password' : 'text'"  
        v-model="password2" :rules="[fieldRules, assertPassword]"></v-text-field>
        <v-card-action><v-btn :disabled="!isvalid" class="button" @click="signUp" :dark="!color" color="#4e4e4eda"> sign up </v-btn></v-card-action>
        <v-spacer></v-spacer>
        <v-card-text>Already have an account? <router-link to="/login">Log in</router-link></v-card-text>
        <v-spacer></v-spacer>
      </v-form>
    </v-card>
  </v-container>
</template>

<script>
export default {
  name: "SignUp",
  data: () => {
    return {
      csrf: "",
      email: "",
      password: "",
      password2: "",
      firstname: "",
      lastname: "",
      username: "",
      isvalid: true,
      hidePassword: true,
      fieldRules: [value => !!value || "Required"],
      passwordRules: [
        value => !!value || "Required",
        // value => {
        //   const pattern = /^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[!@#$%^&*])(?=.{8,})/;
        //   return (
        //     pattern.test(value) ||
        //     "Min. 8 characters with at least one capital letter, a number and a special character."
        //   );
        // }
      ],
      emailRules: [
        value => !!value || 'Required',
        value => /.+@.+\..+/.test(value) || 'E-mail must be valid',
      ],
    };
  },
  computed: {
    assertPassword() {
      return (
        this.password2 === this.password || 'Should be the same as password'
      );
    },
    color: function() {
      if (!this.isValid) return false;
      return true;
    }
  },
  methods: {
    signUp() {
      this.$http.post('https://knights-library.herokuapp.com/register/register', {
          email: this.email,
          password: this.password,
          fname: this.firstname,
          lname: this.lastname,
          username: this.username,
          //csrf: this.csrf
      })
      .then(()=>{
        this.$noty.success("You have been registed! vamos🦾");
        this.$router.push('login');
      })
      .catch(err=>{
        this.$noty.error('Oops, it seems something went wrong. Try again later');
        //eslint-disable-next-line no-console
        console.log(err)
      });
    },
  },
};
</script>

<style>
.login-hero{
  max-width: 50%;
  padding-inline-start: 5%;
  padding-inline-end: 5%;
  min-height: min-content;
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
.button{
    position: relative;
    margin-left: 35%;
}
</style>

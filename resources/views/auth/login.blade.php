@extends('master')

@section('style')
<style type="text/css">
     
 </style>
@stop

@section('body')
 
  <v-app id="inspire" class="center">
    <v-form v-model="valid" ref="form" action="{{ url('/login') }}" method="post" lazy-validation>
        {{ csrf_field() }}
    <v-text-field
      label="Email"
      v-model="email"
      :rules="emailRules"
      name="email"
      required
    ></v-text-field>
    <v-text-field
      label="Password"
      v-model="password"
      name="password"
      :rules="passwordRules"
      :counter="8"
      type="password"
      required
    ></v-text-field>
    
    <v-checkbox
      label="I agree Terms and Conditions!."
      v-model="checkbox"
      :rules="[(v) => !!v || 'You must agree to continue!']"
      required
    ></v-checkbox>

    <v-btn @click="submit" :class="{ green: valid, red: !valid }">login</v-btn>
    <v-btn @click="clear">clear</v-btn>
    <a class="btn btn-link" href="{{ url('/password/reset') }}">Forgot Your Password?</a>
  </v-form>
  </v-app>

@stop

@section('script')
 <script>
   new Vue({
  el: '#app',
  data () {
      return {
        valid: false,
        password: '',
        passwordRules: [
          (v) => !!v || 'Password is required',
          (v) => v && v.length >= 8 || 'Password must be more than 8 characters'
        ],
        email: '',
        emailRules: [
          (v) => !!v || 'E-mail is required',
          (v) => /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(v) || 'E-mail must be valid'
        ],
        select: null,
        items: [
          'Item 1',
          'Item 2',
          'Item 3',
          'Item 4'
        ],
        checkbox: false
      }
    },
    methods: {
      submit () {
        if (this.$refs.form.validate()) {
          this.$refs.form.$el.submit()
        }
      },
      clear () {
        this.$refs.form.reset()
      }
    }
})
 </script>
@stop
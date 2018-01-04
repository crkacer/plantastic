@extends('master')

@section('style')
    <style type="text/css">
        a {
            text-decoration:none;
        }
        #title{
            color:red;
        }
        #app{
            background-color:#f9fbff;
        }
        .fade-enter-active, .fade-leave-active {
            transition: opacity .5s
        }
        .fade-enter, .fade-leave-to /* .fade-leave-active below version 2.1.8 */ {
            opacity: 0
        }
    </style>
@stop

@section('body')
    <v-container class="mt-5 pt-5">
        <v-layout align-center justify-center column>
            <v-flex xs12 class="text-xs-center">
                <h4 style="font-family: 'Merriweather', serif;" >Welcome back</h4>
                <p style="font-family: 'Alegreya', serif; font-size:1.25em;">Please enter your email and password to log in.</p>
            </v-flex>
            <v-flex xs12>
                <transition name="fade" appear>
                    <v-alert color="error" icon="warning" v-model="isError">
                        Password is incorrect.
                    </v-alert>
                </transition>
                <v-form v-model="valid" ref="form" action="{{ url('/login') }}" method="post">
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
                            @focus ="isError = false"
                            :rules="passwordRules"
                            counter="8"
                            :append-icon="e1 ? 'visibility' : 'visibility_off'"
                            :append-icon-cb="() => (e1 = !e1)"
                            :type="e1 ? 'password' : 'text'"
                            required
                    ></v-text-field>

                    <v-checkbox
                            label="Remember Me"
                            v-model="checkbox"
                            name="rememberme"
                    ></v-checkbox>

                    <v-btn round @click="FormSubmit" :class="{ green: valid, red: !valid }">Login</v-btn>
                    <v-btn round @click="clear">clear</v-btn>
                    <a class="btn btn-link transparent" href="{{ url('/password/reset') }}">Forgot Your Password?</a>
                </v-form>
            </v-flex>
            <v-flex xs12 class="text-xs-center">
                <a class="btn btn-link transparent" href="/register" style="text-decoration:underline;">Don't have an account? Register now.</a>
            </v-flex>
        </v-layout>
    </v-container>
@stop

@section('script')
    <script>
    var error = <?php echo $error; ?>;
        var vm = new Vue({
            el: '#app',
            data: {
                //vue-model for alert box
                isError:false,
                //vue-model for displaying passwords or hidden
                e1:true,
                //vue-model for v-form 
                valid: false,
                //vue-model for password input
                password: '',
                //vue-model for rules attribute of password input
                passwordRules: [
                    (v) => !!v || 'Password is required',
                    (v) => v && v.length >= 8 || 'Minimum 8 characters'
                ],
                //vue-model for email input
                email: '',
                //vue-model for rules attribute in email input box
                emailRules: [
                    (v) => !!v || 'E-mail is required',
                    (v) => /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(v) || 'E-mail must be valid'
                ],
                select: null,
                checkbox: false
        },
        methods: {
            FormSubmit () {
                if (this.$refs.form.validate()) {
                    this.$refs.form.$el.submit()
                }
            },
            clear () {
                this.valid=false
                this.$refs.form.reset()
            }
        },
        
        mounted: function(){
            if(error != "correct"){
                this.isError = true
            }
        }
        })
    </script>
@stop
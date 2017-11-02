@extends('master')

@section('style')
    <style type="text/css">
        a {
            text-decoration:none;
        }
        #title{
            color:red;
        }
        #background{
            background-color:white;
        }
    </style>
@stop

@section('body')
    <v-container class="mt-5">
        <v-layout align-center justify-center column>
            <v-flex xs12 class="text-xs-center">
                <h4 style="font-family: 'Merriweather', serif;" >Welcome</h4>
                <p style="font-family: 'Alegreya', serif; font-size:1.5em;">Create an account.</p>
            </v-flex>
            <v-flex xs12>
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
                            label="First Name"
                            v-model="firstName"
                            :rules="firstNameRules"
                            name="firstName"
                            required
                    ></v-text-field>
                    <v-text-field
                            label="Last Name"
                            v-model="lastName"
                            :rules="lastNameRules"
                            name="lastName"
                            required
                    ></v-text-field>
                    <v-text-field
                            label="Password"
                            v-model="password"
                            name="password"
                            :rules="passwordRules"
                            :counter="8"
                            :append-icon="e1 ? 'visibility' : 'visibility_off'"
                            :append-icon-cb="() => (e1 = !e1)"
                            :type="e1 ? 'password' : 'text'"
                            required
                    ></v-text-field>

                    <v-checkbox
                            label="I agree Terms and Conditions!."
                            v-model="checkbox"
                            :rules="[(v) => !!v || 'You must agree to continue!']"
                            required
                    ></v-checkbox>

                    <v-btn round @click="FormSubmit" :class="{ green: valid, red: !valid }">Register</v-btn>
                    <v-btn round @click="clear">clear</v-btn>
                    <a class="btn btn-link transparent" href="{{ url('/password/reset') }}">Forgot Your Password?</a>
                </v-form>
            </v-flex>
        </v-layout>
    </v-container>
@stop

@section('script')
    <script>
        new Vue({
            el: '#app',
            data: {
                e1:true,

                firstName:'',
                firstNameRules: [
                    (v) => !!v || 'First Name is required',
        ],
        lastName:'',
            lastNameRules: [
            (v) => !!v || 'Last Name is required',
        ],
        search:'',
            buttons: [
            {
                text: 'Home',
                url: '/home'
            },
            {
                text: 'Register',
                url: '/register'
            }],
            valid: false,
            password: '',
            passwordRules: [
            (v) => !!v || 'Password is required',
            (v) => v && v.length >= 8 || 'Minimum 8 characters'
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
            checkbox: false,
            invalidCharacter: ['+','-','*','/','*','1','2','3','4','5','6','7','8','9','0']
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
            },
            submit: function (e){
                axios.post('/api/submit',{
                    search:this.search
                })
            },
            firstNameValidation: function(){
                if(this.firstNameRules.length >= 2){
                    this.firstNameRules.pop()
                }
                for(var i=0; i<this.firstName.length; i++){
                    if(this.invalidCharacter.indexOf(this.firstName.charAt(i)) === -1 && i == this.firstName.length-1 && this.firstNameRules.length >= 2){
                        this.firstNameRules.pop()
                    }else if (this.invalidCharacter.indexOf(this.firstName.charAt(i)) != -1){
                        if(this.firstNameRules.length >= 2){
                            this.firstNameRules.pop()
                            this.firstNameRules.push(() => 'Your input contains illegal character(s)')
                            break
                        }else{
                            this.firstNameRules.push(() => 'Your input contains illegal character(s)')
                            break
                        }
                    }
                }
            },
            lastNameValidation: function(){
                if(this.lastNameRules.length >= 2){
                    this.lastNameRules.pop()
                }
                for(var i=0; i<this.lastName.length; i++){
                    if(this.invalidCharacter.indexOf(this.lastName.charAt(i)) === -1 && i == this.lastName.length-1 && this.lastNameRules.length >= 2){
                        this.lastNameRules.pop()
                    }else if (this.invalidCharacter.indexOf(this.lastName.charAt(i)) != -1){
                        if(this.lastNameRules.length >= 2){
                            this.lastNameRules.pop()
                            this.lastNameRules.push(() => 'Your input contains illegal character(s)')
                            break
                        }else{
                            this.lastNameRules.push(() => 'Your input contains illegal character(s)')
                            break
                        }
                    }
                }
            }
        },
        watch: {
            firstName: function (e){
                if(e == null){
                    return
                }
                else{
                    this.firstNameValidation()
                }
            },
            lastName: function (e){
                if(e == null){
                    return
                }
                else{
                    this.lastNameValidation()
                }
            }
        }
        })
    </script>
@stop
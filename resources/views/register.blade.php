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
        input[type=file] {
            position: absolute;
            left: -99999px;
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
    <v-container class="mt-5">
        <v-layout align-center  column wrap>
            <v-flex xs12 class="text-xs-center">
                <h4 style="font-family: 'Merriweather', serif;" >Welcome</h4>
                <p style="font-family: 'Alegreya', serif; font-size:1.5em;">Create an account.</p>
            </v-flex>
            <v-flex xs12>
                <transition name="fade" appear>
                    <v-alert color="error" icon="warning" v-model="isError">
                        Email has been taken.
                    </v-alert>
                </transition>
                <v-stepper v-model="s">
                    <v-stepper-header>
                        <v-stepper-step step="1" :complete="s > 1">Setting Up</v-stepper-step>
                        <v-divider></v-divider>
                        <v-stepper-step step="2" :complete="s > 2">Addtional Info</v-stepper-step>
                        <v-divider></v-divider>
                        <v-stepper-step step="3">Complete</v-stepper-step>
                    </v-stepper-header>
                    <v-stepper-content step="1">
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
                                    :rules="passwordRules"
                                    :counter="8"
                                    :append-icon="e1 ? 'visibility' : 'visibility_off'"
                                    :append-icon-cb="() => (e1 = !e1)"
                                    :type="e1 ? 'password' : 'text'"
                                    required
                            ></v-text-field>
                            <v-btn round @click="FormSubmit" :class="{ green: valid, red: !valid }">Continue</v-btn>
                            <v-btn round @click="clear">Clear</v-btn>
                    </v-stepper-content>
                    <v-stepper-content step="2">
                        <v-form v-model="valid1" ref="form1" action="{{ url('/login') }}" method="post">
                            {{ csrf_field() }}
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
                            <v-menu
                                    lazy
                                    :close-on-content-click="false"
                                    v-model="menu"
                                    transition="scale-transition"
                                    offset-x
                                    full-width
                                    :nudge-right="40"
                                    max-width="290px"
                                    min-width="290px"
                                    allow-overflow

                            >
                                <v-text-field
                                        slot="activator"
                                        label="Date of Birth"
                                        v-model="dateofbirth"
                                        :rules="dobRules"
                                        name="dateofbirth"
                                        append-icon="event"
                                        readonly
                                        required
                                ></v-text-field>
                                <v-date-picker v-model="dateofbirth" no-title scrollable actions>
                                    <template slot-scope="{ save, cancel }">
                                        <v-card-actions>
                                            <v-spacer></v-spacer>
                                            <v-btn flat color="primary" @click.native="cancel">Cancel</v-btn>
                                            <v-btn flat color="primary" @click.native="save">OK</v-btn>
                                        </v-card-actions>
                                    </template>
                                </v-date-picker>
                            </v-menu>
                            <label style="font-size:1.25em;">Select Your Gender: </label>
                            <v-radio-group row v-model="gender" :rules="[(v) => !!v || 'You must select one to continue!']">
                                <v-radio label="Male" value="male"></v-radio>
                                <v-radio label="Female" value="female"></v-radio>
                            </v-radio-group>
                            <v-text-field
                                    append-icon="attach_file"
                                    :append-icon-cb="onFocus"
                                    single-line
                                    v-model="filename"
                                    label="Choose your profile image"
                                    :rules="[(v) => !!v || 'Profile image is required']"
                                    required
                                    :disabled = "disabled"
                                    ref="fileTextField"
                                    readonly
                            ></v-text-field>
                            <input type="file" :accept="accept" :disabled="disabled"
                                   ref="fileInput" @change="changeFile" name='profile'>
                            <div class="text-xs-center"><img id='image' style="max-width:100px; max-height:100px;"/></div>
                            <br/>
                            <br/>
                            <v-checkbox
                                    label="I agree Terms and Conditions!."
                                    v-model="checkbox"
                                    :rules="[(v) => !!v || 'You must agree to continue!']"
                                    required
                            ></v-checkbox>
                            <v-btn round @click="FormSubmit2" :class="{ green: valid1, red: !valid1 }">Register</v-btn>
                            <v-btn round @click="clear">Clear</v-btn>
                            <v-btn round @click="GoBack">Go Back</v-btn>
                        </v-form>
                    </v-stepper-content>
                    <v-stepper-content step="3">
                        <v-card-text class="headline">Congratulation! You're ready</v-card-text>
                        <v-btn color="primary" @click.native="s = 1">Awesome</v-btn>
                    </v-stepper-content>
                </v-stepper>
            </v-flex>
        </v-layout>
    </v-container>
@stop

@section('script')
    <script>
        new Vue({
            el: '#app',
            props: {
                accept: {
                    type: String,
                    default: "image/*"
                },
                disabled: {
                    type: Boolean,
                    default: false
                },
                value: {
                    type: [Array, String]
                }
            },
            data: {
                isError: true,
                filename:'',
                s:0,
                gender: 'male',
                e1:true,
                preview: '',
                menu:false,
                firstName:'',
                firstNameRules: [
                    (v) => !!v || 'First Name is required',
        ],
        lastName:'',
            lastNameRules: [
            (v) => !!v || 'Last Name is required',
        ],
        dateofbirth:null,
            dobRules: [
            (v) => !!v || 'Date of birth is required',
        ],
        valid: false,
            valid1: false,
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
                    this.s=2
                    //this.$refs.form.$el.submit()
                }

            },
            FormSubmit2 () {
                if(this.$refs.form1.validate()){
                    this.s=3
                }
            },
            GoBack: function() {
                this.s--
            },
            clear () {
                this.valid=false
                this.$refs.form.reset()
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
            },
            getFormData(files){
                const data = new FormData()
                for (let file of files) {
                    data.append('files[]', file, file.name)
                }
                return data
            },
            onFocus(){
                if (!this.disabled) {
                    debugger
                    this.$refs.fileInput.click()
                }
            },
            changeFile($event){
                const files = $event.target.files || $event.dataTransfer.files
                const form = this.getFormData(files)
                var reader = new FileReader()
                reader.onload = function (e) {
                    // get loaded data and render thumbnail.

                    document.getElementById("image").src = e.target.result
                }
                if (files) {
                    if (files.length > 0) {
                        this.filename = [...files].map(file => file.name).join(', ')
                    } else {
                        this.filename = null
                    }
                } else {
                    this.filename = $event.target.value.split('\\').pop()
                }
                // read the image file as a data URL.
                reader.readAsDataURL(files[0])

                this.$emit('input', this.filename)
                this.$emit('formData', form)
            }
        },
        computed:{

        },
        watch: {
            email: function(){
                setTimeout(()=>{this.isError=false},1500)
            },
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
            },
            fileValue: function (fv){
                this.filename = fv;
            },

        },
        mounted() {
            this.filename = this.fileValue;
        }
        })
    </script>
@stop
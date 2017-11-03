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
        input[type=file] {
            position: absolute;
            left: -99999px;
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
                <v-alert color="error" icon="warning" v-model="getError">
                    Email has been taken.
                </v-alert>
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
                            :rules="[(v) => !!v || 'You must have a profile image']"
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
            getError: function(){
                setTimeout(()=>{this.isError=false},4000)
                return this.isError
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
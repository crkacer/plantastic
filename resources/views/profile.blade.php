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
            background-color:#efefef;
        }
        input[type=file] {
            position: absolute;
            left: -99999px;
        }
    </style>
@stop

@section('body')
    <v-container class="ma-0 pa-0" fluid>
        <v-layout row wrap>
            <v-flex xs12>
                <v-container fluid class=" ma-0 pa-0 transparent" style="z-index:1;">
                    <v-layout row>
                        <v-flex xs12>
                            <v-card flat style="border-bottom: 1px solid #9b9b9b;">
                                <v-container fluid class="ma-0 pa-2">
                                    <v-layout align-center justify-center row wrap>
                                        <v-flex xs7 class="text-xs-center">
                                            <v-avatar size="45%"><img :src=user.profile_picture alt="User Avatar"></v-avatar>
                                        </v-flex>
                                        <v-flex xs5 >
                                            <v-card-title class="text-xs-center">
                                                <div >
                                                    <div class="title mb-2"><b>Full Name:</b> @{{ fullName }}</div>
                                                    <div class="title mb-2"><b>Date of Birth:</b> @{{ dateofbirth }}</div>
                                                    <div class="title mb-2"><b>Age:</b> @{{ age }} years old</div>
                                                    <div class="title" style="text-transform:capitalize;" ><b>Gender:</b> @{{ gender }}</div>
                                                </div>
                                            </v-card-title>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                            </v-card>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-flex>
    
            <v-flex xs12>
                <v-container fluid>
                    <v-layout align-center justify-center row-wrap>
                        <v-flex xs7>
                            <v-form v-model="valid" ref="form" action="{{ url('/login') }}" method="post" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <v-card-text class="text-xs-center"><h4 class="mb-0" style="font-family: 'Cinzel', serif;"><b>Account Settings</b></h4></v-card-text>
                                <v-text-field
                                        label="Email"
                                        v-model="email"
                                        :rules="emailRules"
                                        name="email"
                                        @focus ="isError = false"
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
                                    <v-date-picker v-model="dateofbirth" no-title scrollable actions :allowed-dates="allowedDates">
                                        <template slot-scope="{ save, cancel }">
                                            <v-card-actions>
                                                <v-spacer></v-spacer>
                                                <v-btn flat color="primary" @click.native="cancel">Cancel</v-btn>
                                                <v-btn flat color="primary" @click.native="save">OK</v-btn>
                                            </v-card-actions>
                                        </template>
                                    </v-date-picker>
                                </v-menu>
                                <label style="font-size:1.25em;">Select your gender: </label>
                                <v-radio-group v-model="gender">
                                    <v-container fluid class="ma-0 pa-0 transparent">
                                        <v-layout row wrap>
                                            <v-flex xs6>
                                                <v-radio label="Male" value='male'></v-radio>
                                            </v-flex>
                                            <v-flex xs6>
                                                <v-radio label="Female" value='female'></v-radio>
                                            </v-flex>
                                        </v-layout>
                                    </v-container>
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
                                       ref="fileInput" @change="changeFile" name='profile' id="files">
                                <div class="text-xs-center"><img id='image' :src=fileURL style="max-width:100px; max-height:100px;"/></div>
                                <br/>
                                <br/>
                                <v-text-field
                                        label="Social Media Link"
                                        v-model="socialMedia"
                                        name="socialMedia"
                                ></v-text-field>    
                                <div class="text-xs-center"><v-btn round @click="sendForm" :class="{ green: valid, red: !valid }">Update</v-btn></div>
                                
                            </v-form>
                            <v-dialog v-model="success" persistent max-width="500">
                                <v-card>
                                    <v-card-title class="headline">Congratulation! You've successfully updated your profile</v-card-title>
                                    <v-card-actions>
                                      <v-spacer></v-spacer>
                                      <v-btn href="/user/profile/" color="primary" flat >Awesome</v-btn>
                                    </v-card-actions>
                                  </v-card>
                            </v-dialog>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-flex>
        </v-layout>
    </v-container>
@stop

@section('script')
    <script>
        var user_login = <?php echo json_encode($user_login); ?>;
        var vm = new Vue({
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
                success: false,
                gender: '',
                e1:true,
                preview: '',
                menu:false,
                user: user_login,
                socialMedia: '',
                age: '',
                fullName: '',
                filename:'',
                fileURL: '',
                name: '',
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
            },
            convertToDateObject : function (dateString){
                return (
                    dateString.constructor === Date ? dateString :
                        dateString.constructor === Number ? new Date(dateString) :
                            dateString.constructor === String ? new Date(dateString) :
                                typeof dateString === "object" ? new Date(dateString.year, dateString.month, dateString.date) :
                                    NaN
                )
            },
            compare: function(a,b){
                return (
                    isFinite(a=this.convertToDateObject(a).valueOf()) &&
                    isFinite(b=this.convertToDateObject(b).valueOf()) ?
                        (a>b)-(a<b):
                        NaN
                )
            },
            allowedDates: function(date){
                const day = new Date()
                const d = new Date()
                d.setFullYear(day.getFullYear() - 17)
                return this.compare(d,date) == 1 ? date : null 
            },
            getYear: function(d){
                var nowTime = Date.now()
                var result = isFinite(a=this.convertToDateObject(d).getFullYear()) && isFinite(b=this.convertToDateObject(nowTime).getFullYear()) ? (a < b ? b-a : "N/A") : NaN
                return result
            },
            sendForm: function(){
                var form = new FormData()
                var imagefile = document.querySelector('#files')
                console.log(imagefile.files[0]);
                form.append("photo", imagefile.files[0]);
                form.append("email",vm.email);
                form.append("password",vm.password);
                form.append("firstname",vm.firstName);
                form.append("lastname",vm.lastName);
                form.append("id",vm.user.id);
                form.append("dob",vm.dateofbirth);
                form.append("gender",vm.gender);
                form.append("socialMedia",vm.socialMedia);
                const config = { headers: { 'Content-Type': 'multipart/form-data' } };
                if (this.$refs.form.validate()) {
                    axios.post('/user/profile', form,config)
                      .then(function (response) {
                        console.log(response.data)
                        if(response.data == 0){
                            vm.success = true
                        }
                      })
                      .catch(function (error) {
                        console.log(error);
                      });
                }
            }
        },
        computed:{

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
            this.filename = this.fileValue
            this.firstName = this.user.firstname
            this.lastName= this.user.lastname
            this.fullName = this.user.firstname + " " + this.user.lastname
            this.dateofbirth = this.user.DOB
            this.email = this.user.email
            this.password = "password"
            this.filename = this.user.profile_picture.replace("/assets/img/user_photo/"+this.user.id+"/","")
            this.fileURL = this.user.profile_picture
            this.gender = this.user.gender
            this.socialMedia = this.user.social_network
            this.age = this.getYear(this.dateofbirth)
        }
        })
    </script>
@stop
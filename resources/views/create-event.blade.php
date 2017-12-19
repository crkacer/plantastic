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
        #map{
            height: 400px;
            
        }
    </style>
@stop

@section('body')
    <v-container class="ma-0 pa-0" fluid>
        <v-layout row wrap>
            <v-flex xs12>
                <v-container fluid>
                    <v-layout align-center justify-center row wrap>
                        <v-flex xs7 class="mt-3">
                            <v-form v-model="valid" ref="form" action="{{ url('/login') }}" method="post" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <v-card-text class="text-xs-center"><h4 class="mb-0" style="font-family: 'Cinzel', serif;"><b>1. Basic Information</b></h4></v-card-text>
                                <v-text-field
                                        label="Event Title"
                                        v-model="tempEvent.title"
                                        :rules="[(v) => !!v || 'Title is required']"
                                        name="title"
                                        required
                                ></v-text-field>
                                <v-text-field
                                        v-model="tempEvent.location"
                                        :rules="[(v) => !!v || 'Location is required']"
                                        name="location"
                                        placeholder="Location*"
                                        ref="autocomplete"
                                        required
                                ></v-text-field>
                                
                                 <div
                                        id="hidden-map"
                                        hidden
                                ></div>
                                
                                <v-container fluid class="transparent ma-0 pa-0">
                                    <v-layout align-center justify-center row wrap>
                                        <v-flex xs3 class="mr-4">
                                            <v-menu
                                                    lazy
                                                    :close-on-content-click="false"
                                                    v-model="m1"
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
                                                        label="Start Date"
                                                        v-model="tempEvent.startDate"
                                                        :rules="[(v) => !!v || 'Start date is required']"
                                                        name="startDate"
                                                    
                                                        append-icon="event"
                                                        readonly
                                                        required
                                                ></v-text-field>
                                                <v-date-picker v-model="tempEvent.startDate" no-title actions :allowed-dates="allowedStartDates">
                                                    <template slot-scope="{ save, cancel }">
                                                        <v-card-actions>
                                                            <v-spacer></v-spacer>
                                                            <v-btn flat color="primary" @click.native="cancel">Cancel</v-btn>
                                                            <v-btn flat color="primary" @click.native="save">OK</v-btn>
                                                        </v-card-actions>
                                                    </template>
                                                </v-date-picker>
                                            </v-menu>
                                        </v-flex>
                                        <v-flex xs3 class="mr-4">
                                            <v-menu
                                                    lazy
                                                    :close-on-content-click="false"
                                                    v-model="m3"
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
                                                        label="End Date"
                                                        v-model="tempEvent.endDate"
                                                        :rules="[(v) => !!v || 'End date is required']"
                                                        name="endDate"
                                                        append-icon="event"
                                                        
                                                        readonly
                                                        required
                                                ></v-text-field>
                                                <v-date-picker v-model="tempEvent.endDate" no-title actions :allowed-dates="allowedEndDates">
                                                    <template slot-scope="{ save, cancel }">
                                                        <v-card-actions>
                                                            <v-spacer></v-spacer>
                                                            <v-btn flat color="primary" @click.native="cancel">Cancel</v-btn>
                                                            <v-btn flat color="primary" @click.native="save">OK</v-btn>
                                                        </v-card-actions>
                                                    </template>
                                                </v-date-picker>
                                            </v-menu>
                                        </v-flex>
                                        <v-flex xs2 class="mr-4">
                                            <v-menu
                                                    lazy
                                                    :close-on-content-click="false"
                                                    v-model="m2"
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
                                                        label="Start Time"
                                                        v-model="tempEvent.startTime"
                                                        :rules="[(v) => !!v || 'Start time is required']"
                                                        name="startTime"
    
                                                        append-icon="access_time"
                                                        readonly
                                                        required
                                                ></v-text-field>
                                                <v-time-picker v-model="tempEvent.startTime" format="24hr" no-title actions :allowed-hours="allowedStartHours">
                                                    <template slot-scope="{ save, cancel }">
                                                        <v-card-actions>
                                                            <v-spacer></v-spacer>
                                                            <v-btn flat color="primary" @click.native="cancel">Cancel</v-btn>
                                                            <v-btn flat color="primary" @click.native="save">OK</v-btn>
                                                        </v-card-actions>
                                                    </template>
                                                </v-time-picker>
                                            </v-menu>
                                        </v-flex>
                                        
                                        <v-flex xs2>
                                            <v-menu
                                                    lazy
                                                    :close-on-content-click="false"
                                                    v-model="m4"
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
                                                        label="End Time"
                                                        v-model="tempEvent.endTime"
                                                        :rules="[(v) => !!v || 'End time is required']"
                                                        name="endTime"
                                                        
                                                        append-icon="access_time"
                                                        readonly
                                                        required
                                                ></v-text-field>
                                                <v-time-picker v-model="tempEvent.endTime" format="24hr" no-title actions :allowed-hours="allowedEndHours">
                                                    <template slot-scope="{ save, cancel }">
                                                        <v-card-actions>
                                                            <v-spacer></v-spacer>
                                                            <v-btn flat color="primary" @click.native="cancel">Cancel</v-btn>
                                                            <v-btn flat color="primary" @click.native="save">OK</v-btn>
                                                        </v-card-actions>
                                                    </template>
                                                </v-time-picker>
                                            </v-menu>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                                <v-text-field
                                        label="Event Image"
                                        append-icon="attach_file"
                                        :append-icon-cb="onFocus"
                                        single-line
                                        v-model="tempEvent.imageName"
                                        :rules="[(v) => !!v || 'You must have a profile image']"
                                        required
                                        :disabled = "disabled"
                                        ref="fileTextField"
                                        readonly
                                ></v-text-field>
                                <input type="file" :accept="accept" :disabled="disabled"
                                       ref="fileInput" @change="changeFile" name='profile' id="files">
                                <div class="text-xs-center"><img id='image' :src=tempEvent.imgURL style="max-width:200px; max-height:200px;"/></div>
                                <v-text-field
                                        label="Event Description"
                                        v-model="tempEvent.description"
                                        name="description"
                                        multi-line
                                ></v-text-field>
                                <v-text-field
                                        label="Organizer Description"
                                        v-model="tempEvent.orgDescription"
                                        name="orgDescription"
                                        multi-line
                                >
                                </v-text-field>
                                <v-card-text class="text-xs-center" style="font-family: 'Cinzel', serif;"><h4 class="mb-0"><b>2. Event Settings</b></h4></v-card-text>
                                <v-container fluid class="transparent ma-0 pa-0">
                                    <v-layout row wrap>
                                        <v-flex xs4>
                                            <v-subheader><b>Event Category:</b></v-subheader>
                                        </v-flex>
                                        <v-flex xs8>
                                            <v-select
                                                    label="Select"
                                                    v-bind:items="EventCategories"
                                                    v-model="tempEvent.category"
                                                    max-height="400"
                                                    hint="Pick your category"
                                                    persistent-hint
                                                    :rules="[(v) => !!v || 'Please provide a category for the event']"
                                                    required
                                            ></v-select>
                                        </v-flex>
                                        <v-flex xs4>
                                            <v-subheader ><b>Event Type:</b></v-subheader>
                                        </v-flex>
                                        <v-flex xs8>
                                            <v-select
                                                    label="Select"
                                                    v-bind:items="EventTypes"
                                                    v-model="tempEvent.type"
                                                    max-height="400"
                                                    hint="Pick your type"
                                                    persistent-hint
                                                    :rules="[(v) => !!v || 'Please provide a type for the event']"
                                                    required
                                            ></v-select>
                                        </v-flex>
                                        <v-flex xs4>
                                            <v-subheader><b>Event Capacity:</b></v-subheader>
                                        </v-flex>
                                        <v-flex xs8>
                                            <v-text-field
                                                    type="number"
                                                    label="Event capacity"
                                                    v-model="tempEvent.capacity"
                                                    :rules="capacityRules"
                                                    required
                                            ></v-text-field>
                                        </v-flex>
                                        <v-flex xs4>
                                            <v-subheader><b>Price:</b></v-subheader>
                                        </v-flex>
                                        <v-flex xs8>
                                            <v-text-field
                                                    type="number"
                                                    label="Price"
                                                    v-model="tempEvent.price"
                                                    :rules="priceRules"
                                                    required
                                            ></v-text-field>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                                <label style="font-size:1.25em;">Choose your event layout: </label>
                                <v-radio-group v-model="tempEvent.layoutID" :rules="[(v) => !!v || 'You must select one to continue!']" required>
                                    <v-container fluid class="ma-0 pa-0 transparent">
                                        <v-layout row wrap>
                                            <v-flex xs6>
                                                <v-radio label="Form 1" value='A'></v-radio><a href="#" v-on:click.prevent="showfullpic1 = true"><img  src="/assets/img/event1.png" width="200" height="200" v-on:click.native.stop="showfullpic1 = true"> </a>
                                            </v-flex>
                                            <v-flex xs6>
                                                <v-radio label="Form 2" value='B'></v-radio><a href="#" v-on:click.prevent="showfullpic2 = true"><img href="#" src="/assets/img/event2.png" width="200" height="200" v-on:click.native.stop="showfullpic2 = true"></a>
                                            </v-flex>
                                        </v-layout>
                                    </v-container>
                                </v-radio-group>
                                <label style="font-size:1.25em;">Do you want to generate a unique code for your event? </label>
                                <v-radio-group v-model="tempEvent.uniqueCode" :rules="[(v) => !!v || 'You must select one to continue!']" required>
                                    <v-container fluid class="ma-0 pa-0 transparent">
                                        <v-layout row wrap>
                                            <v-flex xs6>
                                                <v-radio label="Yes" value='Y'></v-radio>
                                            </v-flex>
                                            <v-flex xs6>
                                                <v-radio label="No" value='N'></v-radio>
                                            </v-flex>
                                        </v-layout>
                                    </v-container>
                                </v-radio-group>
                                <v-dialog v-model="showfullpic1" max-width="1000">
                                    <img src="/assets/img/event1.png">
                                </v-dialog>
                                <v-dialog v-model="showfullpic2" max-width="1000">
                                    <img src="/assets/img/event2.png">
                                </v-dialog>
                                <div class="text-xs-center"><v-btn round @click="FormSubmit" :class="{ green: valid, red: !valid }">Create</v-btn><v-btn round @click="clear">Clear</v-btn></div>
                                <v-dialog v-model="success" persistent max-width="500">
                                    <v-card>
                                        <v-card-title class="headline">Congratulations! You've successfully created an event</v-card-title>
                                        <v-card-actions>
                                          <v-spacer></v-spacer>
                                          <v-btn href="/user/manage-event" color="primary" flat >Awesome</v-btn>
                                        </v-card-actions>
                                      </v-card>
                                </v-dialog>
                            </v-form>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-flex>
        </v-layout>
    </v-container>
@stop

@section('script')
    <script>
        var allCat = <?php echo json_encode($all_categories); ?>;
        var allType = <?php echo json_encode($all_types); ?>;
        console.log(allCat);
        console.log(allType);
        
        
        function initMAP() {
            var uluru = {lat: 43.6532, lng: -79.3832};
            const google = window.google
            var map = new google.maps.Map(document.getElementById('hidden-map'), {
                zoom: 4,
                center: uluru
            });
            
            
            var element = vm.$refs.autocomplete.$el
            
            element = element.querySelector('input');
            var autocomplete = new google.maps.places.Autocomplete(
            
            (element),
            
            {types: ['establishment','geocode']});
            
            google.maps.event.addListener(autocomplete, 'place_changed', function() {
                vm.tempEvent.location = autocomplete.getPlace().formatted_address;
                vm.tempEvent.latitude = autocomplete.getPlace().geometry.location.lat();
                vm.tempEvent.longtitude = autocomplete.getPlace().geometry.location.lng();
          
            
                    // vm.tempEvent.location = place;
            });
            
        }

        
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
                m1: false,
                m2: false,
                m3: false,
                m4: false,
                showfullpic1: false,
                showfullpic2: false,
                success: false,
                allCategories: allCat,
                allTypes: allType,
                EventCategories: [],
                EventTypes: [],
                autocomplete: '',
                valid: false,
                tempEvent: {
                    imgURL: '',
                    imageName: '',
                    title: '',
                    location: '',
                    capacity: 0,
                    startDate: null,
                    startTime: null,
                    endDate: null,
                    endTime: null,
                    description: '',
                    orgDescription: '',
                    price: 0,
                    category:'',
                    type: '',
                    layoutID: '',
                    uniqueCode: '',
                    latitude: 0,
                    longtitude: 0
                },
                capacityRules: [
                    (v) => !!v || 'Please enter the event capacity',
                    (v) => (!isNaN(v) && v <= 10000 && v >= 1) || 'Your capacity should be a number and within 1 to 5000'
                ],
                priceRules: [
                    (v) => !!v || 'Please enter price for the event',
                    (v) => (!isNaN(v) && v >= 0) || 'Your price should be a valid number' 
                ]
        },
        methods: {
            getAddressData: function (addressData, placeResultData, id) {
                this.address = addressData;
            },
            allowedStartDates: function (date){
                if(vm.tempEvent.endDate != null){
                   if(this.compare(date,vm.tempEvent.endDate) == 0 || this.compare(date,vm.tempEvent.endDate) == -1){
                    return date
                    } 
                }else{
                    return date
                }
                
            },
            allowedEndDates: function(date){
                if(vm.tempEvent.startDate != null){
                    if(this.compare(date,vm.tempEvent.startDate) == 0 || this.compare(date,vm.tempEvent.startDate) == 1){
                        return date
                    }
                }else{
                    return date
                }
               
            },
            
            allowedStartHours: function(value){
                if(vm.tempEvent.endDate != null && vm.tempEvent.startDate != null){
                    if(this.compare(vm.tempEvent.endDate, vm.tempEvent.startDate)==0){
                        if(vm.tempEvent.endTime != null){
                            return value < vm.tempEvent.endTime.slice(0,2)
                            
                        }else{
                            return value
                        }
                    }else{
                        return value
                    }
                }else{
                    return 0
                }
            },
            allowedEndHours: function(value){
                if(vm.tempEvent.endDate != null && vm.tempEvent.startDate != null){
                    if(this.compare(vm.tempEvent.endDate, vm.tempEvent.startDate) == 0){
                        if(vm.tempEvent.startTime != null){
                            return value > vm.tempEvent.startTime.slice(0,2)
                        }else{
                            return value
                        }
                    }else{
                        return value
                    }
                }else{
                    return 0
                }
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
            findCategoryID: function(category){
                for(var i = 0; i < this.allCategories.length; i++){
                    if(this.allCategories[i].text == category){
                        return this.allCategories[i].id
                    }
                }
            },
            findTypeID: function(type){
                for(var i = 0; i < this.allTypes.length; i++){
                    if(this.allTypes[i].text == type){
                        return this.allTypes[i].id
                    }
                }
            },
            FormSubmit: function () {
                if (this.$refs.form.validate()) {
                    //this.$refs.form.$el.submit()
                    var form = new FormData()
                    var imagefile = document.querySelector('#files')
                    console.log(imagefile.files[0]);
                    console.log(vm.findCategoryID(vm.tempEvent.category));
                    console.log(vm.findTypeID(vm.tempEvent.type));
                    form.append("photo", imagefile.files[0]);
                    form.append("title",vm.tempEvent.title);
                    form.append("location",vm.tempEvent.location);
                    form.append("capacity",vm.tempEvent.capacity);
                    form.append("startdate",vm.tempEvent.startDate);
                    form.append("starttime",vm.tempEvent.startTime);
                    form.append("enddate",vm.tempEvent.endDate);
                    form.append("endtime",vm.tempEvent.endTime);
                    form.append("description",vm.tempEvent.description);
                    form.append("organizerDescription",vm.tempEvent.orgDescription);
                    form.append("price",vm.tempEvent.price);
                    form.append("category",vm.findCategoryID(vm.tempEvent.category));
                    form.append("type",vm.findTypeID(vm.tempEvent.type));
                    form.append("layoutID",vm.tempEvent.layoutID);
                    form.append("uniqueCode",vm.tempEvent.uniqueCode);
                    form.append("latitude",vm.tempEvent.latitude);
                    form.append("longitude",vm.tempEvent.longtitude);
                    const config = { headers: { 'Content-Type': 'multipart/form-data' } };
                    if (this.$refs.form.validate()) {
                        axios.post('/event/create', form,config)
                          .then(function (response) {
                            console.log(response.data);
                            if(response.data == 0){
                                vm.success = true
                            }
                          })
                          .catch(function (error) {
                            console.log(error);
                          });
                        //this.$refs.form.$el.submit()
                    }
                }
            },
            clear: function () {
                this.valid=false
                this.$refs.form.reset()
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
                        this.tempEvent.imageName = [...files].map(file => file.name).join(', ')
                    } else {
                        this.tempEvent.imageName = null
                    }
                } else {
                    this.tempEvent.imageName = $event.target.value.split('\\').pop()
                }
                // read the image file as a data URL.
                reader.readAsDataURL(files[0])

                this.$emit('input', this.tempEvent.imageName)
                this.$emit('formData', form)
            },
            increase: function(){
                if(this.tempEvent.capacity < 10000){
                    this.tempEvent.capacity++
                }
            },
            decrease: function(){
                if(this.tempEvent.capacity > 1){
                    this.tempEvent.capacity--
                }
            },
            saveEvent: function(){
                if(this.tempEvent.startDate != null){
                    this.m1 = false
                    
                }
                else if(this.tempEvent.endDate != null){
                    this.allowedStartDates.max = this.tempEvent.endDate
                    this.m3 = false
                }
                console.log(this.allowedEndDates)
                console.log(this.tempEvent.startDate)
                console.log(this.tempEvent.endDate)
            },
        
        },
        computed:{
            
        },
        watch: {
            fileValue: function (fv){
                this.tempEvent.imageName = fv;
            }
            
            
        },
        mounted: function() {
            this.tempEvent.imageName = this.fileValue
            for(var i = 0; i < this.allCategories.length; i++){
                this.EventCategories.push(this.allCategories[i].text)
            }
            for(var k = 0; k < this.allTypes.length; k++){
                this.EventTypes.push(this.allTypes[k].text)
            }
            
            
           
            
        }
        })
        
    </script>
    
    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDuP7giqQQp4O8FE8oL41qjWLyFlcv3Ws8&libraries=places&callback=initMAP">
    </script>
    
@stop
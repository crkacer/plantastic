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
                                <v-container fluid class="ma-0 pa-0">
                                    <v-layout row wrap>
                                        <v-flex xs8>
                                            <v-card-title>
                                                <div>
                                                    <span><b>@{{ event.status }}</b></span>
                                                    <span><h4>@{{ event.title }}</h4></span>
                                                    <span>@{{ event.location }}</span>
                                                    <br/>
                                                    <span>@{{ fullDate }}</span>
                                                </div>
                                            </v-card-title>
                                        </v-flex>
                                        <v-flex xs4>
                                            <v-layout align-center justify-center>
                                                <v-card-actions><v-btn outline color="orange" round :href="event.viewLink" class="mt-5">View</v-btn></v-card-actions>
                                            </v-layout>
                                        </v-flex>
                                    </v-layout>
                                </v-container>
                            </v-card>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-flex>
            <v-flex xs3>
                <v-navigation-drawer style="z-index:0; position:relative;" :mini-variant.sync="mini" persistent floating light app v-model="drawer" class="grey lighten-4 elevation-4" >
                    <v-toolbar flat class="transparent">
                        <v-list class="pa-0">
                            <v-list-tile avatar>
                                <v-list-tile-content v-if="!mini">
                                    <v-list-tile-title class="title">Select your option</v-list-tile-title>
                                </v-list-tile-content>
                                <v-list-tile-action>
                                    <v-btn icon @click.native.stop="mini = !mini">
                                        <v-icon v-if="mini">chevron_right</v-icon>
                                        <v-icon v-else>chevron_left</v-icon>
                                    </v-btn>
                                </v-list-tile-action>
                            </v-list-tile>
                        </v-list>
                    </v-toolbar>
                    <v-list dense class="pt-0">
                        <v-divider></v-divider>
                        <v-list-tile v-for="(navigation,i) in navigations" :key="i" @click="getEvent(i)">
                            <v-list-tile-action>
                                <v-icon>@{{ navigation.icon }}</v-icon>
                            </v-list-tile-action>
                            <v-list-tile-content>
                                <v-list-tile-title>@{{ navigation.text }}</v-list-tile-title>
                            </v-list-tile-content>
                        </v-list-tile>
                    </v-list>
                </v-navigation-drawer>
            </v-flex>
            <v-flex xs9>
                
                <v-container v-show="showResponse == 'dashboard'" fluid>
                    <v-layout row wrap>
                        <v-flex xs12 class="text-xs-left">
                            <v-card-text ><h3>Event Dashboard</h3></v-card-text>
                            <hr/>
                        </v-flex>
                        <v-flex xs12>
                            <v-container fluid text-xs-left>
                                <v-layout row wrap>
                                    <v-flex xs3 style="border:1px solid black;" class="mr-4">
                                        <v-card-text style="font-family: 'Lora', serif;" v-if="event.status == 'Ongoing'" ><v-icon>event</v-icon> Ongoing!
                                            <br/>
                                            <br/>
                                            Your event is up and running.
                                        </v-card-text>
                                        <v-card-text style="font-family: 'Lora', serif;" v-if="event.status=='Past'" class="mr-4" ><v-icon>&#xE867;</v-icon> Past!
                                            <br/>
                                            <br/>
                                            Your event has passed.
                                        </v-card-text>
                                    </v-flex>
                                    <v-flex xs4 style="border:1px solid black;" class="mr-4">
                                        <v-card-text style="font-family: 'Lora', serif;" v-if="type=='Public'">
                                            <v-icon>&#xE80B;</v-icon> Public
                                            <br/>
                                            <br/>
                                            Your event is made public and searchable.
                                        </v-card-text>
                                        <v-card-text style="font-family: 'Lora', serif;" class="mr-4" v-else>
                                            <v-icon>&#xE897;</v-icon> Private
                                            <br/>
                                            <br/>
                                            A list of attendants is included.
                                        </v-card-text>
                                    </v-flex>
                                    <v-flex xs4 style="border:1px solid black;">
                                        <v-card-text style="font-family: 'Lora', serif;" v-if="calcPercentage(event) >= 100">Event is full <br/><v-progress-linear v-model="calcPercentage(event)" v-bind:color="getColor(event)"></v-progress-linear></v-card-text>
                                        <v-card-text style="font-family: 'Lora', serif;" v-else>@{{ event.registered_amount }} / @{{ event.capacity }} people has registered <br/><v-progress-linear v-model="calcPercentage(event)" v-bind:color="getColor(event)"></v-progress-linear></v-card-text>
                                    </v-flex>
                                    <v-flex xs7 style="border:1px solid black;" class="mt-3 text-xs-center">
                                        <v-card-text><b>Percentage of attendants</b></v-card-text>
                                        <v-progress-circular
                                                :size="150"
                                                :width="15"
                                                :rotate=-"-90"
                                                v-model="calcPercentage(event)"
                                                :color="getColor(event)"
                                        >
                                            @{{ calcPercentage(event) }}%
                                        </v-progress-circular>
                                    </v-flex>
                                    <v-flex xs4 style="border:1px solid black;" class="mt-4 ml-5 text-xs-center" v-if="event.code != '0000000'">
                                        <v-card-text style="font-family: 'Lora', serif;">
                                            <v-icon>&#xE32A;</v-icon>Event Code: 
                                            <br/>
                                            <br/>
                                            <b>@{{event.code}}</b>
                                            </v-card-text>
                                        
                                    </v-flex>
                                    <v-flex xs12 class="mt-3" v-if="type=='Private'">
                                        <v-card flat class="transparent">
                                            <v-card-title>
                                                <span style="font-family: 'Merriweather', serif;" class="headline">Attendants List</span>
                                                <v-spacer></v-spacer>
                                                <v-text-field append-icon="search" label="Enter any keyword to filter" single-line hide-details v-model="filter"></v-text-field>
                                            </v-card-title>
                                        </v-card>
                                        <v-data-table v-bind:headers="headers" v-bind:items="attendants" v-bind:search="filter" v-bind:pagination.sync="pagination" hide-actions class="elevation-1">
                                            <template slot="items" slot-scope="props">
                                                <td class="text-xs-center"><v-avatar><img :src=props.item.profile_picture></v-avatar></td>
                                                <td class="text-xs-right">@{{ props.item.firstname }}</td>
                                                <td class="text-xs-right">@{{ props.item.lastname }}</td>
                                                <td class="text-xs-right"><a href="#" v-on:click.prevent="getDetails(props.item)">View Details</a></td>
                                            </template>
                                        </v-data-table>
                                        <div class="text-xs-center pt-2">
                                            <v-pagination v-model="pagination.page" :length="pages" circle :total-visible="5"></v-pagination>
                                        </div>
                                        <v-dialog v-model="showDetails" max-width="400" persistent>
                                            <v-card>
                                                <v-container fluid>
                                                    <v-layout column wrap>
                                                        <v-flex xs12 class="text-xs-center">
                                                            <v-avatar size="100px"><img :src=tempDetails.icon></v-avatar>
                                                        </v-flex>
                                                        <v-flex xs12 class="text-xs-center">
                                                            <v-card-text>Full Name: @{{ fullname }}</v-card-text>
                                                        </v-flex>
                                                        <v-flex xs12 class="text-xs-center">
                                                            <v-card-text>Age: @{{ tempDetails.age }}</v-card-text>
                                                        </v-flex>
                                                        <v-flex xs12 class="text-xs-center">
                                                            <v-card-text>Date of Birth: @{{ tempDetails.dateofbirth }}</v-card-text>
                                                        </v-flex>
                                                        <v-flex xs12 class="text-xs-center" style="text-transform:capitalize;">
                                                            <v-card-text>Sex: @{{ tempDetails.sex }}</v-card-text>
                                                        </v-flex>
                                                        <v-flex xs12 class="text-xs-center">
                                                            <v-card-text>Social Media Link:</v-card-text>
                                                            <v-card-text>@{{ tempDetails.socialProfile }}</v-card-text>
                                                        </v-flex>
                                                        <v-flex xs12 class="text-xs-right">
                                                            <v-card-actions>
                                                                <v-spacer></v-spacer>
                                                                <v-btn color="green darken-1" flat @click="done">Done</v-btn>
                                                            </v-card-actions>
                                                        </v-flex>
                                                    </v-layout>
                                                </v-container>
                                            </v-card>
                                        </v-dialog>
                                    </v-flex>
                                    <v-flex xs12 class="mt-3">
                                        <h6>Your event URL:</h6>
                                        <v-text-field v-model="event.viewLink" readonly></v-text-field>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                        </v-flex>
                    </v-layout>
                </v-container>
                <v-container v-show="showResponse == 'edit'" fluid>
                    <v-layout row wrap>
                        <v-flex xs12 class="mt-3">
                            <v-form v-model="valid" ref="form" action="{{ url('/login') }}" method="post">
                                {{ csrf_field() }}
                                <v-card-text class="text-xs-center"><h4 class="mb-0"><b>1. Edit Event</b></h4></v-card-text>
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
                                        ref="autocomplete2"
                                        required
                                ></v-text-field>
                                <div
                                        id="hidden-map"
                                        hidden
                                ></div>
                                <v-container fluid class="transparent ma-0 pa-0">
                                    <v-layout row>
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
                                                    offset-y
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
                                <v-card-text class="text-xs-center"><h4 class="mb-0"><b>2. Event Settings</b></h4></v-card-text>
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
                                            <v-subheader><b>Event Type:</b></v-subheader>
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
                                                    ref="inputCapacity"
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
                                <v-radio-group v-model="tempEvent.layoutID" required>
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
                                <v-radio-group v-model="tempEvent.uniqueCode" required>
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
                                <div class="text-xs-center"> <v-btn round @click="FormSubmit" :class="{ green: valid, red: !valid }">Update</v-btn></div>
                               
                            </v-form>
                        </v-flex>
                    </v-layout>
                </v-container>
                <v-dialog v-model="success" persistent max-width="600">
                    <v-card>
                        <v-card-title class="headline">Congratulation! You've successfully updated an event</v-card-title>
                        <v-card-actions>
                          <v-spacer></v-spacer>
                          <v-btn :href="redirect" color="primary" flat >Awesome</v-btn>
                        </v-card-actions>
                      </v-card>
                </v-dialog>
            </v-flex>
        </v-layout>
    </v-container>
@stop

@section('script')
    <script>
    var event = <?php echo json_encode($event); ?>;
    var category = <?php echo json_encode($category); ?>;
    var type = <?php echo json_encode($event_type); ?>;
    var list = <?php echo json_encode($people_attend); ?>;
    var allCategories = <?php echo json_encode($all_categories); ?>;
    var allTypes = <?php echo json_encode($all_types); ?>;
    console.log(allCategories);
    console.log(allTypes);
    console.log(list);
    console.log(category);
    console.log(type);
    console.log(event);
    
    function initMAP() {
        const google = window.google;
        var uluru = {lat: 43.6532, lng: -79.3832};
        var map = new google.maps.Map(document.getElementById('hidden-map'), {
            zoom: 4,
            center: uluru
        });
        
        
        var element = vm.$refs.autocomplete2.$el
        
        element = element.querySelector('input');
        
        var autocomplete = new google.maps.places.Autocomplete(
        
            (element),
            
            {types: ['establishment','geocode']});
            
        google.maps.event.addListener(autocomplete, 'place_changed', function() {
            vm.tempEvent.location = autocomplete.getPlace().formatted_address;
            vm.tempEvent.latitude = autocomplete.getPlace().geometry.location.lat();
            vm.tempEvent.longtitude = autocomplete.getPlace().geometry.location.lng();
      
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
                includedGMap: false,
                m1: false,
                m2: false,
                m3: false,
                m4: false,
                success: false,
                showfullpic1: false,
                showfullpic2: false,
                type: type,
                category: category,
                allCategories: allCategories,
                allTypes: allTypes,
                EventCategories: [],
                EventTypes: [],
                search:'',
                fullDate: '',
                filter: '',
                showDetails: false,
                pagination: {rowsPerPage:5, page:1},
                drawer: true,
                valid: false,
                mini:false,
                right:null,
                tempDetails: {
                    icon: '',
                    firstname: '',
                    lastname: '',
                    dateofbirth: '',
                    sex: '',
                    socialProfile: ''
                },
                buttons: [
                    {
                        text: 'Home',
                        url: '/home'
                    },
                    {
                        text: 'Register',
                        url: '/register'
                    }],
                navigations: [
                    {
                        text: 'Event Dashboard',
                        actionID: 'dashboard',
                        icon: 'assessment'
                    },
                    {
                        text: 'Edit Event',
                        actionID: 'edit',
                        icon: 'create'
                    }],
                showResponse: 'dashboard',
                tempEvent: {
                    imgURL: '',
                    imageName: '',
                    title: '',
                    location: '',
                    capacity: 0,
                    startDate: '',
                    startTime: '',
                    endDate: '',
                    endTime: '',
                    description: '',
                    orgDescription: '',
                    price: 0,
                    registered_amount: 0,
                    category:'',
                    type: '',
                    layoutID: '',
                    uniqueCode: '',
                    latitude: 0,
                    longtitude: 0
                },
                event: event,
                headers: [
                    {
                        text: 'Icon',
                        sortable: false,
                        align: 'center',
                        value: 'icon'
                    },
                    {
                        text: 'First Name',
                        value: 'firstname'
                    },
                    {
                        text: 'Last Name',
                        value:'lastname'
                    },
                    {
                        text: 'Details',
                        sortable: false,
                        value: 'detailLink'
                    }],
                attendants: list,
                capacityRules: [],
                priceRules: [
                    (v) => (!isNaN(v) && v >= 0) || 'Your price should be a valid number' 
                ]
        },
        methods: {
            
            submit: function (e){
                axios.post('/api/submit',{
                    search:this.search
                })
            },
            getEvent: function (i){
                if(this.navigations[i].actionID == 'edit'){
                    // append google map script
                    if (!vm.includedGMap) {
                        vm.includedGMap = true;
                        var srt = document.getElementsByTagName('script')[0];
                        var script = document.createElement('script');
                        script.setAttribute('async', 'async');
                        script.setAttribute("defer", "defer");
                        script.src = "https://maps.googleapis.com/maps/api/js?key=AIzaSyDuP7giqQQp4O8FE8oL41qjWLyFlcv3Ws8&libraries=places&callback=initMAP";
                        script.type = 'text/javascript';
                        srt.appendChild(script);
                    // end append    
                        this.tempEvent.location = this.event.location;
                        this.tempEvent.latitude = this.event.lat;
                        this.tempEvent.longtitude = this.event.lng;
                        this.tempEvent.imgURL = this.event.background_photo
                        this.tempEvent.imageName = this.event.background_photo.replace("/assets/img/","")
                        this.tempEvent.title = this.event.title
                        this.tempEvent.registered_amount = this.event.registered_amount
                        this.tempEvent.capacity = this.event.capacity
                        this.tempEvent.startDate = this.event.startdate
                        this.tempEvent.startTime = this.event.starttime
                        this.tempEvent.endDate = this.event.enddate
                        this.tempEvent.endTime = this.event.endtime
                        this.tempEvent.description = this.event.description
                        this.tempEvent.orgDescription = this.event.organizer_description
                        this.tempEvent.price = this.event.price
                        this.tempEvent.category = this.category
                        this.tempEvent.type = this.type
                        this.tempEvent.layoutID = this.event.template
                        
                        this.tempEvent.uniqueCode = this.event.code == "0000000" ? "N" : "Y"
                    }
                }
                this.showResponse = this.navigations[i].actionID
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
                    return date.getDate()
                }
            },
            allowedStartHours: function(value){
                if(vm.tempEvent.endDate != null && vm.tempEvent.startDate != null){
                    if(vm.tempEvent.endDate == vm.tempEvent.startDate){
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
                    if(vm.tempEvent.endDate == vm.tempEvent.startDate){
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
            getStatus: function(d){
                var nowTime = Date.now()
                var comparison = this.compare(d,nowTime)
                var result = ""
                switch (comparison){
                    case -1:
                        result = "Past"
                        break
                    case 0:
                        result = "Ongoing"
                        break
                    case 1:
                        result = "Ongoing"
                        break
                    default:
                        result = "Invalid Date Input"
                        break
                }
                return result
            },
            getYear: function(d){
                var nowTime = Date.now()
                var result = isFinite(a=this.convertToDateObject(d).getYear()) && isFinite(b=this.convertToDateObject(nowTime).getYear()) ? (a < b ? b-a : "N/A") : NaN
                return result
            },
            calcPercentage: function(e){
                return Number(((e.registered_amount/e.capacity)*100).toFixed(2))
            },
            getColor: function(event){
                if(this.calcPercentage(event) <= 30){
                    return "green"
                }else if(this.calcPercentage(event) <= 60){
                    return "blue"
                }else {
                    return "red"
                }
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
            getDetails: function(ob){
                this.tempDetails.icon = ob.profile_picture
                this.tempDetails.firstname = ob.firstname
                this.tempDetails.lastname = ob.lastname
                this.tempDetails.age = ob.age
                this.tempDetails.dateofbirth = ob.DOB
                this.tempDetails.sex = ob.gender
                this.tempDetails.socialProfile = ob.social_network
                this.showDetails = true
            },
            done: function(){
                this.showDetails = false

            },
            FormSubmit: function () {
                if (this.$refs.form.validate()) {
                    var form = new FormData()
                    var imagefile = document.querySelector('#files')
                    console.log(imagefile.files[0]);
                    form.append("photo", imagefile.files[0]);
                    form.append("id", vm.event.id);
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
                    form.append("latitude",vm.tempEvent.latitude);
                    form.append("longitude",vm.tempEvent.longtitude);
                    form.append("uniqueCode",vm.tempEvent.uniqueCode);
                    const config = { headers: { 'Content-Type': 'multipart/form-data' } };
                    if (this.$refs.form.validate()) {
                        axios.post('/event/edit', form,config)
                          .then(function (response) {
    
                            if(response.data == 0){
                                vm.success=true
                            }
                          })
                          .catch(function (error) {
                            console.log(error);
                          });
                        
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
            }
        },
        computed:{
            shareLink: function(){
                return "https://php-project-willieduke.c9users.io" + this.event.viewLink
            },
            pages: function() {
                return this.pagination.rowsPerPage ? Math.ceil(this.attendants.length / this.pagination.rowsPerPage) : 0
            },
            fullname: function(){
                return this.tempDetails.firstname + " " + this.tempDetails.lastname
            },
            redirect: function(){
                return "/event/dashboard/" + event.id
            }
        },
        watch: {
            fileValue: function (fv){
                this.tempEvent.imageName = fv;
            }
        },
        updated: function(){
            for(var j = 0; j < this.attendants.length; j++){
                this.attendants[j].age = this.getYear(this.attendants[j].DOB)
            }
            
        },
        mounted: function() {
            this.event.status = this.getStatus(this.event.enddate + " " + this.event.endtime)
            var formats = {
                weekday: "long", year: "numeric", month: "short",
                day: "numeric", hour: "2-digit", minute: "2-digit"
            }
            
            this.fullDate = this.convertToDateObject(this.event.startdate + " " + this.event.starttime).toLocaleTimeString("en-us", formats)
            this.event.viewLink = window.location.href.replace("/dashboard","")
            this.tempEvent.imageName = this.fileValue
            for(var i = 0; i < this.allCategories.length; i++){
                this.EventCategories.push(this.allCategories[i].text)
            }
            for(var k = 0; k < this.allTypes.length; k++){
                this.EventTypes.push(this.allTypes[k].text)
            }
        
                     
            this.capacityRules.push((v) => !!v || 'Please enter the event capacity')
            this.capacityRules.push((v) => (!isNaN(v) && v <= 10000 && v >= vm.event.registered_amount) || 'Your capacity should be a number and within ' + vm.event.registered_amount +' to 5000')
            
        }
        })
    </script>
    <!--<script async defer-->
    <!--        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDuP7giqQQp4O8FE8oL41qjWLyFlcv3Ws8&libraries=places&callback=initMAP">-->
    <!--</script>-->

@stop
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
                                                    <span><h4>@{{ event.name }}</h4></span>
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
                <v-navigation-drawer style="z-index:0; position:relative;" :mini-variant.sync="mini" persistent floating light app v-model="drawer"  class="grey lighten-4 elevation-4" >
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
                <v-container v-if="showResponse == 'dashboard'" fluid>
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
                                        <v-card-text style="font-family: 'Lora', serif;" v-if="event.type=='public'">
                                            <v-icon>&#xE80B;</v-icon> Public
                                            <br/>
                                            <br/>
                                            Your event is made public and searchable.
                                        </v-card-text>
                                        <v-card-text style="font-family: 'Lora', serif;" class="mr-4" v-else>
                                            <v-icon>&#xE897;</v-icon> Private
                                            <br/>
                                            <br/>
                                            Attendees will receive a confirmation code.
                                        </v-card-text>
                                    </v-flex>
                                    <v-flex xs4 style="border:1px solid black;">
                                        <v-card-text style="font-family: 'Lora', serif;" v-if="calcPercentage(event) == 100">Event is full <br/><v-progress-linear v-model="calcPercentage(event)" v-bind:color="getColor(event)"></v-progress-linear></v-card-text>
                                        <v-card-text style="font-family: 'Lora', serif;" v-else>@{{ event.participant }} / @{{ event.capacity }} people has registered <br/><v-progress-linear v-model="calcPercentage(event)" v-bind:color="getColor(event)"></v-progress-linear></v-card-text>
                                    </v-flex>
                                    <v-flex xs7 style="border:1px solid black;" class="mt-3 text-xs-center">
                                        <v-card-text><b>Percentage of attendants</b></v-card-text>
                                        <v-progress-circular
                                                :size="100"
                                                :width="15"
                                                :rotate=-"-90"
                                                v-model="calcPercentage(event)"
                                                :color="getColor(event)"
                                        >
                                            @{{ calcPercentage(event) }}%
                                        </v-progress-circular>
                                    </v-flex>
                                    <v-flex xs12 class="mt-3" v-if="event.type=='private'">
                                        <v-card flat class="transparent">
                                            <v-card-title>
                                                <span style="font-family: 'Merriweather', serif;" class="headline">Attendants List</span>
                                                <v-spacer></v-spacer>
                                                <v-text-field append-icon="search" label="Enter any keyword to filter" single-line hide-details v-model="filter"></v-text-field>
                                            </v-card-title>
                                        </v-card>
                                        <v-data-table v-bind:headers="headers" v-bind:items="attendants" v-bind:search="filter" v-bind:pagination.sync="pagination" hide-actions class="elevation-1">
                                            <template slot="items" slot-scope="props">
                                                <td class="text-xs-center"><v-avatar><img :src=props.item.icon></v-avatar></td>
                                                <td class="text-xs-right">@{{ props.item.firstname }}</td>
                                                <td class="text-xs-right">@{{ props.item.lastname }}</td>
                                                <td class="text-xs-right">@{{ props.item.age }}</td>
                                                <td class="text-xs-right">@{{ props.item.sex }}</td>
                                            </template>
                                        </v-data-table>
                                        <div class="text-xs-center pt-2">
                                            <v-pagination v-model="pagination.page" :length="pages" circle :total-visible="5"></v-pagination>
                                        </div>
                                    </v-flex>
                                    <v-flex xs12 class="mt-3">
                                        <h6>Your event URL:</h6>
                                        <v-text-field v-model="shareLink" readonly autofocus></v-text-field>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                        </v-flex>
                    </v-layout>
                </v-container>
                <v-container v-if="showResponse == 'edit'" fluid>
                    <v-layout>
                        <v-flex>
                            <v-card-text>Hi</v-card-text>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-flex>
        </v-layout>
    </v-container>
@stop

@section('script')
    <script>
        new Vue({
            el: '#app',
            data: {
                search:'',
                fullDate: '',
                filter: '',
                pagination: {rowsPerPage:5, page:1},
                drawer: true,
                mini:false,
                right:null,
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
                event:{
                    name: 'Indie Game Hackathon',
                    location: '160 Kendal Avenue, Toronto, Ontario',
                    participant: 135,
                    capacity: 1000,
                    date: '2017/03/06 16:00',
                    viewLink: '/event/1',
                    status: '',
                    type: 'private'
                },
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
                        text: 'Age',
                        value: 'age'
                    },
                    {
                        text: 'Sex',
                        value: 'sex'
                    }],
                attendants: [
                    {
                        value:true,
                        icon: '/assets/img/myAvatar.png',
                        firstname: 'Huy',
                        lastname: 'Dam',
                        age: '20',
                        sex: 'twice a week'
                    },
                    {
                        value:true,
                        icon: '/assets/img/myAvatar.png',
                        firstname: 'Huy',
                        lastname: 'Dam',
                        age: '20',
                        sex: 'twice a week'
                    },
                    {
                        value:true,
                        icon: '/assets/img/myAvatar.png',
                        firstname: 'Huy',
                        lastname: 'Dam',
                        age: '20',
                        sex: 'twice a week'
                    },
                    {
                        value:true,
                        icon: '/assets/img/myAvatar.png',
                        firstname: 'Huy',
                        lastname: 'Dam',
                        age: '20',
                        sex: 'twice a week'
                    },
                    {
                        value:true,
                        icon: '/assets/img/myAvatar.png',
                        firstname: 'Huy',
                        lastname: 'Dam',
                        age: '20',
                        sex: 'twice a week'
                    },
                    {
                        value:true,
                        icon: '/assets/img/myAvatar.png',
                        firstname: 'Huy',
                        lastname: 'Dam',
                        age: '20',
                        sex: 'twice a week'
                    },
                    {
                        value:true,
                        icon: '/assets/img/myAvatar.png',
                        firstname: 'Huy',
                        lastname: 'Dam',
                        age: '20',
                        sex: 'twice a week'
                    },
                    {
                        value:true,
                        icon: '/assets/img/myAvatar.png',
                        firstname: 'Huy',
                        lastname: 'Dam',
                        age: '20',
                        sex: 'twice a week'
                    }]
            },
            methods: {
                submit: function (e){
                    axios.post('/api/submit',{
                        search:this.search
                    })
                },
                getEvent: function (i){
                    this.showResponse = this.navigations[i].actionID
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
                calcPercentage: function(e){
                    return (e.participant/e.capacity)*100
                },
                getColor: function(event){
                    if(this.calcPercentage(event) <= 30){
                        return "green"
                    }else if(this.calcPercentage(event) <= 60){
                        return "blue"
                    }else {
                        return "red"
                    }
                }
            },
            computed:{
                shareLink: function(){
                    return "https://php-project-willieduke.c9users.io" + this.event.viewLink
                },
                pages: function() {
                    return this.pagination.rowsPerPage ? Math.ceil(this.attendants.length / this.pagination.rowsPerPage) : 0
                }
            },
            mounted: function() {
                this.event.status = this.getStatus(this.event.date)
                var formats = {
                    weekday: "long", year: "numeric", month: "short",
                    day: "numeric", hour: "2-digit", minute: "2-digit"
                };
                this.fullDate = this.convertToDateObject(this.event.date).toLocaleTimeString("en-us", formats)
            }
        })
    </script>
@stop
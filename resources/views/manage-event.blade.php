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
    </style>
@stop

@section('body')
    <v-container class="mt-4 pt-4">
        <v-layout align-center column>
            <v-flex xs12 class="text-xs-center">
                <h4 style="font-family: 'Merriweather', serif;"><b>Manage Events</b></h4>
            </v-flex>
            <v-flex xs12>
                <v-tabs light v-model="active" :scrollable="false">
                    <v-tabs-bar class="transparent" style="border-bottom: 2px solid black; margin-bottom:0.25px;">
                        <v-tabs-item v-for="tab in tabs" :key="tab" :href="'#' + tab" ripple>
                            @{{ tab }}
                        </v-tabs-item>
                        <v-tabs-slider color="blue" style="height:4px;"></v-tabs-slider>
                    </v-tabs-bar>
                    <hr/>
                    <v-tabs-items>
                        <v-tabs-content id="Created" style="min-width:40vw; max-width:90vw;">
                            <v-card flat class="transparent">
                                <v-card-title>
                                    <span style="font-family: 'Merriweather', serif;" class="headline">Event Log</span>
                                    <v-spacer></v-spacer>
                                    <v-text-field append-icon="search" label="Enter any keyword to filter" single-line hide-details v-model="filter"></v-text-field>
                                </v-card-title>
                            </v-card>
                            <v-data-table v-bind:headers="headers" v-bind:items="items" v-bind:search="filter" v-bind:pagination.sync="pagination" hide-actions class="elevation-1"  >
                                <template slot="items" slot-scope="props">
                                    <td>@{{ props.item.title }}</td>
                                    <td class="text-xs-right">@{{ props.item.registered_amount }}</td>
                                    <td class="text-xs-right">@{{ props.item.capacity }}</td>
                                    <td class="text-xs-right">@{{ props.item.startdate }}</td>
                                    <td class="text-xs-right"><a :href=linkDashboard(props.item)>Manage</a></td>
                                    <td class="text-xs-right"><a :href=link(props.item)>View</a></td>
                                    <td class="text-xs-right"><a href="" v-on:click.prevent="deleteEvent(props.item)">Delete</a></td>
                                    <td class="green--text text-xs-right" v-if="props.item.status == 'Ongoing'">@{{ props.item.status }}</td>
                                    <td class="red--text text-xs-right" v-else>@{{ props.item.status }}</td>
                                </template>
                            </v-data-table>
                            <div class="text-xs-center pt-2">
                                <v-pagination v-model="pagination.page" :length="pages" circle :total-visible="5"></v-pagination>
                            </div>
                            <v-dialog v-model="confirmModal" persistent max-width="500">
                                <v-card>
                                    <v-card-title class="text-xs-center headline">Are you sure you want to delete this event?</v-card-title>
                                    <v-card-actions>
                                        <v-spacer></v-spacer>
                                      <v-btn color="primary" flat @click.native="decline">No</v-btn>
                                      <v-btn color="primary" flat @click.native="accept">Yes</v-btn>
                                    </v-card-actions>
                                  </v-card>
                            </v-dialog>
                        </v-tabs-content>
                        <v-tabs-content id="Attended" class="transparent" style="min-width:40vw; max-width:50vw;">
                            <v-layout column class="mt-4" align-center>
                                <v-flex xs12 v-for="(attend,i) in attends[attendedPagination.attendPage-1]" :key="i" class="mb-3">
                                    <v-card flat class="mb-2 grey lighten-2">
                                        <v-card-title class="ma-0 pa-0">
                                            <v-container class="transparent ma-0">
                                                <v-layout row wrap>
                                                    <v-flex xs7 style="width: 40vw;">
                                                        <div class="headline text-xs-left pl-1" style="font-family: 'Cinzel', serif;"><b>@{{ attend.title }}</b></div>
                                                        <div class="text-xs-left ma-0 pa-1" style="font-family: 'Lora', serif;">@{{ attend.startdate }}&nbsp; @{{attend.starttime}}</div>
                                                        <div class="text-xs-left ma-0 pa-1" style="font-family: 'Lora', serif;" v-if="attend.code != '0000000'">Event Code: <b>@{{attend.code}}</b></div>
                                                    </v-flex>
                                                    <v-flex xs5>
                                                        <v-card-text style="font-family: 'Lora', serif;" v-if="calcPercentage(attend) == 100"><v-progress-linear v-model="calcPercentage(attend)" v-bind:color="getColor(attend)"></v-progress-linear> Event is full</v-card-text>
                                                        <v-card-text style="font-family: 'Lora', serif;" v-else><v-progress-linear v-model="calcPercentage(attend)" v-bind:color="getColor(attend)"></v-progress-linear> @{{ attend.registered_amount }} / @{{ attend.capacity }} people has registered</v-card-text>
                                                    </v-flex>
                                                </v-layout>
                                            </v-container>
                                        </v-card-title>
                                        <v-card-actions class="ma-0 pa-0">
                                            <v-btn class="ma-0 pa-0" :href=link(attend) flat color="blue">View</v-btn>
                                        </v-card-actions>
                                    </v-card>
                                </v-flex>
                                <div class="text-xs-center pt-2">
                                    <v-pagination v-model="attendedPagination.attendPage" v-bind:length="attendPages" circle :total-visible="7"></v-pagination>
                                </div>
                            </v-layout>
                        </v-tabs-content>
                    </v-tabs-items>
                </v-tabs>
            </v-flex>
        </v-layout>
    </v-container>
@stop

@section('script')
    <script>
    //attended events list
    var attended = <?php echo json_encode($attended); ?>;
    //created events list
    var created = <?php echo json_encode($created); ?>;
        var vm = new Vue({
            el: '#app',
            data: {
                //filtering for search options
                filter:'',
                //modals to confirm
                confirmModal: false,
                //confirm variables
                confirm: false,
                //event that is pending to be executed
                pendingEvent: {},
                //pagination for attended
                attendedPagination:{attendRowsPerPage: 5, attendPage:1},
                pagination: {rowsPerPage:5, page:1},
                active:null,
                tabs: ['Created','Attended'],
                //data tables headers
                headers: [
                    {
                        text: 'Event Name',
                        align: 'left',
                        value: 'name'
                    },
                    {
                        text: 'Attended Amount',
                        value:'attend'
                    },
                    {
                        text: 'Capacity',
                        value: 'capacity'
                    },
                    {
                        text: 'Date',
                        value: 'date'
                    },
                    {
                        text: 'Manage',
                        sortable: false,
                        value: 'manageLink'
                    },
                    {
                        text: 'View',
                        sortable: false,
                        value: 'viewLink'
                    },
                    {
                        text: 'Delete',
                        sortable: false,
                        value: 'deleteLink'
                    },
                    {
                        text: 'Status',
                        value: 'status'
                    }],
                items: created,
                attends: attended
            },
            methods: {
                //convert date-time object
                convertToDateObject : function (dateString){
                    return (
                        dateString.constructor === Date ? dateString :
                            dateString.constructor === Number ? new Date(dateString) :
                                dateString.constructor === String ? new Date(dateString) :
                                    typeof dateString === "object" ? new Date(dateString.year, dateString.month, dateString.date) :
                                        NaN
                    )
                },
                //refuse to delete event
                decline: function(){
                    this.confirm = false
                    this.confirmModal = false
                },
                //accept to delete event
                accept: function(){
                    this.confirm = true
                    this.confirmModal = false
                },
                //delete event
                deleteEvent: function(item){
                    this.pendingEvent = item
                    this.confirmModal = true
                },
                //compare dates
                compare: function(a,b){
                    return (
                        isFinite(a=this.convertToDateObject(a).valueOf()) &&
                        isFinite(b=this.convertToDateObject(b).valueOf()) ?
                            (a>b)-(a<b):
                            NaN
                    )
                },
                //get the status of the event according to compare method
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
                //return percentage for progress bar
                calcPercentage: function(e){
                    return (e.registered_amount/e.capacity)*100
                },
                //return color for progress bar
                getColor: function(event){
                    if(this.calcPercentage(event) <= 30){
                        return "green"
                    }else if(this.calcPercentage(event) <= 60){
                        return "blue"
                    }else {
                        return "red"
                    }
                },
                link: function(event) {
                    return '/event/'+ event.id
                },
                linkDashboard: function(event){
                    return '/event/dashboard/'+event.id
                }

            },
            computed: {
                //computing page for pagination
                pages: function() {
                    return this.pagination.rowsPerPage ? Math.ceil(this.items.length / this.pagination.rowsPerPage) : 0
                },
                attendPages: function(){
                    return this.attends.length
                }

            },
            watch: {
                //confirm to delete
                confirm: function(isConfirm){
                    console.log(isConfirm)
                    if(isConfirm == true){
                        axios.post('/event/delete',{
                            id: this.pendingEvent.id
                        })
                        .then(function(response){
                            vm.pendingEvent = {}
                            this.confirm = false
                            console.log(response.data)
                            if(response.data == 0){
                                window.location.reload(true)
                            }
                            
                        })
                        .catch(function(error){
                            console.log(error)
                        })
                    }
                }
            },
            updated: function() {
                //updating status
                for(var i = 0; i < this.items.length; i++){
                    this.items[i].status = this.getStatus(this.items[i].enddate + " " + this.items[i].endtime)
                }
            }
        })
    </script>
@stop
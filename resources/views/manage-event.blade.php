@extends('master')

@section('style')
  <style type="text/css">
    a {
      text-decoration:none;
    }
    #title{
      color:red;
    }
  </style>
@stop

@section('body')
  <v-container class="mt-4 pt-4">
    <v-layout align-center justify-center column wrap >
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
                <v-tabs-content id="Created">
                    <v-card flat class="transparent">
                        <v-card-title>
                            <span style="font-family: 'Merriweather', serif;" class="headline">Event Log</span>
                            <v-spacer></v-spacer>
                            <v-text-field append-icon="search" label="Enter any keyword to filter" single-line hide-details v-model="filter"></v-text-field>
                        </v-card-title>
                    </v-card>
                        <v-data-table v-bind:headers="headers" v-bind:items="items" v-bind:search="filter" v-bind:pagination.sync="pagination" hide-actions class="elevation-1">
                            <template slot="items" slot-scope="props">
                                <td>@{{ props.item.name }}</td>
                                <td class="text-xs-right">@{{ props.item.attend }}</td>
                                <td class="text-xs-right">@{{ props.item.capacity }}</td>
                                <td class="text-xs-right">@{{ props.item.date }}</td>
                                <td class="text-xs-right"><a :href=props.item.manageLink>Manage</a></td>
                                <td class="text-xs-right"><a :href=props.item.viewLink>View</a></td>
                                <td class="green--text text-xs-right" v-if="props.item.status == 'Ongoing'">@{{ props.item.status }}</td>
                                <td class="red--text text-xs-right" v-else>@{{ props.item.status }}</td>
                            </template>
                        </v-data-table>
                        <div class="text-xs-center pt-2">
                            <v-pagination v-model="pagination.page" :length="pages" circle :total-visible="5"></v-pagination>
                        </div>
                    
                </v-tabs-content>
                <v-tabs-content id="Attended" class="transparent">
                    <v-layout column wrap class="mt-4" align-center>
                      <v-flex xs12 v-for="(attend,i) in attends" :key="i" class="mb-3">
                        <v-card raised class="mb-2">
                          <v-card-title class="ma-0 pa-0">
                            <v-container class="transparent ma-0">
                              <v-layout row wrap>
                                <v-flex xs7 style="width: 40vw;">
                                  <div class="headline text-xs-left pl-1" style="font-family: 'Cinzel', serif;"><b>@{{ attend.title }}</b></div>
                                  <div class="text-xs-left ma-0 pa-1" style="font-family: 'Lora', serif;">@{{ attend.date }}</div>
                               </v-flex>
                               <v-flex xs5>
                                  <v-card-text style="font-family: 'Lora', serif;" v-if="calcPercentage(attend) == 100"><v-progress-linear v-model="calcPercentage(attend)" v-bind:color="getColor(attend)"></v-progress-linear> Event is full</v-card-text>
                                  <v-card-text style="font-family: 'Lora', serif;" v-else><v-progress-linear v-model="calcPercentage(attend)" v-bind:color="getColor(attend)"></v-progress-linear> @{{ attend.participant }} / @{{ attend.capacity }} people has registered</v-card-text>
                              </v-flex>
                              </v-layout>
                            </v-container>
                          </v-card-title>
                          <v-card-actions class="ma-0 pa-0">
                            <v-btn class="ma-0 pa-0" :href=attend.url flat color="blue">View</v-btn>
                          </v-card-actions>
                        </v-card>
                      </v-flex>
                      <div class="text-xs-center pt-2">
                        <v-pagination v-model="attendedPagination.attendPage" v-bind:length="attendPages" circle :total-visible="5"></v-pagination>
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
  new Vue({
      el: '#app',
      data: {
          filter:'',
          attendedPagination:{attendRowsPerPage: 5, attendPage:1},
          pagination: {rowsPerPage:5, page:1},
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
          active:null,
          tabs: ['Created','Attended'],
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
                  text: 'Status',
                  value: 'status'
              }],
          items:[
              {
                value: true,
                name: 'Indie Game Hackathon',
                attend: 100,
                capacity: 1000,
                date: '2017/03/06',
                manageLink: '/event/dashboard/1',
                viewLink: '/event/1',
                status: ''
              },
              {
                value: false,
                name: 'Netflix and Chill',
                attend: 50,
                capacity: 50,
                date: '2018/05/01',
                manageLink: '/event/dashboard/2',
                viewLink: '/event/2',
                status: ''
              },
              {
                value: false,
                name: 'Indie Game Hackathon',
                attend: 100,
                capacity: 1000,
                date: '2017/03/06',
                manageLink: '/event/dashboard/1',
                viewLink: '/event/1',
                status: ''
              },
              {
                value: false,
                name: 'Netflix and Chill',
                attend: 50,
                capacity: 50,
                date: '2018/05/01',
                manageLink: '/event/dashboard/2',
                viewLink: '/event/2',
                status: ''
              },
              {
                value: false,
                name: 'Indie Game Hackathon',
                attend: 100,
                capacity: 1000,
                date: '2017/03/06',
                manageLink: '/event/dashboard/1',
                viewLink: '/event/1',
                status: ''
              },
              {
                value: false,
                name: 'Netflix and Chill',
                attend: 50,
                capacity: 50,
                date: '2018/05/01',
                manageLink: '/event/dashboard/2',
                viewLink: '/event/2',
                status: ''
              },
              {
                value: false,
                name: 'Indie Game Hackathon',
                attend: 100,
                capacity: 1000,
                date: '2017/03/06',
                manageLink: '/event/dashboard/1',
                viewLink: '/event/1',
                status: ''
              },
              {
                value: false,
                name: 'Netflix and Chill',
                attend: 50,
                capacity: 50,
                date: '2018/05/01',
                manageLink: '/event/dashboard/2',
                viewLink: '/event/2',
                status: ''
              }],
          attends: [
              {
                url: '/event/1',
                date: 'November 11, 2017 19:00',
                title: 'Indie Game Hackathon',
                participant: 50,
                capacity: 1000
              },
              {
                url: '/event/2',
                date: 'November 11, 2017 19:00',
                title: 'Indie Game Hackathon',
                participant: 150,
                capacity: 1000
              },
              {
                url: '/event/3',
                date: 'November 11, 2017 19:00',
                title: 'Indie Game Hackathon',
                participant: 350,
                capacity: 1000
              },
              {
                url: '/event/3',
                date: 'November 11, 2017 19:00',
                title: 'Indie Game Hackathon',
                participant: 350,
                capacity: 1000
              },
              {
                url: '/event/3',
                date: 'November 11, 2017 19:00',
                title: 'Indie Game Hackathon',
                participant: 350,
                capacity: 1000
              },
              {
                url: '/event/3',
                date: 'November 11, 2017 19:00',
                title: 'Indie Game Hackathon',
                participant: 350,
                capacity: 1000
              }]
  },
  methods: {
      submit: function (e){
          axios.post('/api/submit',{
              search:this.search
          })
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
  computed: {
      pages: function() {
          return this.pagination.rowsPerPage ? Math.ceil(this.items.length / this.pagination.rowsPerPage) : 0
      },
      attendPages: function(){
          return this.attendedPagination.attendRowsPerPage ? Math.ceil(this.attends.length / this.attendedPagination.attendRowsPerPage) : 0
      }
      
  },
  mounted: function() {
      for(var i = 0; i < this.items.length; i++){
          this.items[i].status = this.getStatus(this.items[i].date)
      }
  }
})
</script>
@stop
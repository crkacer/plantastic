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
<v-container fluid class="grey lighten-4">
<v-layout row>
  <v-flex d-flex sm3 class="mr-5">
    <v-layout column>
      <v-flex d-flex>
        <v-card class="grey lighten-4" tile flat >
          <v-card-text>Google map API here</v-card-text>
        </v-card>
      </v-flex>
      <v-expansion-panel style="height:4vh;" focusable>
        <v-expansion-panel-content>
          <div slot="header">Category</div>
          <v-card>
            <v-card-text v-for="category in categories" :key="category">
              @{{ category.text }}
            </v-card-text>
          </v-card>
        </v-expansion-panel-content>
        <v-expansion-panel-content>
          <div slot="header">Types</div>
          <v-card>
            <v-card-text v-for="type in types" :key="type">
              @{{ type.text }}
            </v-card-text>
          </v-card>
        </v-expansion-panel-content>
      </v-expansion-panel>
  </v-flex>
  <v-flex d-flex sm12 class="ml-5">
    <v-layout column wrap>
      <v-flex d-flex class="mb-5">
        <v-card class="indigo" dark tile flat>
          <v-card-text>Website Description</v-card-text>
        </v-card>
      </v-flex>
      <v-flex d-flex>
        <v-layout column wrap>
          <v-flex d-flex>
            <v-card class="amber lighten-2" tile flat>
              <v-card-text>Event Cards here</v-card-text>
            </v-card>
          </v-flex>
          <v-flex d-flex>
            <div class="text-xs-center">
              <v-pagination :length="4" v-model="page" circle></v-pagination>
            </div>
          </v-flex>
        </v-layout>
      </v-flex>
    </v-layout>
  </v-flex>
</v-layout>
</v-container>
@stop

@section('script')
 <script>
  new Vue({
  el: '#app',
  data () {
      return {
        page: 1,
        search:'',
        buttons: [
          {
            text: 'Home',
            url: '/'
          },
          {
            text: 'Register',
            url: '/'
          }],
        categories: [
          {
            text: 'Conference',
            url: '/'
          },
          {
            text: 'Seminar',
            url: '/'
          },
          {
            text: 'Career Fair',
            url: '/'
          }],
        types: [
          {
            text: 'Private',
            url: '/'
          },
          {
            text: 'Public',
            url: '/'
          }]
      }
    },
  methods: {
      submit: function (e){
        axios.post('/api/submit',{
          search:this.search
        })
      }
  }
})
</script>
@stop
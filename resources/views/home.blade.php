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
        <v-card class="blue grey" dark tile flat>
          <v-card-text>Google map API here</v-card-text>
        </v-card>
      </v-flex>
      <v-flex d-flex>
        <v-card class="blue" dark tile flat>
          <v-card-text>Event Type Expansion Panel</v-card-text>
        </v-card>
      </v-flex>
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
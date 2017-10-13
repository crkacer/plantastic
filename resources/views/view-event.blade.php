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

</v-container>
@stop

@section('script')
 <script>
  new Vue({
  el: '#app',
  data () {
      return {
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
  }
})
</script>
@stop
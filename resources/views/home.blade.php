@extends('master')

@section('style')
<style type="text/css">
a {
  text-decoration:none;
}
#title{
  color:red;
}
#description{
  font-family: 'Arial';
}

#map {
  height: 400px;
}
#container{
  padding-right:30px;
  padding-left:30px;
}
#footer{
  margin-top:400px;
}
</style>
@stop

@section('body')
<main>
  <v-content>
    <section>
        <v-container fluid class="transparent" id="container">
        <v-layout row>
          <v-flex sm3 class="mr-5">
            <v-layout column>
              <v-flex>
                <v-card class="transparent" tile flat >
                  <v-card-text id="map"></v-card-text>
                </v-card>
              </v-flex>
              <v-expansion-panel style="height:4vh; box-shadow:none;" focusable >
                <v-expansion-panel-content>
                  <div slot="header">Category</div>
                  <v-card>
                    <v-card-text v-for="(category,i) in categories" :key="i">
                      <a :href=category.url>@{{ category.text }}</a>
                    </v-card-text>
                  </v-card>
                </v-expansion-panel-content>
                <v-expansion-panel-content>
                  <div slot="header">Types</div>
                  <v-card>
                    <v-card-text v-for="(type,k) in types" :key="k">
                      @{{ type.text }}
                    </v-card-text>
                  </v-card>
                </v-expansion-panel-content>
              </v-expansion-panel>
          </v-flex>
          <v-flex sm12 class="ml-5">
            <section>
            <v-layout column wrap>
              <v-flex d-flex class="mb-5">
                <v-card class="transparent" tile flat>
                  <v-card-text class="text-xs-center pb-0 mb-0"><h4><b>SIMPLIFYING EVENTS</b></h4></v-card-text>
                  <v-card-text id="description"><span style="color:red;font-size:2.5em; font-weight:bold;">Plantastic</span> <span style="font-size:1.25em;">acts as a brand new, efficient and interactive event creation tool for George Brown College Faculty and Students. Our main goal is to <b>facilitate the event creation and management process</b> while creating and maintaining a high quality internal social network.</span></v-card-text>
                </v-card>
              </v-flex>
              <v-flex xs12 v-for="(event,i) in events" :key="i">
                <v-card raised>
                  <v-container class="white ma-0 pa-0" fluid>
                    <v-layout column>
                      <v-flex xs12>
                        <v-layout class="ma-0 pa-0" row>
                          <v-flex xs4>
                            <a :href=event.url>
                            <v-card-media :src= event.src height="125px" contain></v-card-media>
                            </a>
                          </v-flex>
                          <v-flex xs7>
                              <div class="text-xs-left ma-0 pa-1">@{{ event.date }}</div>
                              <div class="headline text-xs-left pl-1">@{{ event.title }}</div>
                              <div class="text-xs-left ma-0 pa-1">@{{ event.locationName }}</div>
                          </v-flex>
                          <v-flex xs6>
                                <v-card-text><v-progress-linear v-model="calcPercentage(event)"></v-progress-linear>@{{ event.participant }}/@{{ event.capacity }} people has registered this event</v-card-text>
                          </v-flex>
                        </v-layout>
                      </v-flex>
                      <v-flex xs12>
                        <v-layout row>
                          <v-flex d-flex class="text-xs-center grey lighten-3" xs4>
                            <v-card-text class=" pt-1">@{{ event.price }}</v-card-text>
                          </v-flex> 
                          <v-flex class="text-xs-left" style="border-top-style:solid; border-top-width:1px" xs7>
                            <a v-for="(tag,j) in event.tags" :key="j" :href= tag.link class="pa-1 ma-1">@{{ tag.name }}</a>
                          </v-flex>
                          <v-flex d-flex class="text-xs-right" style="border-top-style:solid; border-top-width:1px"  xs6>
                            <v-tooltip bottom>
                              <v-btn slot="activator" icon @click.native.stop="getShareLink(i)"><v-icon>share</v-icon></v-btn>
                              <span>Share</span>
                            </v-tooltip>
                          </v-flex>
                        </v-layout>
                      </v-flex>
                    </v-layout>
                  </v-container>
                  <v-dialog v-model="share" max-width="400" persistent>
                    <v-card>
                      <v-container fluid>
                        <v-layout column wrap>
                          <v-flex xs12>
                            <v-card-title class="headline">Copy this link to share</v-card-title>
                          </v-flex>
                          <v-flex xs12>
                            <v-text-field v-model="shareLink" readonly autofocus></v-text-field>
                          </v-flex>
                          <v-flex xs12>
                            <v-card-actions>
                              <v-spacer></v-spacer>
                              <v-btn color="green darken-1" flat="flat" @click="share=false">Done</v-btn>
                            </v-card-actions>
                          </v-flex>
                        </v-layout>
                      </v-container>
                    </v-card>
                  </v-dialog>
                </v-card>
              </v-flex>
              <v-flex>
                <div class="text-xs-center">
                  <v-pagination :length="4" v-model="page" circle></v-pagination>
                </div>
              </v-flex>
            </v-layout>
            </section>
          </v-flex>
        </v-layout>
      </v-container>
    </section>
  </v-content>
</main>


@stop

@section('script')

 <script>
  var allType = <?php echo json_encode($event_type); ?>;
  var allCategory = <?php echo json_encode($category); ?>;

  function initMap() {
        var uluru = {lat: 43.6532, lng: -79.3832};
        var map = new google.maps.Map(document.getElementById('map'), {
          zoom: 4,
          center: uluru
        });
        var marker = new google.maps.Marker({
          position: uluru,
          map: map
        });
      }

  new Vue({
  el: '#app',
  data () {
      return {
        shareLink:'',
        share: false,
        page: 1,
        search:'',
        categories: allCategory,
        types: allType,
        buttons: [
          {
            text: 'Home',
            url: '/home'
          },
          {
            text: 'Register',
            url: '/register'
          }],
        events: [
          {
            url: '/event/1',
            src: '/assets/img/dummyEvent.jpg',
            date: 'November 11, 2017',
            title: 'Indie Game Hackathon',
            locationName: 'George Brown College (Casa Loma Campus)',
            participant: 50,
            capacity: 1000,
            price: 'Free',
            tags: [
              {
                name: '#private',
                link: '/home'
              },
              {
                name: "#conference",
                link: '/home'
              }]
          },
          {
            url: '/event/3',
            src: '/assets/img/dummyEvent.jpg',
            date: 'November 11, 2017',
            title: 'Indie Game Hackathon',
            locationName: 'George Brown College (Casa Loma Campus)',
            participant: 500,
            capacity: 1000,
            price: 'Free',
            tags: [
              {
                name: '#private',
                link: '/home'
              },
              {
                name: "#conference",
                link: '/home'
              }]            
          }]
      }
    },
  methods: {
      submit: function (e){
        axios.post('/api/submit',{
          search:this.search
        })
      },
      calcPercentage: function(e){
        return (e.participant/e.capacity)*100
      },
      getShareLink: function(index){
        this.shareLink = "https://php-project-willieduke.c9users.io" + this.events[index].url
        this.share = true
      }
  }
})
</script>

<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDuP7giqQQp4O8FE8oL41qjWLyFlcv3Ws8&callback=initMap">
</script>

@stop
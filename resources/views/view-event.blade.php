@extends('master')

@section('style')
<style type="text/css">
a {
  text-decoration:none;
}
#title{
  color:red;
}
#container{
  height:300px;
}
#map{
  height:400px;
}
.carousel{
  box-shadow:none;
}
</style>

@stop

@section('body')
<main>
  <v-content>
    <img src="/assets/img/parallax.png" style="width:100vw;height:500px;position:absolute; z-index:0;">
    </img>
    <section>
        <v-container class="white ml-7 mr-7 mb-0 pa-0" style="position:relative; z-index:1; margin-top:100px;">
          <v-layout column wrap>
            <v-flex xs12>
              <v-layout row wrap>
                <v-flex xs8 class="yellow" style="height:400px; border-right-width:1px; border-right-style:solid;">
                  <v-card-text>Picture here</v-card-text>
                </v-flex>
                <v-layout column>
                  <v-flex xs12 class="blue">
                    <v-card class="transparent" tile flat>
                      <v-card-text>Date</v-card-text>
                    </v-card>
                  </v-flex>
                  <v-flex xs12 class="yellow darken-2">
                    <v-card-text>Name of the event</v-card-text>
                  </v-flex>
                  <v-flex xs12 class="purple darken-2">
                    <v-card-text>Price</v-card-text>
                  </v-flex>
                </v-layout>
                <v-flex xs8 class="text-xs-left" style="border-top-style:solid; border-top-width:1px;border-bottom-style:solid;border-bottom-width:1px">
                  <v-tooltip bottom>
                    <v-btn slot="activator" icon><v-icon>share</v-icon></v-btn>
                    <span>Share</span>
                  </v-tooltip>
                </v-flex>
                <v-flex xs4 class="text-xs-center" style="border-top-style:solid; border-top-width:1px;border-bottom-style:solid;border-bottom-width:1px">
                  <v-card class="transparent" tile flat>
                    <v-btn tile color="green" style="width:90%;">Join Event</v-btn>
                  </v-card>
                </v-flex>
                <v-flex xs8>
                  <v-layout column>
                    <v-flex xs12 class="green text-xs-center pa-3" style="height:400px;">
                      <v-card-text>Description Here</v-card-text>
                    </v-flex>
                    <v-flex xs12 class="text-xs-left pa-3">
                      <v-card-text>Tags Here</v-card-text>
                    </v-flex>
                  </v-layout>
                </v-flex>
                <v-layout column>
                  <v-flex xs12 class="purple text-xs-left pl-1 pt-3">
                    <v-card-text>Date and Time</v-card-text>
                  </v-flex>
                  <v-flex xs12 class="text-xs-left pl-1 pt-3">
                    <v-card-text>Location</v-card-text>
                  </v-flex> 
                </v-layout>
                <v-flex xs12>
                    <hr style="text-align:center; margin: 0 auto; border-top: 1px solid #8c8b8b;" width="80%">
                </v-flex>
                <v-flex xs12 class="text-xs-center pa-3">
                  <v-card-text>Creator Profile</v-card-text>
                </v-flex>
                <v-flex xs12>
                  <v-card-text id="map"></v-card-text>
                </v-flex>
              </v-layout>
            </v-flex>
          </v-layout>
        </v-container>
        <v-container fluid class="transparent">
          <v-layout column align-center justify-center>
            <v-flex xs12 class="mt-5">
              <h5><b>Other Events You May Like</b></h5>
            </v-flex>
          </v-layout>
          <v-carousel hide-controls light>
            <v-carousel-item v-for="(suggestion,i) in suggestions" src="" :key="i">
              <v-container>
                <v-layout row wrap>
                  <v-flex xs4 v-for="(event,k) in suggestion" :key="k">
                    <v-card raised class="mr-3">
                        <a :href= event.url>
                          <v-card-media  style="border-bottom: solid 1px #E8E8E8;" class="text-xs-right" height="200px" :src= event.src contain>
                            <v-container fill-height fluid>
                              <v-layout fill-height>
                                <v-flex xs12 align-end flexbox>
                                  <span class="white black--text">@{{ event.price }}</span>
                                </v-flex>
                              </v-layout>
                            </v-container>
                          </v-card-media>
                        </a>
                        <v-card-title>
                          <div>
                            <span class="grey--text">@{{ event.date }}</span><br>
                            <span><b>@{{ event.title }}</b></span><br>
                            <span class="grey--text">@{{ event.locationName }}</span>
                          </div>
                        </v-card-title>
                        <v-card-actions class="ma-0 pa-0">
                          <v-container class="ma-0 pa-0">
                            <v-layout row wrap>
                              <v-flex class="text-xs-left" style="border-top: solid 1px #E8E8E8;" xs10>
                                <a v-for="tag in event.tags" :href= tag.link class="pa-2">@{{ tag.name }}</a>
                              </v-flex>
                              <v-flex class="text-xs-center" style="border-top: solid 1px #E8E8E8; border-left: solid 1px #E8E8E8;" xs2>
                                <v-tooltip bottom>
                                  <v-btn slot="activator" icon><v-icon>share</v-icon></v-btn>
                                  <span>Share</span>
                                </v-tooltip>
                              </v-flex>
                            </v-layout>
                          </v-container>
                        </v-card-actions>
                    </v-card>
                  </v-flex>
                </v-layout>
              </v-container>
            </v-carousel-item>  
          </v-carousel>
        </v-container>
    </section>
  </v-content>
</main>
@stop

@section('script')
 <script>
 
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
        suggestions: [
          [
            {
              url: '/event/1',
              src: '/assets/img/dummyEvent.jpg',
              date: 'November 11, 2017',
              title: 'Indie Game Hackathon',
              locationName: 'George Brown College (Casa Loma Campus)',
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
              url: '/event/1',
              src: '/assets/img/dummyEvent.jpg',
              date: 'November 11, 2017',
              title: 'Indie Game Hackathon',
              locationName: 'George Brown College (Casa Loma Campus)',
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
              url: '/event/1',
              src: '/assets/img/dummyEvent.jpg',
              date: 'November 11, 2017',
              title: 'Indie Game Hackathon',
              locationName: 'George Brown College (Casa Loma Campus)',
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
            }
          ],
          //Next Carousel
          [ 
            {
              url: '/event/1',
              src: '/assets/img/dummyEvent.jpg',
              date: 'November 11, 2017',
              title: 'Indie Game Hackathon',
              locationName: 'George Brown College (Casa Loma Campus)',
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
              url: '/event/1',
              src: '/assets/img/dummyEvent.jpg',
              date: 'November 11, 2017',
              title: 'Indie Game Hackathon',
              locationName: 'George Brown College (Casa Loma Campus)',
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
              url: '/event/1',
              src: '/assets/img/dummyEvent.jpg',
              date: 'November 11, 2017',
              title: 'Indie Game Hackathon',
              locationName: 'George Brown College (Casa Loma Campus)',
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
            }
          ]]
      }
    },
  methods: {
    submit: function() {
        axios.post('/api/submit',{
          search:this.search
        })
      }
  }
})
</script>

<script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDuP7giqQQp4O8FE8oL41qjWLyFlcv3Ws8&callback=initMap">
</script>
@stop
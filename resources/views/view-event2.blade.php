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
                <v-container class="white ml-7 mr-7 mb-0 pa-0" style="position:relative; z-index:1; margin-top:100px; width: 75%;">
                    <v-layout column wrap>
                        <v-flex xs12>
                            <v-layout row wrap>
                                <v-layout column>
                                    <v-flex xs12 class="blue">
                                        <v-card class="transparent" tile flat>
                                            <v-card-text style="font-family: 'Lora', serif;">Date</v-card-text>
                                        </v-card>
                                    </v-flex>
                                    <v-flex xs12 class="yellow darken-2">
                                        <v-card-text style="font-family: 'Cinzel', serif; font-size:1.5em"><b>Name of the event</b></v-card-text>
                                    </v-flex>
                                    <v-flex xs12 class="purple darken-2">
                                        <v-card-text style="font-family: 'Lora', serif;">Price</v-card-text>
                                    </v-flex>
                                </v-layout>
                                <v-flex xs8 class="yellow" style="height:400px; border-right-width:1px; border-right-style:solid;">
                                    <v-card-text>Picture here</v-card-text>
                                </v-flex>
                                <v-flex xs8 class="text-xs-left" style="border-top-style:solid; border-top-width:1px;border-bottom-style:solid;border-bottom-width:1px">
                                    <v-tooltip bottom>
                                        <v-btn slot="activator" icon><v-icon>share</v-icon></v-btn>
                                        <span>Share</span>
                                    </v-tooltip>
                                </v-flex>
                                <v-flex xs4 class="text-xs-center" style="border-top-style:solid; border-top-width:1px;border-bottom-style:solid;border-bottom-width:1px">
                                    <v-card class="transparent" tile flat>
                                        <v-btn tile color="green" style="width:90%; font-family: 'Belleza', sans-serif;">Join Event</v-btn>
                                    </v-card>
                                </v-flex>
                                <v-flex xs8>
                                    <v-layout column>
                                        <v-flex xs12 class="green text-xs-center pa-3" style="height:400px;">
                                            <v-card-text style="font-family: 'Lora', serif;">Description Here <br/> Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum</v-card-text>
                                        </v-flex>
                                        <v-flex xs12 class="text-xs-left pa-3">
                                            <v-card-text>Tags Here</v-card-text>
                                        </v-flex>
                                    </v-layout>
                                </v-flex>
                                <v-layout column>
                                    <v-flex xs12 class="purple text-xs-left pl-1 pt-3">
                                        <v-card-text style="font-family: 'Lora', serif;">Date and Time</v-card-text>
                                    </v-flex>
                                    <v-flex xs12 class="yellow text-xs-left pl-1 pt-3">
                                        <v-card-text style="font-family: 'Lora', serif;" >Location</v-card-text>
                                    </v-flex>
                                </v-layout>
                                <v-flex xs12>
                                    <hr style="text-align:center; margin: 0 auto; border-top: 1px solid #8c8b8b;" width="80%">
                                </v-flex>
                                <v-flex xs12 class="text-xs-center pa-3">
                                    <v-card-text style="font-family: 'Lora', serif;">Creator Profile</v-card-text>
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
                                                                <span class="white black--text" style="font-family: 'Lora', serif;">@{{ event.price }}</span>
                                                            </v-flex>
                                                        </v-layout>
                                                    </v-container>
                                                </v-card-media>
                                            </a>
                                            <v-card-title>
                                                <div>
                                                    <span class="grey--text" style="font-family: 'Lora', serif;">@{{ event.date }}</span><br>
                                                    <span style="font-family: 'Cinzel', serif;"><b>@{{ event.title }}</b></span><br>
                                                    <span class="grey--text" style="font-family: 'Lora', serif;">@{{ event.locationName }}</span>
                                                </div>
                                            </v-card-title>
                                            <v-card-actions class="ma-0 pa-0">
                                                <v-container class="ma-0 pa-0">
                                                    <v-layout row wrap>
                                                        <v-flex class="text-xs-left" style="border-top: solid 1px #E8E8E8;" xs10>
                                                            <a v-for="tag in event.tags" :href= tag.link class="pa-2" style="font-family: 'Lora', serif;">@{{ tag.name }}</a>
                                                        </v-flex>
                                                        <v-flex class="text-xs-center" style="border-top: solid 1px #E8E8E8; border-left: solid 1px #E8E8E8;" xs2>
                                                            <v-tooltip bottom>
                                                                <v-btn slot="activator" icon  @click.native.stop="getShareLink(i,k)"><v-icon>share</v-icon></v-btn>
                                                                <span>Share</span>
                                                            </v-tooltip>
                                                        </v-flex>
                                                    </v-layout>
                                                </v-container>
                                            </v-card-actions>
                                            <v-dialog v-model="share" max-width="400" persistent>
                                                <v-card>
                                                    <v-container fluid>
                                                        <v-layout column wrap>
                                                            <v-flex xs12>
                                                                <v-card-title class="headline">Copy this link to share</v-card-title>
                                                            </v-flex>
                                                            <v-flex xs12>
                                                                <v-text-field v-model="shareLink" readonly focus></v-text-field>
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
            data: {
                share:false,
                shareLink:'',
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
                            url: '/event/2',
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
                            url: '/event/3',
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
                            url: '/event/4',
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
                            url: '/event/5',
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
                            url: '/event/6',
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
            },
            methods: {
                submit: function() {
                    axios.post('/api/submit',{
                        search:this.search
                    })
                },
                getShareLink: function(row,column){
                    this.shareLink = "https://php-project-willieduke.c9users.io" + this.suggestions[row][column].url
                    this.share = true
                }
            }
        })
    </script>

    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDuP7giqQQp4O8FE8oL41qjWLyFlcv3Ws8&callback=initMap">
    </script>
@stop
@extends('master')

@section('style')
    <style type="text/css">

        #description{
            font-family: 'Kaushan Script';
        }

        #map {
            height: 400px;
        }
        #container{
            padding-right:30px;
            padding-left:30px;
        }
        #app{
            background-color:#f9fbff;
        }
        #footer{
            margin-top: 600px;
        }
    </style>
@stop

@section('body')
<v-container fluid class="transparent" id="container">
    <v-layout row>
        <v-flex xs3 class="mr-5">
            <v-layout column wrap>
                <v-flex xs12>
                    <v-card class="transparent elevation-1" tile flat >
                        <v-card-text id="map"></v-card-text>
                    </v-card>
                </v-flex>
                <v-flex xs12>
                    <v-expansion-panel style="height:4vh; box-shadow:none;">
                        <v-expansion-panel-content class="elevation-1">
                            <div slot="header" style="font-family: 'Belleza', sans-serif; font-size:1.25em;">Category</div>
                            <v-card>
                                <v-card-text v-for="(category,i) in categories" :key="i" style="font-family: 'Belleza', sans-serif; font-size:1.25em;">
                                    <a :href=category.url>@{{ category.text }}</a>
                                </v-card-text>
                            </v-card>
                        </v-expansion-panel-content>
                        <v-expansion-panel-content class="elevation-1">
                            <div slot="header" style="font-family: 'Belleza', sans-serif; font-size:1.25em;">Types</div>
                            <v-card>
                                <v-card-text v-for="(type,k) in types" :key="k" style="font-family: 'Belleza', sans-serif; font-size:1.25em;">
                                    <a :href=type.url>@{{ type.text }}</a>
                                </v-card-text>
                            </v-card>
                        </v-expansion-panel-content>
                    </v-expansion-panel>
                </v-flex>
            </v-layout>
        </v-flex>
        <v-flex xs12 class="ml-5">
            <section>
                <v-layout column wrap>
                    <v-flex xs12 class="mb-5">
                        <v-card class="transparent" tile flat>
                            <v-card-text class="text-xs-center pb-0 mb-0 display-1" style="font-family: 'Cinzel', serif;"><b>SIMPLIFYING EVENTS</b></v-card-text>
                            <v-card-text id="description"><span style="color:red;font-size:2.5em; font-weight:bold; font-family: Kaushan Script', cursive;">Plantastic</span><span style="font-size:1.5em; font-family: 'Lora', serif;">&nbsp; acts as a brand new, efficient and interactive event creation tool for George Brown College Faculty and Students. Our main goal is to <b>facilitate the event creation and management process</b> while creating and maintaining a high quality internal social network.</span></v-card-text>
                        </v-card>
                    </v-flex>
                    <v-flex xs12 v-for="(e,i) in event[page-1]" :key="i">
                        <v-card raised class="mb-5">
                            <v-container class="white ma-0 pa-0" fluid>
                                <v-layout column>
                                    <v-flex xs12>
                                        <v-layout class="ma-0 pa-0" row wrap>
                                            <v-flex xs3 class="grey lighten-2">
                                                <a :href="link(e)">
                                                    <v-card-media :src=e.background_photo height="175px" contain></v-card-media>
                                                </a>
                                            </v-flex>
                                            <v-flex xs5>
                                                <div class="text-xs-left ma-0 pa-1" style="font-family: 'Lora', serif;">@{{ e.startdate }}&nbsp; @{{e.starttime}}</div>
                                                <div class="headline text-xs-left pl-1" style="font-family: 'Cinzel', serif;"><b>@{{ e.title }}</b></div>
                                                <div class="text-xs-left ma-0 pa-1" style="font-family: 'Lora', serif;">@{{ e.location }}</div>
                                            </v-flex>
                                            <v-flex xs4>
                                                <v-card-text style="font-family: 'Lora', serif;" v-if="calcPercentage(e) >= 100"><v-progress-linear v-model="calcPercentage(e)" v-bind:color="getColor(e)"></v-progress-linear> Event is full</v-card-text>
                                                <v-card-text style="font-family: 'Lora', serif;" v-else><v-progress-linear v-model="calcPercentage(e)" v-bind:color="getColor(e)"></v-progress-linear> @{{ e.registered_amount }} / @{{ e.capacity }} people has registered</v-card-text>
                                            </v-flex>
                                        </v-layout>
                                    </v-flex>
                                    <v-flex xs12>
                                        <v-layout row wrap>
                                            <v-flex d-flex class="text-xs-center grey lighten-3" xs3>
                                                <v-card-text class=" pt-1" style="font-family: 'Lora', serif;" v-if='e.price > 0'>$@{{ e.price }}</v-card-text>
                                                <v-card-text class=" pt-1" style="font-family: 'Lora', serif;" v-else>Free</v-card-text>
                                            </v-flex>
                                            <v-flex class="text-xs-left" style="border-top-style:solid; border-top-width:1px" xs5>
                                                <a :href="getTypeURL(e.event_type_id)" class="pa-1 ma-1" style="font-family: 'Lora', serif;"><strong>#@{{ e.event_type_name }}</strong></a>
                                                <a :href="getCatURL(e.category_id)" class="pa-1 ma-1" style="font-family: 'Lora', serif;"><strong>#@{{ e.category_name }}</strong></a>
                                            </v-flex>
                                            <v-flex d-flex class="text-xs-right" style="border-top-style:solid; border-top-width:1px"  xs4>
                                                <v-tooltip bottom>
                                                    <v-btn slot="activator" icon @click.native.stop="getShareLink(i)"><v-icon>share</v-icon></v-btn>
                                                    <span>Share</span>
                                                </v-tooltip>
                                            </v-flex>
                                        </v-layout>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                        </v-card>
                    </v-flex>
                    <v-flex>
                        <div class="text-xs-center">
                            <v-pagination :length="pages" v-model="page" :total-visible="7" circle></v-pagination>
                        </div>
                    </v-flex>
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
                </v-layout>
            </section>
        </v-flex>
    </v-layout>
</v-container>
@stop

@section('script')

    <script>
        var allType = <?php echo json_encode($event_type); ?>;
        var allCategory = <?php echo json_encode($category); ?>;
        var allEvent = <?php echo json_encode($pagi); ?>;
        var events = <?php echo json_encode($event); ?>;
        console.log(allCategory);
        console.log(allEvent);
        console.log(allType);
        
        function initMap() {
            var infowindow = new google.maps.InfoWindow;
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 12,
                center: {lat: events[1].lat, lng: events[1].lng}
            });
            
            for(var i = 0; i < events.length; i++){
                
                marker = new google.maps.Marker({
                    position: new google.maps.LatLng(events[i].lat, events[i].lng),
                    map: map
                });
                
                google.maps.event.addListener(marker, 'click', (function(marker, i) {
                    return function() {
                        infowindow.setContent(events[i].title);
                        infowindow.open(map, marker);
                    }
                })(marker, i));
            }
            
        }

        new Vue({
            el: '#app',
            data: {
                event: allEvent,
                shareLink:'',
                share: false,
                page: 1,
                categories: allCategory,
                types: allType
            },
            methods: {
                calcPercentage: function(e){
                    return (e.registered_amount/e.capacity)*100
                },
                getShareLink: function(index){
                    this.shareLink = window.location.href.replace("/home","") + "/event/" + this.event[this.page-1][index].id
                    this.share = true
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
                link: function(event) {
                    return '/event/'+ event.id
                },
                getTypeURL: function(id){
                    return "/event-type/" + id
                },
                getCatURL: function(id){
                    return "/category/" + id
                }
            },
            computed:{
                pages: function() {
                    return this.event.length
                }

            }
        })
    </script>

    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDuP7giqQQp4O8FE8oL41qjWLyFlcv3Ws8&callback=initMap">
    </script>

@stop
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
                <v-container class="white ml-7 mr-7 mb-0 pa-0" style="position:relative; z-index:1; margin-top:100px; width: 65%;">
                    <v-layout column wrap>
                        <v-flex xs12>
                            <v-layout row wrap>
                                <v-flex d-flex xs4 class="grey lighten-4">
                                    <v-layout column wrap>
                                        <v-flex d-flex class="">
                                            <v-card class="transparent" tile flat>
                                                <v-card-text style="font-family: 'Lora', serif;">@{{ fullStartDate}}</v-card-text>
                                            </v-card>
                                        </v-flex>
                                        <v-flex d-flex class="">
                                            <v-card-text style="font-family: 'Cinzel', serif; font-size:2em;"><b>@{{ event.title}}</b></v-card-text>
                                        </v-flex>
                                        <v-flex d-flex >
                                            <v-card-text style="font-family: 'Lora', serif; font-size:1.25em;" v-if='event.price > 0'>$@{{ event.price}}</v-card-text>
                                            <v-card-text style="font-family: 'Lora', serif; font-size:1.25em; " v-else>Free</v-card-text>
                                        </v-flex>
                                    </v-layout>
                                </v-flex>
                                <v-flex d-flex xs8 class="" style="height:400px; border-left-width:1px; border-left-style:solid;">
                                    <v-card-media contain :src=event.background_photo></v-card-text>
                                </v-flex>
                                
                                
                                <v-flex xs8 class="text-xs-left" style="border-top-style:solid; border-top-width:1px;border-bottom-style:solid;border-bottom-width:1px">
                                    <v-tooltip bottom>
                                        <v-btn @click="getLink" slot="activator" icon><v-icon>share</v-icon></v-btn>
                                        <span>Share</span>
                                    </v-tooltip>
                                </v-flex>
                                <v-flex xs4 class="text-xs-center" style="border-top-style:solid; border-top-width:1px;border-bottom-style:solid;border-bottom-width:1px">
                                    <v-card class="transparent" tile flat>
                                        <v-btn tile color="red" style="width:90%; font-family: 'Belleza', sans-serif;" v-if="event.registered_amount >= event.capacity">Event is full</v-btn>
                                        <v-btn tile href="/login" color="green" style="width:90%; font-family: 'Belleza', sans-serif;" v-if="event.registered_amount < event.capacity && user_login == null">Join Event</v-btn>
                                        <v-btn tile color="grey" style="width:90%; font-family: 'Belleza', sans-serif;" v-if="event.registered_amount < event.capacity && user_login != null && attended == true">Joined</v-btn>
                                        <v-btn tile color="green" style="width:90%; font-family: 'Belleza', sans-serif;" v-if="event.registered_amount < event.capacity && user_login != null && attended == false " @click.native="join">Join Event</v-btn>
                                        
                                    </v-card>
                                </v-flex>
                                <v-flex xs8>
                                    <v-layout column>
                                        <v-flex xs12 class=" text-xs-center pa-3" style="height:400px;">
                                            <v-card-text style="font-family: 'Lora', serif;"><div class="headline text-xs-center">Description</div> 
                                            <br/> @{{ event.description }} </v-card-text>
                                        </v-flex>
                                        <v-flex xs12 class="text-xs-center pa-3">
                                            <v-btn round :href="getTypeURL(event.event_type_id)"outline color="primary">@{{ event.event_type_name }}</v-btn>
                                            <v-btn round :href="getCatURL(event.category_id)" outline color="secondary">@{{ event.category_name }}</v-btn>
                                        </v-flex>
                                    </v-layout>
                                </v-flex>
                                <v-flex xs4>
                                    <v-layout column>
                                        <v-flex xs12 class="text-xs-left pl-1 pt-3">
                                            <v-card-text style="font-family: 'Lora', serif;"> <div class="headline text-xs-center"> Date and Time</div>
                                            <br/><b>From:</b> @{{ fullStartDate }}
                                            <br/><b>To: </b> @{{ fullEndDate }}</v-card-text>
                                        </v-flex>
                                        <v-flex xs12 class="text-xs-left pl-1 pt-3">
                                            <v-card-text style="font-family: 'Lora', serif;" > <div class="headline text-xs-center">Location</div>
                                            <br/>@{{ event.location }}</v-card-text>
                                        </v-flex>
                                    </v-layout>
                                </v-flex>
                                
                                <v-flex xs12>
                                    <hr style="text-align:center; margin: 0 auto; border-top: 1px solid #8c8b8b;" width="80%">
                                </v-flex>
                                <v-flex xs12 class="text-xs-center pa-3">
                                    <v-card-text style="font-family: 'Lora', serif;"><div class="headline">Creator Profile</div>
                                    <br/>
                                    @{{ event.organizer_description }}</v-card-text>
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
                    <v-dialog v-model="share" max-width="400" persistent lazy>
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
                    <v-carousel hide-controls light>
                        <v-carousel-item v-for="(suggestion,i) in recommendations" src="/" :key="i">
                            <v-container>
                                <v-layout row wrap>
                                    <v-flex xs4 v-for="(s,k) in suggestion" :key="k">
                                        <v-card raised class="mr-3">
                                            <a :href= "link(s)">
                                                <v-card-media  style="border-bottom: solid 1px #E8E8E8;" class="text-xs-right" height="200px" :src= s.background_photo contain>
                                                    <v-container fill-height fluid>
                                                        <v-layout fill-height>
                                                            <v-flex xs12 align-end flexbox>
                                                                <span class="white black--text" style="font-family: 'Lora', serif;" v-if="s.price > 0">$ @{{ s.price }}</span>
                                                                <span class="white black--text" style="font-family: 'Lora', serif;" v-else>Free</span>
                                                            </v-flex>
                                                        </v-layout>
                                                    </v-container>
                                                </v-card-media>
                                            </a>
                                            <v-card-title>
                                                <div>
                                                    <span class="grey--text" style="font-family: 'Lora', serif;">@{{ s.startdate }}</span><br>
                                                    <span style="font-family: 'Cinzel', serif;"><b>@{{ s.title }}</b></span><br>
                                                    <span class="grey--text" style="font-family: 'Lora', serif;">@{{ s.location }}</span>
                                                </div>
                                            </v-card-title>
                                            <v-card-actions class="ma-0 pa-0">
                                                <v-container class="ma-0 pa-0">
                                                    <v-layout row wrap>
                                                        <v-flex class="text-xs-left" style="border-top: solid 1px #E8E8E8;" xs10>
                                                            <a :href="getTypeURL(s.event_type_id)" class="pa-2" style="font-family: 'Lora', serif;">#@{{ s.event_type_name }}</a>
                                                            <a :href="getCatURL(s.category_id)" class="pa-2" style="font-family: 'Lora', serif;">#@{{ s.category_name }}</a>
                                                        </v-flex>
                                                        <v-flex class="text-xs-center" style="border-top: solid 1px #E8E8E8; border-left: solid 1px #E8E8E8;" xs2>
                                                            <v-tooltip bottom>
                                                                <v-btn slot="activator" icon  @click="getShareLink(i,k)"><v-icon>share</v-icon></v-btn>
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
        var event = <?php echo json_encode($event); ?>;
        var suggestions = <?php echo json_encode($pagi); ?>;
        var allType = <?php echo json_encode($all_type); ?>;
        var allCat = <?php echo json_encode($all_cat); ?>;
        var user_login = <?php echo json_encode($user_login); ?>;
        var attended = <?php echo $attended; ?>;
        console.log(attended);
        console.log(suggestions);
        console.log(allType);
        console.log(allCat);
        console.log(event);
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
        var vm = new Vue({
            el: '#app',
            data: {
                fullStartDate: '',
                fullEndDate: '',
                attended: attended,
                types: allType,
                categories: allCat,
                user_login: user_login,
                event: event,
                share:false,
                shareLink:'',
                recommendations: suggestions
            },
            methods: {
                getShareLink: function(row,column){
                    this.shareLink = "https://php-project-willieduke.c9users.io/event/" + this.recommendations[row][column].id
                    this.share = true
                },
                link: function(event) {
                    return '/event/'+ event.id
                },
                getLink: function(){
                    this.shareLink = window.location.href
                    this.share = true
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
                join: function(){
                    axios.post('/event/attend', {
                        user: this.user_login.id,
                        event: this.event.id
                      })
                      .then(function (response) {
                        console.log(response);
                        if(response.data > 0){
                            vm.attended = true
                        }else{
                            alert("Event is full")
                            window.location.reload(true)
                        }
                      })
                      .catch(function (error) {
                        console.log(error);
                      });
                },
                getTypeURL: function(id){
                    return "/event-type/" + id
                },
                getCatURL: function(id){
                    return "/category/" + id
                }
            },
            mounted: function(){
                var formats = {
                weekday: "long", year: "numeric", month: "short",
                day: "numeric", hour: "2-digit", minute: "2-digit"
                }
                this.fullStartDate = this.convertToDateObject(this.event.startdate + " " + this.event.starttime).toLocaleTimeString("en-us", formats)
                this.fullEndDate = this.convertToDateObject(this.event.enddate + " " + this.event.endtime).toLocaleTimeString("en-us", formats)
            }
        })
    </script>

    <script async defer
            src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDuP7giqQQp4O8FE8oL41qjWLyFlcv3Ws8&callback=initMap">
    </script>
@stop
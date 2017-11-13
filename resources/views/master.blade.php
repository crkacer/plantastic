<!DOCTYPE html>
<html>
<meta name="csrf-token" content="{{ csrf_token() }}" />
<meta name="_token" content="{{ csrf_token() }}" />
<head>
    <link href='https://fonts.googleapis.com/css?family=Roboto|Material+Icons' rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Kaushan+Script" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Belleza" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Cinzel" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Lora" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Merriweather" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Alegreya" rel="stylesheet">
    <link href="https://unpkg.com/vuetify/dist/vuetify.min.css" rel="stylesheet">

    <style type="text/css">
        a {
            text-decoration:none;
            color:black;
        }
        #title{
            color:red;
            font-family: 'Kaushan Script', cursive;
            font-size:30px;
        }
        #search::-webkit-input-placeholder{
            color:grey;
            font-style:italic;
        }
    </style>
    @yield('style')
</head>
<body>
<div>
    <v-toolbar class="white" style="z-index:2;" id="navbar">
        <v-toolbar-title><a href="/home" id="title">Plantastic</a></v-toolbar-title>
        <v-spacer></v-spacer>
        <form method="post" action="/search" ref="formSearch">
            <v-text-field name="input" style="width:200%;" append-icon="search" v-bind:append-icon-cb="submit" v-model="search" hide-details single-line placeholder="Event Names, Description, Location, Type..." id="search" required></v-text-field>
            {!! csrf_field() !!}        
        </form>
        <v-spacer></v-spacer>
        <v-toolbar-items class="hidden-sm-and-down">
            <v-btn flat href="/home" class="button">Home</v-btn>
        </v-toolbar-items>

        <v-toolbar-items>
            <v-menu open-on-hover offset-y>
                <v-btn flat slot="activator" v-if="user_login"><v-avatar size="32px"><img :src=user_login.profile_picture></v-avatar>&nbsp; @{{ user_login.firstname }}</v-btn>
                <v-list class="ma-0 pa-0 grey lighten-4">
                    <v-list-tile v-for="(dropdown,c) in dropdowns" :key="c" @click="redirect(dropdown.url)">
                        <v-list-tile-title>@{{ dropdown.text }}</v-list-tile-title>
                    </v-list-tile>
                    <v-list-tile @click="redirect('/logout')">
                        <v-list-tile-title>Sign out</v-list-tile-title>
                    </v-list-tile>
                </v-list>
            </v-menu>
        </v-toolbar-items>

        <v-toolbar-items class="hidden-sm-and-down" v-if="!user_login">
            <v-btn flat href="/register" class="button">Register</v-btn>
        </v-toolbar-items>

        <v-toolbar-items class="hidden-sm-and-down" v-if="!user_login">
            <v-btn flat href="/login" class="button">Sign In</v-btn>
        </v-toolbar-items>

        <v-toolbar-items class="hidden-sm-and-down" v-if="!user_login">
            <v-btn flat href="/login">Create Event</v-btn>
        </v-toolbar-items>
        <v-toolbar-items class="hidden-sm-and-down" v-else>
            <v-btn flat href="/event/create">Create Event</v-btn>
        </v-toolbar-items>
    </v-toolbar>

    <v-app id="app">
        @yield('body')
        <v-footer class="grey darken-4 pa-3" id="footer" style="z-index:2;">
            <v-spacer></v-spacer>
            <div style="color:white">© @{{ new Date().getFullYear() }}</div>
        </v-footer>
    </v-app>
</div>
<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script src="https://unpkg.com/vue/dist/vue.js"></script>
<script src="https://unpkg.com/vuetify/dist/vuetify.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script>
    //axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    var user_login = <?php echo json_encode($user_login); ?>;
    
    new Vue({
        el: '#navbar',
        data: {
            search:'',
            showUserMenu: false,
            buttons: [
                {
                    text: 'Home',
                    url: '/home'
                },
                {
                    text: 'Register',
                    url: '/register'
                }],
            user_login: user_login,
            dropdowns: [
                {
                    text: 'User Profile',
                    url: '/user/profile/'
                },
                {
                    text: 'Manage Event',
                    url: '/user/manage-event/'
                }]
        },
        methods: {
            submit: function() {
               this.$refs.formSearch.submit()
            },
            redirect: function(link){
                window.location.href=link
            }
        }
    })
</script>
@yield('script')
</body>
</html>
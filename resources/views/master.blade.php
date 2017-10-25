<!DOCTYPE html>
<html>
<head>
 <link href='https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Material+Icons' rel="stylesheet">
 <link href="https://unpkg.com/vuetify/dist/vuetify.min.css" rel="stylesheet">
 @yield('style')
</head>
<body>
<div id="app">
 <v-app id="inspire" class="centered" id="background">
   <v-toolbar class="white">
    <v-toolbar-title><a href="/home" id="title">Plantastic</a></v-toolbar-title>
    <v-spacer></v-spacer>
    <form method="post">
      <v-text-field style="width:150%;" append-icon="search" v-bind:append-icon-cb="submit" v-model="search" hide-details single-line placeholder="Enter any search events" required></v-text-field>
    </form>
    <v-spacer></v-spacer>
    <v-toolbar-items class="hidden-sm-and-down" v-for="button in buttons" :key="button.text">
      <v-btn flat :href=button.url class="button">@{{ button.text }}</v-btn> 
    </v-toolbar-items>
    <v-toolbar-items class="hidden-sm-and-down">
      <v-btn flat href="/login">Create Event</v-btn>
    </v-toolbar-items>
  </v-toolbar>
  @yield('body')
  <v-footer class="grey darken-4 pa-3" id="footer">
    <v-spacer></v-spacer>
    <div style="color:white">© @{{ new Date().getFullYear() }}</div>
  </v-footer>
 </v-app>
</div>
<script src="https://unpkg.com/vue/dist/vue.js"></script>
<script src="https://unpkg.com/vuetify/dist/vuetify.js"></script>
@yield('script')
</body>
</html>
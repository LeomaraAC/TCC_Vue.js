
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

window.VeeValidate = require( 'vee-validate');
window.Vue.use(VeeValidate);
window.pt_br = require( 'vee-validate/dist/locale/pt_BR');

window.Vuetify = require ('vuetify');
window.Vue.use(Vuetify);

// require( 'vuetify/dist/vuetify.min.css');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// Vue.component('example-component', require('./components/ExampleComponent.vue'));
Vue.component('sara-breadcrumb', require('./components/shared/Breadcrumb.vue'));
Vue.component('sara-input', require('./components/shared/Input.vue'));
Vue.component('sara-snackbar', require('./components/shared/Snackbar.vue'));
Vue.component('sara-tabela', require('./components/shared/Tabela.vue'));

Vue.component('sara-login', require('./components/auth/Login.vue'));

Vue.component('sara-sidebar', require('./components/layouts/Sidebar.vue'));
Vue.component('sara-card', require('./components/layouts/Card.vue'));
Vue.component('sara-navbar', require('./components/layouts/Navbar.vue'));
Vue.component('sara-app', require('./components/layouts/App.vue'));

const app = new Vue({
    el: '#app',
    mounted: function () {
        document.getElementById('app').style.visibility = 'visible';
    }
});

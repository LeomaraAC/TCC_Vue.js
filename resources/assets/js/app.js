
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

window.VueGoodTablePlugin = require('vue-good-table');
window.Vue.use(VueGoodTablePlugin);
import 'vue-good-table/dist/vue-good-table.css';

import VModal from 'vue-js-modal'
Vue.use(VModal)

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('s-breadcrumb', require('./components/shared/Breadcrumb.vue'));
Vue.component('s-input', require('./components/shared/Input.vue'));
Vue.component('s-snackbar', require('./components/shared/Snackbar.vue'));
Vue.component('s-checkbox', require('./components/shared/Checkbox.vue'));
Vue.component('s-pagination', require('./components/shared/tabela/Pagination.vue'));

Vue.component('s-login', require('./components/auth/Login.vue'));

Vue.component('s-sidebar', require('./components/layouts/Sidebar.vue'));
Vue.component('s-card', require('./components/layouts/Card.vue'));
Vue.component('s-navbar', require('./components/layouts/Navbar.vue'));
Vue.component('s-app', require('./components/layouts/App.vue'));
Vue.component('s-listagem', require('./components/layouts/Listagem.vue'));

Vue.component('s-modalpermissoes', require('./components/grupo/ModalPermissoes.vue'));
Vue.component('s-criargrupo', require('./components/grupo/CriarGrupo.vue'));

const app = new Vue({
    el: '#app',
    mounted: function () {
        document.getElementById('app').style.visibility = 'visible';
        this.$validator.localize("pt_BR");
    }
});

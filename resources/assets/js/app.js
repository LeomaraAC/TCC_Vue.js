
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

window.Vuex = require('Vuex');
window.Vue.use(Vuex);

/**
 * Vuex
 */
const store = new Vuex.Store({
    state: {
        itens: {}
    },
    mutations: {
        settItens(state, obj) {
            state.itens = obj;
        }
    }
})


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('sara-breadcrumb', require('./components/shared/Breadcrumb.vue'));
Vue.component('sara-input', require('./components/shared/Input.vue'));
Vue.component('sara-snackbar', require('./components/shared/Snackbar.vue'));
Vue.component('sara-tabela', require('./components/shared/tabela/Tabela.vue'));
Vue.component('sara-checkbox', require('./components/shared/Checkbox.vue'));
Vue.component('sara-pagination', require('./components/shared/tabela/Pagination.vue'));
Vue.component('sara-modal', require('./components/shared/modal/Modal.vue'));
Vue.component('sara-linkmodal', require('./components/shared/modal/LinkModal.vue'));

Vue.component('sara-login', require('./components/auth/Login.vue'));

Vue.component('sara-sidebar', require('./components/layouts/Sidebar.vue'));
Vue.component('sara-card', require('./components/layouts/Card.vue'));
Vue.component('sara-navbar', require('./components/layouts/Navbar.vue'));
Vue.component('sara-app', require('./components/layouts/App.vue'));
Vue.component('sara-criargrupo', require('./components/layouts/CriarGrupo.vue'));
Vue.component('sara-listagem', require('./components/layouts/Listagem.vue'));
Vue.component('sara-tabelapaginada', require('./components/layouts/TabelaPaginada.vue'));

const app = new Vue({
    el: '#app',
    store,
    mounted: function () {
        document.getElementById('app').style.visibility = 'visible';
        this.$validator.localize("pt_BR");
    }
});

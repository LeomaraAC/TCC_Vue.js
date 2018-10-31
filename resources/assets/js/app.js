
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

import VModal from 'vue-js-modal';
Vue.use(VModal);

import Multiselect from 'vue-multiselect';
Vue.component('multiselect', Multiselect);

import VueTheMask from 'vue-the-mask';
Vue.use(VueTheMask);

import VTooltip from 'v-tooltip';
Vue.use(VTooltip);



/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('s-breadcrumb', require('./components/shared/Breadcrumb.vue'));
Vue.component('s-import', require('./components/shared/inputs/Import.vue'));
Vue.component('s-input', require('./components/shared/inputs/Input.vue'));
Vue.component('s-pesquisa', require('./components/shared/inputs/Pesquisa.vue'));
Vue.component('s-checkbox', require('./components/shared/inputs/Checkbox.vue'));
Vue.component('s-radio', require('./components/shared/inputs/RadioButton.vue'));
Vue.component('s-select', require('./components/shared/inputs/Select.vue'));
Vue.component('s-snackbar', require('./components/shared/Snackbar.vue'));
Vue.component('s-pagination', require('./components/shared/tabela/Pagination.vue'));
Vue.component('s-listagem', require('./components/shared/tabela/Listagem.vue'));
Vue.component('s-tabela', require('./components/shared/tabela/Tabela.vue'));
Vue.component('s-modal', require('./components/shared/Modal.vue'));
Vue.component('s-formulario', require('./components/shared/form/Formulario.vue'));
Vue.component('s-formcard', require('./components/shared/form/FormCard.vue'));
Vue.component('s-card', require('./components/shared/Card.vue'));
Vue.component('s-box', require('./components/shared/Box.vue'));

Vue.component('s-login', require('./components/layouts/auth/Login.vue'));
Vue.component('s-criargrupo', require('./components/layouts/master/CriarGrupo.vue'));
Vue.component('s-criarusuario', require('./components/layouts/master/CriarUsuarios.vue'));
Vue.component('s-sidebar', require('./components/layouts/Sidebar.vue'));
Vue.component('s-navbar', require('./components/layouts/Navbar.vue'));


Vue.component('s-listagemalunos', require( './components/layouts/alunos/listagemAlunos.vue'));
Vue.component('s-modalimport', require('./components/layouts/alunos/modalImport.vue'));
// Vue.component('s-teste', require('./components/ExampleComponent.vue'));

Vue.component("s-listagemtipo", require('./components/layouts/atendimentos/tipos/ListagemTipo.vue'));
Vue.component("s-formulariotipo", require('./components/layouts/atendimentos/tipos/FormularioTipo.vue'));
Vue.component("s-formularioagendamento", require('./components/layouts/atendimentos/agendamento/FormularioAgendamento.vue'));
Vue.component("s-visualizacaohome", require('./components/layouts/atendimentos/agendamento/VisualizacaoHome.vue'));
Vue.component("s-pesquisaagendamento", require('./components/layouts/atendimentos/agendamento/pesquisaAgendamento.vue'));
Vue.component("s-listagemagendamento", require('./components/layouts/atendimentos/agendamento/listagemAtendimento.vue'));


window.Event = new class {
    constructor() {
        this.vue = new Vue();
    }

    fire(event, data = null) {
        this.vue.$emit(event,data);
    }

    listen(event, callback) {
        this.vue.$on(event, callback);
    }
}



const app = new Vue({
    el: '#app',
    mounted: function () {
        document.getElementById('app').style.visibility = 'visible';
        this.$validator.localize("pt_BR");
    }
});
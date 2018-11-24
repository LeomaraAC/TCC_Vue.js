<template>
   <div  v-if="empty">
        <vue-good-table 
            mode="remote"
            :columns="columns"
            :rows="rows"
            @on-sort-change="sort"
            :sort-options="{ enabled: true }"
            styleClass="table table-hover">
            <!-- SLOT DAS LINHAS -->
            <span slot="table-row" slot-scope="props">
                <span v-if="props.column.field == 'visualizar'" class="btn-icon"  v-tooltip.top-center="'Visualizar agendamento'">
                        <i class="fas fa-eye" @click="carregarDadosModal(props.formattedRow['idAgendamento'])"></i>
                </span>
                <span v-if="props.column.field == 'cancelar' && permissao_cancelar && props.formattedRow['status'] != 'Cancelada'" class="btn-icon"  v-tooltip.top-center="'Cancelar agendamento'">
                    <s-formulario :action="'/atendimento/agendamento/cancelar/' + props.formattedRow['idAgendamento']" 
                                :token="token"  method="put" :id="props.formattedRow['idAgendamento']">
                        <i  class="far fa-calendar-times" @click="cancelarAgendamento(props.formattedRow['idAgendamento'])"></i>
                    </s-formulario>
                </span>
                <span v-if="props.column.field == 'remarcar' && permissao_remarcar" class="btn-icon"  v-tooltip.top-center="'Remarcar reunião'">
                        <a :href="'/atendimento/agendamento/remarcar/'+props.formattedRow['idAgendamento']"><i class="fas fa-calendar-alt"></i></a>
                </span>
                <span v-if="props.column.field == 'registrar' && permissao_registrar && props.formattedRow['status'] == 'Agendada'" class="btn-icon"  v-tooltip.top-center="'Registrar reunião'">
                        <a :href="'/atendimento/agendamento/registrar/'+props.formattedRow['idAgendamento']"><i class="far fa-calendar-check"></i></a>
                </span>
                <span v-else>
                    {{props.formattedRow[props.column.field]}}
                </span>
            </span>
            <!-- SLOT DAS COLUNAS -->
            <span slot="table-column" slot-scope="props">
                <span v-if="props.column.label !=''" class="hover">
                    <i v-if="sortProperty == props.column.field" :class="{'fas fa-sort-alpha-down': sortDirection == 'asc' , 
                                        'fas fa-sort-alpha-up': sortDirection == 'desc'}"></i> {{props.column.label}}
                </span>
                <span v-else>
                    {{props.column.label}}
                </span>
            </span>
            <div slot="emptystate">
                <v-alert :value="true" color="red" icon="warning">
                    Nenhum dado encontrado :(
                </v-alert>
            </div>
        </vue-good-table>
        <div v-if="pagination && pagination.last_page > 1"  class="text-left">
            <s-pagination @navigate="buscaDados" :pages="pagination.last_page " ref="paginacao"></s-pagination>
        </div>
        <s-modal name="visualizarAgendamento" title="Detalhe do agendamento" :width="800">
            <span slot="modal-body">
                <div class="row">
                    <div class="col-sm-3">
                       <b>Data:</b> {{atendimento.agendamento.data}}
                    </div>
                    <div class="col-sm-3">
                        <b>Horário do início:</b> {{atendimento.agendamento.horaInicio}}
                    </div>
                    <div class="col-sm-3">
                        <b>Horário do fim:</b> {{atendimento.agendamento.horaFim}}
                    </div>
                    <div class="col-sm-3">
                            <b>Status:</b> {{atendimento.agendamento.status}}
                        </div>
                </div>
                <div class="row">
                    <div class="col-sm-4">
                        <b>Forma de atendimento:</b> {{atendimento.agendamento.formaAtendimento}}
                    </div>
                    <div class="col-sm-3">
                        <b>Tipo:</b> {{atendimento.agendamento.descricao}}
                    </div>
                    <div class="col-sm-5">
                        <b>Responsável pelo atendimento:</b> {{atendimento.agendamento.responsavel}}
                    </div>
                </div>
                <hr>
                <h5>Alunos</h5>
                <div class="row">
                    <div class="col-sm-12">
                        <vue-good-table id="tabelaAlunos"
                            :columns="columnsAlunos"
                            :rows="atendimento.alunos"
                            :sort-options="{ enabled: false }"
                            styleClass="table table-hover">
                            <div slot="emptystate">
                                <v-alert :value="true" color="red" icon="warning">Nenhum aluno participando dessa reunião :(</v-alert>
                            </div>
                        </vue-good-table>
                    </div>
                </div>
            </span>
        </s-modal>
        <s-snackbar cor="red" :msg="msgSnack" :show="showError"></s-snackbar>
   </div>
</template>
<script>
export default {
    props: {
        linkfiltro: {
            required: true,
            type: String
        },
        columns: {
            required: true,
            type: Array
        },
        permissao_cancelar: {
            type: Boolean,
            required: true
        },
        permissao_remarcar: {
            type: Boolean,
            required: true
        },
        permissao_registrar: {
            type: Boolean,
            required: true
        },
        token: {
            type: String,
            required: true
        },
    },
    data: function() {
        return{
            termo: '',
            responsavel: 'todos',
            sortProperty: "dataPrevisto",
            sortDirection: "asc",
            empty: false,
            pagination: {},
            rows: [],
            atendimento: {
                agendamento: [],
                alunos: []
            },
            msgSnack: '',
            showError: false,
            columnsAlunos: [],
        }
    },
    mounted: function (){
        Event.listen('filtrarAgendamento', busca => {
            this.termo = busca[0];
            this.responsavel = busca[1];
            this.buscaDados();
        });
        this.buscaDados();
        this.columnsAlunos = [
            { field: "prontuario", label: "Prontuário", sortable: false},
            { field: "nome", label: "Nome", sortable: false },
            { field: "responsavel", label: "Responsável", sortable: false},
            { field: "telefone", label: "Telefone", sortable: false},
            { field: "curso", label: "Curso", sortable: false}
        ];
    },
    methods: {
        sort(params) {
            this.sortDirection = params.sortType == 'asc' ? 'desc' : 'asc';
            this.sortProperty = this.columns[params.columnIndex].field;
            this.resetPage();
            this.buscaDados();
        },
        buscaDados: function (page = 1) {
            var url = this.termo === '' ? this.linkfiltro+'/'+this.sortProperty+'/'+this.sortDirection+'/'+this.responsavel+'?page='+page
                                        : this.linkfiltro+'/'+this.sortProperty+'/'+this.sortDirection+'/'+this.responsavel+'/'+this.termo+'?page='+page;
            axios.get(url).then (res => {
                this.empty = true;
                this.rows = res.data.data;
                this.pagination = res.data;
            });            
        },
        resetPage: function() {
            if(this.pagination.last_page > 1)
                this.$refs.paginacao.resetPage();
        },
        carregarDadosModal(id) {
            this.carregaDados(id);
        },
        showModal() {
            this.$modal.show("visualizarAgendamento");
        },
        closeModal() {
            this.$modal.hide("visualizarAgendamento");
        },
        carregaDados(id) {
            axios.get("/atendimento/agendamento/" + id)
                 .then (res => {
                    this.atendimento.agendamento = res.data.agendamento;
                    this.atendimento.alunos = res.data.alunos;
                    this.showModal()
                 })
                 .catch(error => {
                     this.msgSnack = error.response.data;
                     this.openSnackbar();
                 });
        },
        openSnackbar: function() {
                this.showError = true;
                setTimeout( function () {this.showError = false}.bind(this), 5000);
        },
        cancelarAgendamento: function (index) {
            this.$swal({
                text: "Tem certeza que deseja cancelar o atendimento?",
                type: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim, Cancele!',
                cancelButtonText: 'Não!'
            }).then((result) => {
                if (result.value) {
                    document.getElementById(index).submit();
                }
            })
        },
    }
}
</script>


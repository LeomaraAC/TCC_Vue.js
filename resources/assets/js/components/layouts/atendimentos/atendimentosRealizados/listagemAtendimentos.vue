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
                        <i class="fas fa-eye" @click="carregarDadosModal(props.formattedRow['idRegistro'])"></i>
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
        <s-modal name="visualizarAtendimento" title="Detalhe do atendimento" :width="800">
            <span slot="modal-body">
                <div class="row">
                    <div class="col-sm-4">
                       <b>Data:</b> {{atendimento.atendimento.dataRealizado}}
                    </div>
                    <div class="col-sm-4">
                        <b>Horário:</b> {{atendimento.atendimento.horaRealizado}}
                    </div>
                    <div class="col-sm-4">
                        <b>Forma de atendimento:</b> {{atendimento.atendimento.formaAtendimento}}
                    </div>
                </div>
                <div class="row" >
                    <div class="col-sm-3">
                        <b>Responsabilidade:</b> {{atendimento.atendimento.responsavel}}
                    </div>
                    <div class="col-sm-4" v-if="atendimento.atendimento.formaAtendimento == 'Familiar'">
                        <b>A família compareceu?</b> {{atendimento.atendimento.comparecimentoFamiliar}}
                    </div>
                    <div class="col-sm-3" v-if="atendimento.atendimento.formaAtendimento == 'Familiar'">
                        <b>Grau de parentesco:</b> {{atendimento.atendimento.grauParentesco}}
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <b>Responsáveis:</b> {{atendimento.responsaveis.nome}}</div>
                </div>
                <br>
                <div class="row">
                    <div class="col-sm-12">
                        <b>Resumo:</b> {{atendimento.atendimento.resumo}}
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
                                <v-alert :value="true" color="red" icon="warning">Nenhum aluno participou desse atendimento :(</v-alert>
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
        }
    },
    data: function() {
        return{
            termo: '',
            sortProperty: "dataRealizado",
            sortDirection: "asc",
            empty: false,
            pagination: {},
            rows: [],
            msgSnack: '',
            showError: false,
            atendimento: {
                atendimento: [],
                alunos: [],
                responsaveis: []
            },
            columnsAlunos: [],
        }
    },
    mounted: function (){
        Event.listen('filtrar', busca => {
            this.termo = busca;
            this.buscaDados();
        });
        this.buscaDados();
        this.columnsAlunos = [
            { field: "prontuario", label: "Prontuário", sortable: false},
            { field: "nome", label: "Nome", sortable: false },
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
            var url = this.termo === '' ? this.linkfiltro+'/'+this.sortProperty+'/'+this.sortDirection+'?page='+page
                                        : this.linkfiltro+'/'+this.sortProperty+'/'+this.sortDirection+'/'+this.termo+'?page='+page;
            console.log(url);
            
            axios.get(url).then (res => {
                console.log(res);
                
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
            this.$modal.show("visualizarAtendimento");
        },
        closeModal() {
            this.$modal.hide("visualizarAtendimento");
        },
        carregaDados(id) {
            axios.get("/atendimento/realizados/" + id)
                 .then (res => {
                    this.atendimento.atendimento = res.data.atendimento;
                    this.atendimento.alunos = res.data.alunos;
                    this.atendimento.responsaveis = res.data.responsaveis;
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
        }
    }
}
</script>


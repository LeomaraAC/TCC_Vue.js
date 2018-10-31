<template>
    <div>
        <vue-good-table id="tabelaListagem"
            :columns="columns"
            :rows="rows"
            :sort-options="{ enabled: false }"
            styleClass="table table-hover">
            <span slot="table-row" slot-scope="props">
                <span v-if="props.column.field == 'visualizar'" class="btn-icon"  v-tooltip.top-center="'Visualizar atendimento'">
                        <i class="fas fa-eye" @click="carregarDadosModal(Object.values(props.formattedRow)[0])"></i>
                </span>
                <span v-else>
                    {{props.formattedRow[props.column.field]}}
                </span>
            </span>
            <div slot="emptystate">
                <v-alert :value="true" color="red" icon="warning">Nenhum dado encontrado :(</v-alert>
            </div>
        </vue-good-table>
        <s-modal :name="nameModal" title="Detalhe do agendamento" :width="800">
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
                    <div class="col-sm-4">
                        <b>Tipo:</b> {{atendimento.agendamento.descricao}}
                    </div>
                    <div class="col-sm-4">
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
            <!-- <span slot="modal-footer">
                footer
            </span> -->
        </s-modal>
        <s-snackbar cor="red" :msg="msgSnack" :show="showError"></s-snackbar>
    </div>
</template>
<script>
export default {
    props: {
        columns: {
            type: [Array, Object],
            required: true
        },
        rows: {
            type: [Array, Object],
            required: true
        },
        id: {
            type: String,
            required: true
        }
    },
    data: function() {
        return {
            columnsAlunos: [],
            nameModal: 'resumoAtendimento'+this.id,
            atendimento: {
                agendamento: [],
                alunos: []
            },
            msgSnack: '',
            showError: false
        }
    },
    mounted() {
         this.columnsAlunos = [
            { field: "prontuario", label: "Prontuário", sortable: false},
            { field: "nome", label: "Nome", sortable: false },
            { field: "responsavel", label: "Responsável", sortable: false},
            { field: "telefone", label: "Telefone", sortable: false},
            { field: "curso", label: "Curso", sortable: false}
        ];
    },
    methods:{
        carregarDadosModal(id) {
            this.carregaDados(id);
        },
        showModal() {
            this.$modal.show(this.nameModal);
        },
        closeModal() {
            this.$modal.hide(this.nameModal);
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
        }
    }
}
</script>
<style>
    h5{
        padding-top: 10px;
        padding-bottom: 10px;
        color: #636363;
    }
</style>



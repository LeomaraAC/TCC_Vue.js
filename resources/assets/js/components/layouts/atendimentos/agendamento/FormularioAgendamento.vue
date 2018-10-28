<template>
    <s-formcard titulo="Novo Agendamento" icon="fas fa-calendar-alt" linkTabela="/atendimento/agendamento"
                @submitForm="validaForm" @resetForm="resetForm">
        <span slot="form">
            <s-formulario @submit="validaForm"  :action="action" :token="token"  ref="form"
                                    :method="method" id="novoAgendamento">
                <div class="row">
                    <div class="col-md-3 col-sm-6">
                        <s-input>
                            <the-mask 
                                :mask="['##/##/####']"
                                v-model="dataSelecionada"
                                masked
                                :class="{'form-control form-control-warning': true, 'is-invalid': erroData }" 
                                @blur.native="validaData"
                                placeholder="Data"/>

                            <div slot="error">
                                {{ erroData }}
                            </div>
                        </s-input>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <s-input>
                            <the-mask 
                                :mask="['##:##']"
                                v-model="horaSelecionada"
                                masked
                                :class="{'form-control form-control-warning': true, 'is-invalid': erroHora }" 
                                @blur.native="validaHora"
                                placeholder="Horário"/>
                            <div slot="error">
                                {{ erroHora }}
                            </div>
                        </s-input>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <label>Duração:</label>
                        <br>
                        <s-radio label="15 minutos" name="teste" id="quinze" value="15" @checked="checkedRadio"/>
                        <s-radio label="30 minutos" name="teste" id="trinta" value="30" @checked="checkedRadio"/>
                        <span class="erro" v-if="erroDuracao">Selecione uma opção</span>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <s-checkbox name="visivel" :ischecked="checkVisivel"
                                    label="Visível" @checked="checkedVisivel">
                        </s-checkbox>
                    </div>
                </div>
                <h5>Alunos</h5>
                <hr>
                <div class="row">
                    <div class="col-md-4 col-sm-12">
                        <s-select ref="selectAluno"
                            id="aluno"
                            :options="alunos"
                            placeholder="Aluno"
                            track-by="cpf"
                            label="nome"
                            campo="aluno"
                            :messageErro="erroAluno"
                            :erro="erroAlunoRepetido"
                            :required="true"
                            @selected="setSelectAluno"
                            >
                        </s-select>
                    </div>
                    <div class="col-md-5 col-sm-12" v-show="hasAluno">
                        <s-select ref="selectCurso"
                            id="curso"
                            :options="cursos"
                            placeholder="Curso"
                            track-by="codigo"
                            label="descricao"
                            campo="curso"
                            :required="true"
                            @selected="setSelectCurso"
                            ></s-select>
                    </div>
                    <div class="col-md-2 col-sm-12" v-show="hasCurso && hasAluno">
                        <s-input>
                            <input ref="campoSemestre" id="semestre"
                                        name="semestre"
                                        type="number"
                                        v-model="semestre"
                                        placeholder="Ano\Semestre"  
                                        v-validate="'required'"
                                        min="1"
                                        :class="{'form-control form-control-warning': true, 'is-invalid': errors.has('semestre') }"
                                        data-vv-validate-on="focusout"
                            >
                            <div slot="error">
                                {{ errors.first('semestre') }}
                            </div>
                        </s-input>
                    </div>
                    <div class="col-sm-12 col-md-1" v-show="semestre != ''">
                        <button type="button" @click="adicionar" class="btn btn-outline-success mb-3" v-tooltip.top-center="'Adicionar'">
                            <i class="fas fa-check"></i>
                        </button>
                    </div>
                </div>
                <div v-if="alunosParticiapantes.length > 0">
                    <br>
                    <s-tabela :columns="columnsSelect" :rows="alunosParticiapantes" :empty="true"
                                        :remoto="false"  :apagar="true" @apagar="removeItem">
                    </s-tabela>
                </div>
            </s-formulario>
            <s-snackbar cor="red" :msg="msgErroSnack" :show="statusLista"></s-snackbar>
        </span>
    </s-formcard>
</template>
<script>
var moment = require('moment');
export default {
    props: {
        action: {
            type: String,
            required: true
        },
        token: {
            type: String,
            required: true
        },
        method: {
            type: String,
            required: true
        },
        edicao: {
            type: Boolean,
            default: false
        },
        alunos: {
            type: [Object, Array],
            required: true
        }
    },
    data: function () {
        return {
            checkVisivel: false,
            dataSelecionada:"", // Data selecionada
            horaSelecionada:"", // Hora selecionada
            hasAluno: false, // Possui algum aluno selecionado?
            hasCurso: false, // Possui algum curso selecionado?
            alunoSelecionado: {}, // Aluno selecionado
            cursoSelecionado: {}, // Curso selecionado
            cursos: [], // Cursos que o aluno está cursando ou já cursou
            semestre: '', // Modulo/Ano do curso
            alunosParticiapantes: [], // Alunos escolhidos para participar da reunião 
            duracao: '', // Duração da reunião
            columnsSelect: [], // Colunas da tabela com os alunos que irão participar da reunião
            erroAlunoRepetido: false, // Visibilidade 
            erroData: "", // Mensagem de erro relacionado a data
            erroHora: "", // Mensagem de erro relacionado a hora
            erroAluno: '', // Mensagem de erro ao selecionar uma aluno que já está na lista 
            erroDuracao: false, // Mensagem de erro relacionado a duração da reunião
            msgErroSnack: '', // Mensagem de erro relacionado a lista de participantes.
            statusLista: false // Possui algum aluno na lista?

        }
    },
    mounted() {
        if(!this.edicao) {
            let today = new Date();
            let dd = today.getDate();
            let mm = today.getMonth()+1; 
            let yyyy = today.getFullYear();
            if(dd<10) 
            {
                dd='0'+dd;
            } 

            if(mm<10) 
            {
                mm='0'+mm;
            } 
            this.dataSelecionada = dd + '/' + mm + '/' + yyyy;
        }
        this.columnsSelect = [
            { field: "cpf", label: "",  hidden: true },
            { field: "codigoCurso", label: "",  hidden: true },
            { field: "deletar", label: '', width: '50px', sortable: false}, 
            { field: "nome", label: "Nome" },
            { field: "prontuario", label: "Prontuário" },
            { field: "descricaoCurso", label: "Curso"},
            { field: "semestre", label: "Ano\Semestre"}
        ];

    },
    methods: {
        validaForm: function() {
            this.validaHora();
            this.validaData();
            this.validaDuracao();
            this.validaAlunos();
            const lista = this.alunosParticiapantes.length > 0;
            
            if(this.erroHora == '' && this.erroData == '' && this.duracao && lista){
                axios.post('/atendimento/agendamento', {
                        horaPrevista: this.horaSelecionada,
                        dataPrevista: this.dataSelecionada,
                        duracao: this.duracao,
                        alunos: this.alunosParticiapantes,
                        visivel: this.checkVisivel

                    })
                    .then(function (response) {
                        // window.location = '/atendimento/agendamento'
                        console.log(response.data);
                        
                        
                    })
                    .catch(function(error) {
                        console.log(error.response);
                    })
            }
        },
        resetForm: function() {
            // this.$refs.campoDescricao.clear();
            // this.$refs.campoDescricao.$validator.reset(); 
            // this.$refs.campoDescricao.setFocus();    
        },
        validaHora: function(){
            let horaSplit = this.horaSelecionada.split(':');
            if(horaSplit.length == 1 && horaSplit[0].length == 0)
                        this.erroHora = "O campo horário é obrigatório.";
            else if (moment(this.horaSelecionada, 'HH:mm', true).isValid()) {
                this.erroHora = ""
            } else
                this.erroHora = "O valor do campo horário é inválido.";
             
        },
        validaData: function(){
            let dataSplit = this.dataSelecionada.split('/');
            if(dataSplit.length == 1 && dataSplit[0].length == 0)
                        this.erroData = "O campo data é obrigatório.";
            else if (moment(this.dataSelecionada, 'DD/MM/YYYY',true).isValid()) {
                this.erroData = ""
            } else
                this.erroData = "O valor do campo data é inválido.";
             
        },
        checkedRadio: function(value) {
            this.duracao = value;            
        },
        checkedVisivel: function(value) {
            this.checkVisivel = value[0];
        },
        setSelectAluno: function (value) {
            let existe = this.getIndex(value.cpf);
            if (existe < 0){
                this.$refs.selectCurso.reset();
                this.alunoSelecionado = value;
                this.semestre = '';
                this.cursoSelecionado = [];
                this.hasAluno = false;
                if(value.length == 0)
                    this.hasCurso = false;
                else{
                    this.erroAlunoRepetido = false;
                    this.erroAluno = '';
                    this.buscarCurso(value.cpf);
                }
            }
            else {
                this.erroAluno = "Aluno "+ value.nome +" já está selecionado!"
                this.erroAlunoRepetido = true;
            }
        },
        setSelectCurso: function (value) {
            this.cursoSelecionado = value;
            this.hasCurso = true;
        },
        buscarCurso: function(cpf) {
            const url = "http://projetosara.meu/matricula/"+cpf;
            axios.get(url).then(res => {
                this.hasAluno = true;
                this.cursos = res.data;
                this.hasCurso = false;
            });
        },
        adicionar: function() {
            this.statusLista = false;
            this.alunosParticiapantes.push(this.item());
        },
        item: function () {
            let item = {
                            "cpf": this.alunoSelecionado.cpf,
                            "nome": this.alunoSelecionado.nome,
                            "prontuario": this.cursoSelecionado.prontuario,
                            "codigoCurso": this.cursoSelecionado.codigo,
                            "descricaoCurso": this.cursoSelecionado.descricao,
                            "semestre": this.semestre
                        };
            this.$refs.selectAluno.reset();
            this.alunoSelecionado = {};
            this.cursoSelecionado = {};
            this.semestre = '',
            this.hasAluno = this.hasCurso = false;
            return item;
        },
        removeItem: function(cpf) {
            var index = this.getIndex(cpf);
            if (index > -1) 
                this.alunosParticiapantes.splice(index, 1);
        },
        getIndex(cpf) {
            return this.alunosParticiapantes.map(e => e.cpf).indexOf(cpf);
        },
        validaDuracao: function () {
            if(this.duracao == '')
                this.erroDuracao = true;
            else
                this.erroDuracao = false;
        },
        validaAlunos: function() {
            // Verifica o tamanho do array de alunos
            if(this.alunosParticiapantes.length == 0){
                this.msgErroSnack = "Pelo menos um aluno deve participar da reunião!";
                this.statusLista = true;
                // Voltando o valor para false depois de um segundo 
                setTimeout( function () {this.statusLista = false}.bind(this), 1000);
            }
        }
    },
    watch: {
        duracao: 'validaDuracao'
    }
}
</script>


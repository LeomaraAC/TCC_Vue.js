<template>
    <s-formcard titulo="Novo Agendamento" icon="fas fa-calendar-alt" linkTabela="/atendimento/agendamento"
                @submitForm="validaForm" @resetForm="resetForm">
        <span slot="form">
            <s-formulario @submit="validaForm"  :action="action" :token="token"  ref="form"
                                    :method="method" id="novoAgendamento">
                <div class="row">
                    <div class="col-md-3 col-sm-6">
                        <s-input>
                            <input v-mask="'##/##/####'" 
                                   ref="campoData"
                                   v-model="dataSelecionada"
                                   masked
                                   id="data"
                                   :class="{'form-control form-control-warning': true, 'is-invalid': erroData }"
                                   @blur="validaData"
                                   placeholder="Data"
                                   />
                            <div slot="error">
                                {{ erroData }}
                            </div>
                        </s-input>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <s-input>
                            <the-mask 
                                :mask="['##:##']"
                                v-model="horaInicial"
                                masked
                                id="horaInicial"
                                :class="{'form-control form-control-warning': true, 'is-invalid': erroHoraInicial }" 
                                @blur.native="validaHoraInicial"
                                placeholder="Horário inicial"/>
                            <div slot="error">
                                {{ erroHoraInicial }}
                            </div>
                        </s-input>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <s-input>
                            <the-mask 
                                :mask="['##:##']"
                                v-model="horaFinal"
                                masked
                                id="horaFinal"
                                :class="{'form-control form-control-warning': true, 'is-invalid': erroHoraFim }" 
                                @blur.native="validaHoraFinal"
                                placeholder="Horário final"/>
                            <div slot="error">
                                {{ erroHoraFim }}
                            </div>
                        </s-input>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <s-select ref="selectResponsavel"
                            id="responsavel"
                            :options="['Setor', 'Particular']"
                            placeholder="Responsável pelo atendimento"
                            campo="responsável"
                            :required="true"
                            @selected="setResponsavel"
                            >
                        </s-select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 col-sm-12">
                        <s-select ref="selectTipo"
                            id="tipo"
                            :options="tipos"
                            placeholder="Tipo de atendimento"
                            track-by="idTipo_atendimento"
                            label="descricao"
                            campo="tipo"
                            :required="true"
                            @selected="setSelectTipo"
                            >
                        </s-select>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <s-checkbox name="familiar" :ischecked="checkFamilia" ref="campoFamilia"
                                    label="Atendimento com a família" @checked="checkedFamilia">
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
                            track-by="idAluno"
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
                    <div class="col-sm-12 col-md-1" v-show="semestre">
                        <button type="button" id="adicionarAluno" @click="adicionar" class="btn btn-outline-success mb-3" v-tooltip.top-center="'Adicionar'">
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
            <s-snackbar cor="red" :msg="msgSnack" :show="showError"></s-snackbar>
            <s-snackbar cor="green" :msg="msgSnack" :show="showSuccess"></s-snackbar>
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
        },
        tipos: {
            type: [Object, Array],
            required: true
        }
    },
    data: function () {
        return {
            responsabilidade: '',
            checkFamilia: false,
            dataSelecionada:"", // Data selecionada
            horaInicial:"", // Hora selecionada
            horaFinal: '',
            alunoSelecionado: {}, // Aluno selecionado
            cursoSelecionado: {}, // Curso selecionado
            cursos: [], // Cursos que o aluno está cursando ou já cursou
            semestre: '', // Modulo/Ano do curso
            alunosParticiapantes: [], // Alunos escolhidos para participar da reunião 
            columnsSelect: [], // Colunas da tabela com os alunos que irão participar da reunião
            tipoSelecionado: {}, // Curso selecionado

            // *****************************************************************************
            hasAluno: false, // Possui algum aluno selecionado?
            hasCurso: false, // Possui algum curso selecionado?
            erroAlunoRepetido: false, // Visibilidade 
            erroData: "", // Mensagem de erro relacionado a data
            erroHoraInicial: "", // Mensagem de erro relacionado a hora
            erroAluno: '', // Mensagem de erro ao selecionar uma aluno que já está na lista
            erroHoraFim: '', 
            msgSnack: '', // Mensagem de erro
            showError: false, // Possui alguma mensagem de erro
            showSuccess: false, // Possui alguma mensagem de sucesso
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
            { field: "idAluno", label: "",  hidden: true },
            { field: "codigoCurso", label: "",  hidden: true },
            { field: "deletar", label: '', width: '50px', sortable: false}, 
            { field: "nome", label: "Nome" },
            { field: "prontuario", label: "Prontuário" },
            { field: "descricaoCurso", label: "Curso"},
            { field: "semestre", label: "Ano ou Semestre"}
        ];

    },
    methods: {
        validaForm: function() {
            this.validaHoraInicial();
            this.validaHoraFinal();
            this.validaData();
            this.validaAlunos();
            this.$refs.selectTipo.onTouch();
            this.$refs.selectResponsavel.onTouch();

            const lista = this.alunosParticiapantes.length > 0;
            let formaAtendimento = '';
            if(this.checkFamilia)
                formaAtendimento = 'Familiar';
            else if(this.alunosParticiapantes.length == 1)
                formaAtendimento = 'Individual';
            else
                formaAtendimento = 'Grupo';
            //limpar campos dos campos de selecionar o aluno
            this.resetSelecionarAluno();

            if(this.erroHoraInicial == '' && this.erroData == '' && this.erroHoraFim == ''
                && this.$refs.selectResponsavel.valid() && lista && formaAtendimento != ''
                && this.$refs.selectTipo.valid()){
                axios.post('/atendimento/agendamento', {
                        horaPrevistaInicio: this.horaInicial,
                        horaPrevistaFim: this.horaFinal,
                        dataPrevista: this.dataSelecionada, 
                        alunos: this.alunosParticiapantes,
                        responsavel: this.responsabilidade,
                        atendimento: formaAtendimento,
                        tipo: this.tipoSelecionado['idTipo_atendimento']
                    })
                    .then((response) => {
                        this.openSnackbar("Reunião agendada com sucesso!", false);
                        this.resetForm();
                    })
                    .catch((error) => {      
                        let listOfObjects = Object.keys(error.response.data).map((key) => {
                            return error.response.data[key]
                        })
                        this.openSnackbar(listOfObjects[0][0], true);
                    })
            }
        },
        resetForm: function() {
            this.dataSelecionada = this.erroData = '';

            this.horaInicial = this.horaFinal = this.erroHoraInicial = this.erroHoraFim = '';

            this.$refs.campoFamilia.reset();

            this.responsabilidade = ''
            this.checkFamilia = false;

            this.$refs.selectTipo.reset();
            this.$refs.selectResponsavel.reset();
            this.alunosParticiapantes = [];
            this.resetSelecionarAluno();
            this.$refs.campoData.focus();
        },
        resetSelecionarAluno(){
            this.$refs.selectAluno.reset();
            this.$refs.selectCurso.reset();
            this.semestre = '';
            this.erroAluno = '';
            this.erroAlunoRepetido = false;
            this.hasAluno = this.hasCurso = false;
        },
        validaHoraInicial: function(){
            this.erroHoraInicial = this.horaIsValid(this.horaInicial, "horário inicial");
            if(this.horaFinal != '')
                this.validaHoraFinal();
        },
        validaHoraFinal: function(){
            let erro = this.horaIsValid(this.horaFinal, "horário final");
            let after = moment(this.horaFinal,'HH:mm', true).isAfter(moment(this.horaInicial,'HH:mm', true));
            
            if (erro == '' && after){
                this.erroHoraFim = '';
            }
            else if(erro != ''){
                this.erroHoraFim = erro;
            }
            else{
                this.erroHoraFim = 'O termino da reunião deve ser posterior ao seu início.'
            }

        },
        horaIsValid: function (hora, campo) {
             let horaSplit = hora.split(':');
            if(horaSplit.length == 1 && horaSplit[0].length == 0)
                        return "O campo "+campo+" é obrigatório.";
            else if (moment(hora, 'HH:mm', true).isValid()) {
                return "";
            } else
                return "O valor do campo "+campo+" é inválido.";
        },
        validaData: function(){
            let dataSplit = this.dataSelecionada.split('/');
            if(dataSplit.length == 1 && dataSplit[0].length == 0)
                this.erroData = "O campo data é obrigatório.";
            else if(dataSplit[2] < 2000)
                this.erroData = "A data deve ser superior ou igual ao ano 2000";
            else if(dataSplit[2] > 2200)
                this.erroData = "A data deve ser inferior ou igual ao ano 2200";
            else if (moment(this.dataSelecionada, 'DD/MM/YYYY',true).isValid()) {
                this.erroData = ""
            } else
                this.erroData = "O valor do campo data é inválido.";
             
        },
        
        checkedFamilia: function(value){
            this.checkFamilia = value[0];
        },
        setSelectAluno: function (value) {
            let existe = this.getIndex(value.idAluno);
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
                    console.log(value);
                    
                    this.buscarCurso(value.idAluno);
                }
            }
            else {
                this.erroAluno = "Aluno "+ value.nome +" já está selecionado!"
                this.erroAlunoRepetido = true;
            }
        },
        setSelectCurso: function (value) {
            
            if(value.length != 0){
                this.cursoSelecionado = value;
                this.buscarTurma(value.prontuario);
            }
            
        },
        setSelectTipo: function (value) {
            this.tipoSelecionado = value;
        },
        setResponsavel: function (value) {
              this.responsabilidade = value;
            
        },
        buscarCurso: function(idAluno) {
            const url = "http://projetosara.meu/matricula/"+idAluno;
            axios.get(url).then(res => {
                this.hasAluno = true;
                this.cursos = res.data;
                this.hasCurso = false;
            });
        },
        buscarTurma: function(prontuario) {
            const url = "http://projetosara.meu/matricula/find/"+prontuario;
            axios.get(url).then(res => {
                this.hasCurso = true;
                this.semestre = res.data[0].turma;
            });
        },
        adicionar: function() {
            this.showError = false;
            this.alunosParticiapantes.push(this.item());
        },
        item: function () {
            let item = {
                            "idAluno": this.alunoSelecionado.idAluno,
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
        removeItem: function(idAluno) {
            var index = this.getIndex(idAluno);
            if (index > -1) 
                this.alunosParticiapantes.splice(index, 1);
        },
        getIndex(idAluno) {
            return this.alunosParticiapantes.map(e => e.idAluno).indexOf(idAluno);
        },
        validaAlunos: function() {
            // Verifica o tamanho do array de alunos
            if(this.alunosParticiapantes.length == 0){
                this.openSnackbar("Pelo menos um aluno deve participar da reunião!", true);
            }
        },
        openSnackbar: function(message, error) {
            this.msgSnack = message;
            if(error){
                this.showError = true;
                // Voltando o valor para false depois de cinco segundo 
                setTimeout( function () {this.showError = false}.bind(this), 5000);
            }
            else {
                this.showSuccess = true;
                // Voltando o valor para false depois de um segundo 
                setTimeout( function () {this.showSuccess = false}.bind(this), 5000);
            }
        }
    }
}
</script>

